<?php

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

$collection = $db->manager;
$docrecord = $collection->findOne(['d_unid' => $_GET['id']]);

// echo $docrecord['fname'];
$acc =  $docrecord['acc'];
// echo $acc;
$_SESSION['docid'] = $_GET['id'];
$did = $_SESSION['docid'];
$_SESSION['date'] = $_GET['d'];
$_SESSION['s_time'] = $_GET['t'];
$_SESSION['e_time'] = $_GET['e'];
$_SESSION['index'] = $_GET['i'];


?>

<!doctype html>
<html lang='en'>

<head>
    <?php include '../../assest/top_links.php'; ?>
    <link rel="stylesheet" href="http://localhost/d/public/stylesheet/p-checkout.css?ver=1.2">
    <script src='http://localhost/d/controller/js/p-checkout.js?ver=1.7'></script>
    <title>Feely | Checkout</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
    <?php include_once '../../assest/navbar.php'; ?>

    <!-- breadcrumb -->
    <nav class='breadc navbar-expand-lg'>
        <div class='container-fluid'>
            <div class="breadcrumb d-flex flex-column mx-4 my-auto">
                <p class=" my-auto py-1">Home / Checkout</p>
                <h5 class="my-auto py-1">Checkout</h5>
            </div>
        </div>
    </nav>

    <!-- personal information -->
    <div class="container my-4">
        <div class="row">
            <div class="col-md-8">
                <div class="per_info my-4">
                    <h4 class="card-title">Personal Information</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="my-2">First Name</label>
                                <input type="text" id="pfname" name="fname" class="form-control p-2 " disabled value="<?php echo $record['fname']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="my-2">Last Name</label>
                                <input type="text" name="sname" class="form-control p-2 " disabled value="<?php echo $record['sname']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="my-2">Email</label>
                                <input type="text" id="pemail" name="email" class="form-control p-2 " disabled value="<?php echo $record['email']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="my-2">Phone No</label>
                                <input type="number" name="phone_no" maxlength="10" class="form-control p-2 " required value="<?php echo $record['gen_info']['phone_no']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pay_method my-4">
                    <h4 class="card-title">Payment</h4>

                    <div class="card-bounding row">

                        <div class="card-container col-md-6">
                            <!--- ".card-type" is a sprite used as a background image with associated classes for the major card types, providing x-y coordinates for the sprite --->
                            <label for="">Card Number</label>
                            <div class="card-type"></div>
                            <input class="form-control" placeholder="0000 0000 0000 0000" onkeyup="$cc.validate(event)" />
                            <!-- The checkmark ".card-valid" used is a custom font from icomoon.io --->
                            <!-- <div class="card-valid">&#xea10;</div> -->
                        </div>

                        <div class="card-details clearfix col-md-6">

                            <div class="expiration">
                                <label for="">Expiry Date</label>
                                <input class="form-control" onkeyup="$cc.expiry.call(this,event)" maxlength="7" placeholder="mm/yyyy" />
                            </div>
                        </div>

                        <div class="cvv my-4 col-md-6">
                            <label for="">CVV</label>
                            <input class="form-control" placeholder="XXX" />
                        </div>

                    </div>
                </div>
                <div class="my-3">

                </div>
            </div>
            <div class="col-md-4 my-4">
                <?php
                $collection = $db->manager;
                $record = $collection->findOne(['d_unid' => $_GET['id']]);


                ?>
                <div class="summary">
                    <h4 class="card-title">Booking Summary</h4>
                    <div class="d-flex justify-content-start my-3">
                        <?php
                        if ($record['profile_image'] != '') {
                            echo '<a href="#" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle mx-2" height="110" alt="User Image"
                    src="http://localhost/d/public/image/doc-img/doc-img/' . $record['profile_image'] . '"
                        ></a>';
                        } else {
                            echo '<img src="http://localhost/d/public/image/doc-img/doc-img/default-doc.jpg" class="rounded doc_img"
                    height="160" alt="User Image">';
                        } ?>
                        <div class="mx-2">
                            <h5 class="card-title">Dr. <?php echo $record['fname'] . ' ' . $record['sname']; ?></h5>
                            <div class="d-flex">
                                <i class="bi bi-star-fill text-warning "></i>
                                <i class="bi bi-star-fill text-warning px-2"></i>
                                <i class="bi bi-star-fill text-warning "></i>
                            </div>
                            <p><i class="bi bi-geo-alt-fill text-primary"></i><?php echo $record['contact_detail']['country'] . ', ' . $record['contact_detail']['city']; ?></p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <h6>Date</h6>
                        <p class="mb-1"><?php echo $_GET['d']; ?></p>
                        <!-- <p class="mb-1" id = "timess"></p> -->
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <h6>Time</h6>
                        <?php
                        if ($val[0] <= 12 && $val[1]) {
                            echo '<p class="mb-1">' . date('h:i', strtotime($_GET['t'])) . ' AM</p>';
                        } else {
                            echo '<p class="mb-1">' . date('h:i', strtotime($_GET['t'])) . ' PM</p>';
                        }
                        ?>
                        <!-- <span id = "s_timess"> -->
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <h6>Consulting Fee</h6>
                        <p class="mb-1">$ 100</p>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <h6>Booking Fee</h6>
                        <p class="mb-1">$ 50</p>
                    </div>
                    <!-- <div class="d-flex justify-content-between py-1">
                        <h6>Video Call</h6>
                        <p></p>
                    </div> -->
                    <hr>
                    <div class="d-flex justify-content-between">
                        <h5>Total</h5>
                        <p>$ 150</p>
                    </div>
                </div>

                <div class="my-5 d-grid gap-2">
                    <!-- <a href = "http://localhost/d/view/razorpay/pay?id=${doc_id}&t=${s_time}&d=${date}">  -->
                    <button class="btn btn-primary px-5 py-3" id="proccedtopay1" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Check Out with $<?php echo $amt ?></button>
                    <!-- </a> -->
                </div>

                <!-- <div class="modal fade  " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-body">
                                Do you want continue with payment?

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                                <button type="button" class="btn btn-primary" onclick="yesProceed()">Yes</button>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>





    <!-- <script src="https://checkout.razorpay.com/v1/checkout.js"></script> -->
    <!-- <script>
     var s_time = localStorage.getItem('s_time');
     var e_time = localStorage.getItem('e_time');
     var date = localStorage.getItem('date');
     var s11  = s_time;
     document.getElementById('timess').innerHTML = date;
     var s1 = parseFloat(s_time);
     if(s1 < 12)
     {
     document.getElementById('s_timess').innerHTML = s_time+' '+'AM';
     }
     else
     {
    document.getElementById('s_timess').innerHTML = s_time+' '+'PM'
    }

-->

    <?php include '../../assest/bottom_links.php'; ?>

    <script src='http://localhost/d/controller/js/p-checkout.js?ver=1.6'></script>
    <script src="http://localhost/d/controller/js/p-booking.js?ver=3.4"></script>
    <!-- <script src="http://localhost/d/controller/js/success.js?ver=2.0"></script> -->


    <!-- razor Pay code -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>


    <form name='razorpayform' action="verify.php?id=<?php echo $_GET['id'] ?>&acc=<?php echo $acc ?>" method="POST">
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
        <input type="hidden" name="amt" id="amount" value=<?php echo $amt ?>>
    </form>
    <!-- <script>
        function yesProceed() {
            document.razorpayform.submit();
            console.log("Proceed")
        }
    </script>    -->

    <script>
        // Checkout details as a json
        var options = <?php echo $json ?>;

        /**
         * The entire list of Checkout fields is available at
         * https://docs.razorpay.com/docs/checkout-form#checkout-fields
         */
        options.handler = function(response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            document.razorpayform.submit();
        };

        // Boolean whether to show image inside a white frame. (default: true)
        options.theme.image_padding = false;

        options.modal = {
            ondismiss: function() {
                console.log("This code runs when the popup is closed");
            },
            // Boolean indicating whether pressing escape key
            // should close the checkout form. (default: true)
            escape: true,
            // Boolean indicating whether clicking translucent blank
            // space outside checkout form should close the form. (default: false)
            backdropclose: false
        };

        var rzp = new Razorpay(options);

        document.getElementById('proccedtopay1').onclick = function(e) {
            rzp.open();
            e.preventDefault();
        }
    </script>


    <!-- verify.php -->



    <!-- verify.php -->




</body>

</html>
