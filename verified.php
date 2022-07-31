<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Điện thoại chính hãng Hoàng Kiên</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Responsive bootstrap 4 admin template" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="./admin/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
    <link href="./admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="./admin/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
    <style>
        .account-logo-box {
            background-color: #1A89DA;
        }

        .error {
            color: red;
        }

        .title {
            color: white;
            font-size: 24px;
        }

        .card {
            box-shadow: 6px 6px 15px #383838;
        }
    </style>

</head>

<body>

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card">

                        <div class="text-center account-logo-box">
                            <span class="title">HOÀNG KIÊN MOBILE</span>
                        </div>

                        <div class="card-body">

                            <div class="text-center">
                                <div class="checkmark mb-3">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                        <circle class="path circle" fill="none" stroke="#4bd396" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />
                                        <polyline class="path check" fill="none" stroke="#4bd396" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 " />
                                    </svg>
                                </div>

                                <h4><?php echo isset($_SESSION['verified'])? $_SESSION['verified']['title'] : "Đã đăng xuất"; ?></h4>
                                <p class="text-muted"><?php echo isset($_SESSION['verified'])? $_SESSION['verified']['content'] : ""; ?></p>

                            </div>

                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->

                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <!-- Vendor js -->
    <script src="./admin/assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="./admin/assets/js/app.min.js"></script>
    <script>
        function direct(){
            window.location.href= "<?php echo isset($_SESSION['verified'])? $_SESSION['verified']['link'] : "trang-chu.php"; ?>";
        }
        setTimeout(direct,2000)
    </script>

</body>

</html>