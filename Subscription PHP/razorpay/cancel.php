<?php
session_start();
require '../../vendor/autoload.php';
require('razorpay-php/Razorpay.php');
require('config.php');

error_reporting(0);

use Razorpay\Api\Api;

$con = new MongoDB\Client('mongodb://127.0.0.1:27017');
$db = $con->php_mongo;
$collection = $db->manager;

$api = new Api($keyId, $keySecret);

if (isset($_POST['cancelled'])) {
    $pid = strval($_POST['pid']);
    $date = strval($_POST['date']);
    $date_ind = strval($_POST['date_ind']);
    $d_collection = $db->manager;
    $drecord = $d_collection->findOne(['d_unid' => $_SESSION['d_unid']]);
    $hos_unid = $drecord['hos_unid'];
    $e_collection = $db->employee;
    // print_r($e_collection);
    echo $pid;
    $erecord = $e_collection->findOne(['p_unid' => $pid]);

    print_r($erecord);
    $paymentId = strval($_POST['id']);
    // echo $razorId;

    // include './email/cancel_app_email.php';
    // echo $razorId;
    // $refund = $api->refund->create(array('payment_id' => $razorId, 'amount' => 150));
    $api->payment->fetch($paymentId)->refund(array('amount' => '100', 'reverse_all' => '1'));

    if ($refund) {
        $record = $e_collection->updateOne(
            ['p_unid' => $pid],
            ['$set' => ['datetime.' . $hos_unid . '.' . $_SESSION['d_unid'] . '.' . $date . '.' . $date_ind . '.status' => 'cancelled']]
        );
        $recordpayment = $e_collection->updateOne(
            ['p_unid' => $pid],
            ['$set' => ['datetime.' . $hos_unid . '.' . $_SESSION['d_unid'] . '.' . $date . '.' . $date_ind . '.payment_status' => 'refunded']]
        );
        echo "refunded";
        // header( 'location: http://localhost/d');
    } else {
        echo "refunded";
        // header( 'location: http://localhost/d/view/p/checkout?email=err' );
    }
}































// $pat_id = $_GET['pat_id'];
// $date = $_GET['date'];
// $status = "null";
// $paymentId = strval($_GET['id']);
// echo $paymentId;

// try{
//     // $refund = $api->refund->create(array('payment_id' => $paymentId, 'amount'=>150));
//     // $refund = $api->payment->fetch($paymentId)->refund(array('amount'=> '100','reverse_all'=>'1'));
//     $refund = $api->payment->fetch($paymentId);
//     echo $refund;
// }
// catch(Exception $e) {
//     echo $e;
// }
// if($refund){

//     echo 'success';
//     // header('location: http://localhost/d/view/d/index');
//     exit;
// }
// else{
//     echo 'failed';
// }





















    // use Razorpay\Api\Api;

    // // $con = new MongoDB\Client( 'mongodb://127.0.0.1:27017' );
    // // $db = $con->php_mongo;

    // // $pat_id = $_GET['pat_id'];
    // // $date = $_GET['date'];
    // // $status = "null";
    // $paymentId = $_GET['id'];

    // echo $paymentId;
    // // $e_collection = $db->employee;
    // // $pat = $e_collection->findOne(['p_unid'=>$pat_id]);
    // // $datetime = $e_collection->find(['razorpay_payment_id'=>$_GET['id']]);
    // // // print_r($pat);



    // // $test = $pat->updateOne(
    // //     ["datetime.BALAJ989764106.989764106.2022-01-11"=>$date],
    // //     ['set'=> ['payment_status'=>$status]],
    // // );
    // // echo $test->getMatchedCount().'success';
    // $api = new Api($keyId, $keySecret);

    // $api->payment->fetch($paymentId);
    // // $mat = $pay->amount;
    // // echo $mat;
    // // $refund = $api->refund->create(array('payment_id' => $_GET['id'], 'amount'=>150));

    // // if($refund){

    // //     echo 'success';
    // //     // header('location: http://localhost/d/view/d/index');
    // //     // exit;
    // // }
    // // else{
    // //     echo 'failed';
    // // }
