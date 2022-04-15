<?php
error_reporting(E_ALL);

// $query = "INSERT INTO subscription 
// VALUES(1, '2022-04-09 09:27:41.736414', '2022-04-09 09:27:41.736414', 'dummyurl', 'dummyurl', 'abcd1234', '2022-04-09 09:27:41.736414', '2023-04-09', false, 3, 'yearly')";


if (isset($_GET["subid"])) {
    $subid = $_GET["subid"];
}

?>
<!-- <!doctype html> -->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>
    <div class="container my-4">
        <div class="car">
            <div class="card-header">
                <div class="card-title">
                    <h4 class="text-center">Feel The Form!</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="handle.php" method="POST">
                    <div class="mb-3">
                        <label for="id" class="form-label">id</label>
                        <input type="text" class="form-control" id="id" placeholder="" name="id">
                    </div>
                    <div class="mb-3">
                        <label for="created_at" class="form-label">created_at</label>
                        <input type="text" class="form-control" id="created_at" placeholder="Another input placeholder" name="created_at">
                    </div>
                    <div class="mb-3">
                        <label for="updated_at" class="form-label">updated_at</label>
                        <input type="text" class="form-control" id="updated_at" placeholder="Example input placeholder" name="updated_at">
                    </div>
                    <div class="mb-3">
                        <label for="cancel_url" class="form-label">cancel_url</label>
                        <input type="text" class="form-control" id="cancel_url" placeholder="Another input placeholder" name="cancel_url">
                    </div>
                    <div class="mb-3">
                        <label for="update_url" class="form-label">update_url</label>
                        <input type="text" class="form-control" id="update_url" placeholder="Example input placeholder" name="update_url">
                    </div>
                    <div class="mb-3">
                        <label for="event_time" class="form-label">event_time</label>
                        <input type="text" class="form-control" id="event_time" placeholder="Example input placeholder" name="event_time">
                    </div>
                    <div class="mb-3">
                        <label for="next_bill_date" class="form-label">next_bill_date</label>
                        <input type="text" class="form-control" id="next_bill_date" placeholder="Another input placeholder" name="next_bill_date">
                    </div>
                    <div class="mb-3">
                        <label for="plan" class="form-label">plan</label>
                        <input type="text" class="form-control" id="plan" placeholder="Another input placeholder" name="plan">
                    </div>
                    <div class="mb-3">
                        <label for="user_id" class="form-label">user_id</label>
                        <input type="text" class="form-control" id="user_id" placeholder="Example input placeholder" name="user_id">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" name="monthly" value="monthly">Submit Monthly</button>
                        <button type="submit" class="btn btn-primary" name="yearly" value="yearly">Submit Yearly</button>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                sdf
            </div>
        </div>
    </div>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>


    <script>
        function handleSubscription(subid) {

            var options = {
                "key": "rzp_test_Se13LWnNmHqP74",
                "subscription_id": subid, // Enter the Key ID generated from the Dashboard

                "name": "Acme Corp",
                "description": "Test Transaction",
                "image": "https://example.com/your_logo",

                "theme": {
                    "color": "#3399cc"
                }
            };
            var paymentObject = new Razorpay(options);
            paymentObject.open()
        }

        <?php
        if ($_GET["subid"]) {
            echo '
        handleSubscription("' . $subid . '")
        ';
        }

        ?>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>