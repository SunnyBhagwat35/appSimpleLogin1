<?php

require './vendor/autoload.php';
require('./razorpay/razorpay-php/Razorpay.php');

use Razorpay\Api\Api;

$keyId = 'rzp_test_Se13LWnNmHqP74';
$keySecret = 'saWJv6ALheBGrknKu9JyyP8Z';
$RZ_MONTHLY = "plan_JICII8LyUcvfPH";
$RZ_YEARLY = "plan_JICHWHoQW8OUCJ";

$db_connecztion = pg_connect("host=localhost dbname=simplelogin user=postgres password=postgres");
$query = '';

$id = $_POST['id'];
$cre = $_POST['created_at'];
$up = $_POST['updated_at'];
$canurl = $_POST['cancel_url'];
$upurl = $_POST['update_url'];
$event = $_POST['event_time'];
$nextbilldate = $_POST['next_bill_date'];
$user_id = $_POST['user_id'];

if (isset($_POST['monthly'])) {
    $plan = $_POST['monthly'];

    $api = new Api($keyId, $keySecret);
    $result = $api->subscription->create(
        array(
            'plan_id' => $RZ_MONTHLY,
            'customer_notify' => 1,
            'quantity' => 1,
            'total_count' => 12,
            'addons' => array(array(
                'item' => array('name' => 'Delivery charges', 'amount' => 30000, 'currency' => 'INR')
            )),
            'notes' => array('key1' => 'value3', 'key2' => 'value2')
        )
    );

    $subid = $result["id"];
}
if (isset($_POST['yearly'])) {

    $plan = $_POST['yearly'];

    $api = new Api($keyId, $keySecret);

    $result = $api->subscription->create(
        array(
            'plan_id' => $RZ_YEARLY,
            'customer_notify' => 1,
            'quantity' => 1,
            'total_count' => 12,
            'addons' => array(array(
                'item' => array('name' => 'Delivery charges', 'amount' => 30000, 'currency' => 'INR')
            )),
            'notes' => array('key1' => 'value3', 'key2' => 'value2')
        )
    );
    echo 'h';

    $subid = $result["id"];
}
if ($result) {
    $query = "INSERT INTO public.subscription(
            id, created_at, updated_at, cancel_url, update_url, subscription_id, event_time, next_bill_date, cancelled, plan, user_id)
        VALUES (" . $id . ",'" . $cre . "','" . $up . "','" . $canurl . "','" . $upurl . "','" . $subid . "','" . $event . "','" . $nextbilldate . "', false ,'" . $plan . "'," . $user_id . ")";

    // echo $query . '<br>';
    $resultpg = pg_query($query);
    print_r($resultpg);
    header("location: ./index.php?subid=" . $subid);
}
