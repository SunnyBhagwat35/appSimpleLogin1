<?php
require('config.php');
error_reporting(0);
session_start();

require '../../vendor/autoload.php';
if ($_SESSION['eid'] == '') {
    header('location: http://localhost/d/index');
}
$con = new MongoDB\Client('mongodb://127.0.0.1:27017');
$db = $con->php_mongo;

$collection = $db->employee;
$record = $collection->findOne(['_id' => $_SESSION['eid']]);


require('razorpay-php/Razorpay.php');

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;

$error = "Payment Failed";
// echo "yes";
// if (empty($_POST['razorpay_payment_id']) === false)
// {
$api = new Api($keyId, $keySecret);
$acc = $_GET['acc'];
$amt = $_POST['amt'];
try {
    echo "yes";
    $transfer = $api->payment->fetch($_POST['razorpay_payment_id'])->transfer(
        array(
            'transfers' => array(
                array(
                    'account' => $acc,
                    'amount' => $amt,
                    'currency' => 'INR',
                    'notes' => array(
                        'branch' => 'PATTANCHERU',
                        'name' => 'Testing Team'
                    ),
                    'linked_account_notes' => array('branch'),
                    'on_hold' => 1,
                    'on_hold_until' => 1671222870
                )
            )
        )
    );
    // $transfer = $api->order->create(array('amount' => 2000, 'currency' => 'INR', 'transfers' => array(array('account' => $acc, 'amount' => 150, 'currency' => 'INR', 'notes' => array('branch' => 'PATTANCHERU', 'name' => 'Testing Team'), 'linked_account_notes' => array('branch'), 'on_hold' => 1, 'on_hold_until' => 1671222870))));
    // print_r($transfer) . '<br>';

    // print_r('----' . $trasfer->items[0]->id);
    // echo "yes";
    // Please note that the razorpay order ID must
    // come from a trusted source (session here, but
    // could be database or something else)
    // $attributes = array(
    //     'razorpay_order_id' => $_SESSION['razorpay_order_id'],
    //     'razorpay_payment_id' => $_POST['razorpay_payment_id'],
    //     'razorpay_signature' => $_POST['razorpay_signature']
    // );

    // $api->utility->verifyPaymentSignature($attributes);
} catch (Exception $e) {
    $success = false;
    $error = 'Razorpay Error : ' . $e;
}
// }

if ($success === true) {

    $order_id = $_SESSION['razorpay_order_id'];
    // echo $transfer['id'];
    $email = $_SESSION['email'];
    $eid = $_SESSION['eid'];
    $phone_no = $_SESSION['phone_no'];
    date_default_timezone_set("Asia/Calcutta");
    $orderdate = date('d-m-y h:i:s');
    $payment_id = $_POST['razorpay_payment_id'];
    $_SESSION['razorpay_payment_id'] = $payment_id;
    $status = $_POST['status'];
    $doc_id = $_SESSION['docid'];
    $date = $_SESSION['date'];
    $s_time = $_SESSION['s_time'];
    $e_time = $_SESSION['e_time'];
    // $amt = 150;
    $_SESSION['amt'] = $amt;
    $index = $_SESSION['index'];
    $_SESSION['payment_status'] = 'success';

    echo $payment_id . '\n';
    echo $order_id;


    $collection1 = $db->manager;
    $drecord = $collection1->findOne(['d_unid' => $doc_id]);
    $hos_unid = $drecord['hos_unid'];
    $hos_nid = substr($hos_unid, 0, 5);
    $hosp_name = $drecord['hosp_name'];

    $record = $collection->updateOne(
        ['_id' => $eid],
        ['$push' => ['datetime.' . $hos_unid . '.' . $doc_id . '.' . $date => [
            'd_stamp' => date('d-M-Y H:i:s'), 'payment_status' => $_SESSION['payment_status'], 'status' => 'pending', 'amt' => $amt, 'razorpay_payment_id' => $payment_id,
            'razorpay_order_id' => $order_id, 'p_name' => $_SESSION['fname'], 'hosp_name' => $hosp_name, 'book_t' => [$s_time, $e_time]
        ]]]
    );
    date_default_timezone_set("Asia/Calcutta");
    $orderdate = date('d-M-Y h:i:s');
    $_SESSION['d_stamp'] = $orderdate;
    $collection = $db->manager;
    $drecord = $collection->findOne(['d_unid' => $doc_id]);
    //patients
    $collection->updateOne(
        ['d_unid' => $doc_id],
        ['$addToSet' => ['p_unid' => $_SESSION['p_unid']]]
    );
    //appointments
    $collection->updateOne(
        ['d_unid' => $doc_id],
        ['$push' => ['app_id' => $_SESSION['p_unid']]]
    );

    $collection = $db->admin;
    //admin
    $collection->updateOne(
        ['email' => 'admin@gmail.com'],
        ['$push' => ['app_id' => $_SESSION['p_unid']]]
    );
    //hos admin
    $collection->updateOne(
        ['hos_unid' => $hos_nid],
        ['$push' => ['app_id' => $_SESSION['p_unid']]]
    );

    $collection = $db->manager;
    $record = $collection->findOne(['d_unid' => $doc_id]);

    //booked slots
    $collection->updateOne(
        ['d_unid' => $doc_id],
        ['$push' => ['booked_slots.' . $date => [$s_time, $e_time]]]
    );
    //booked slots
    // delete Slot
    $collection->updateOne(
        ['d_unid' => $doc_id],
        ['$pull' => ['datetime.' . $date => [$s_time, $e_time]]]
    );
    // delete Slot

    header('location:http://localhost/d/view/p/success');
} else {
    $html = "<p>Your payment failed</p>
             <p>{$error}</p>";
}

echo $html;
?>
<script src="http://localhost/d/controller/js/p-booking.js?ver=3.4"></script>
