<?php
session_start();
require '../../vendor/autoload.php';
require('razorpay-php/Razorpay.php');
require('config.php');

// error_reporting(0);

use Razorpay\Api\Api;

// $con = new MongoDB\Client( 'mongodb://127.0.0.1:27017' );
// $db = $con->php_mongo; 
// $collection = $db->manager;

$accountId = 'acc_It49ya15k5WBDG';

$api = new Api($keyId, $keySecret);
echo $accountId;

try{
    $refund = $api->order->fetch($v['razorpay_order_id'])->payments();
    // $transfer = $api->order->create(array('amount' => 2000,'currency' => 'INR','transfers' => array(array('account' => 'acc_It49ya15k5WBDG','amount' => 1000,'currency' => 'INR','notes' => array('branch' => 'PATTANCHERU','name' => 'Testing Team'),'linked_account_notes' => array('branch'),'on_hold' => 1,'on_hold_until' => 1671222870))));
}
catch(Exception $E){
    echo $E;
}
// echo $transfer['id'];



?>