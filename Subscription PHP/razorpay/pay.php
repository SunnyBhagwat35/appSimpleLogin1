<?php

require('config.php');
require('razorpay-php/Razorpay.php');
require '../../vendor/autoload.php';
// error_reporting(0);
session_start();

use Razorpay\Api\Api;

$con = new MongoDB\Client('mongodb://127.0.0.1:27017');
$db = $con->php_mongo;
$collection = $db->manager;

$api = new Api($keyId, $keySecret);


$id = $_GET['id'];
echo $id;
$email = $_SESSION["email"];
// $phone_no = $_SESSION["phone_no"];
// $doc = $collection->findOne(['_id' => '1680225733']);
$doc = $collection->findOne(['d_unid' => $id]);

//Getting both offers if present

if ($doc['loffer']) {
    if ($doc['loffer']['nopeople'] > 0) {
        $loffer = $doc['loffer'];
        // print_r($loffer);
    }
}
if ($doc['aoffer'] > 0) {
    $aoffer = $doc['aoffer'];
    // print_r($aoffer);
}

//check and compare offers and set amount
if ($loffer || $aoffer) {
    if ($loffer['offeramt'] < $aoffer) {
        echo 'haha';
        $amt = $loffer['offeramt'];
    } elseif ($aoffer) {
        $amt = $aoffer;
    } else {
        $amt = $loffer['offeramt'];
    }
} else {
    $amt = 150;
}
echo $amt;


$orderData = [
    'receipt'         => 3456,
    'amount'          => $amt * 100, // 2000 rupees in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];

$razorpayOrder = $api->order->create($orderData);

$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;

$displayAmount = $amount = $orderData['amount'];

if ($displayCurrency !== 'INR') {
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}

$checkout = 'manual';

if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true)) {
    $checkout = $_GET['checkout'];
}

$data = [
    "key"               => $keyId,
    "amount"            => $amt,
    "name"              => "Feely Privacy",
    "description"       => "Testing Trasaction",
    "image"             => "data:image/jpeg;base64,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",
    "prefill"           => [
        "name"              => "Daft Punk",
        "email"             => $email,
        "contact"           => $phone_no,
    ],
    "notes"             => [
        "address"           => "Hello World",
        "merchant_order_id" => "12312321",
    ],
    "theme"             => [
        "color"             => "#F37254"
    ],
    "order_id"          => $razorpayOrderId,
];

if ($displayCurrency !== 'INR') {
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}


$json = json_encode($data);

require("checkout/{$checkout}.php");
