<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['phan_quyen'] != 1) {
    echo '<script>window.location.href="../login.php"</script>';
}
require_once '../module/function.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dashboard Hoàng Kiên</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Responsive bootstrap 4 admin template" name="description">
    <meta content="Coderthemes" name="author">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- App favicon -->
    <link rel="shortcut icon" href="../assets/img/icon.png">

    <!-- App css -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="assets\css\bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet">
    <link href="assets\css\icons.min.css" rel="stylesheet" type="text/css">
    <link href="assets\css\app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet">
    <link rel="stylesheet" href="./assets/css/head.css">
</head>

<body data-layout="horizontal">

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Navigation Bar-->
        <header id="topnav">
            <!-- Topbar Start -->
            <div class="navbar-custom">
                <div class="container-fluid">
                    <ul class="list-unstyled topnav-menu float-right mb-0">

                        <li class="dropdown notification-list">
                            <!-- Mobile menu toggle-->
                            <a class="navbar-toggle nav-link">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </li>

                        <div class="clearfix"></div>
                </div>
            </div>
            <!-- end Topbar -->

            <div class="topbar-menu">
                <div class="container-fluid">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <ul class="navigation-menu">

                            <li>
                                <a href="../trang-chu.php"><img src="../assets/img/logo.png" height="27" alt="Logo"></a>
                            </li>
                            <li class="has-submenu">
                                <a href="index.php"> <i class="fas fa-home"></i>Thống kê</a>
                            </li>
                            <li class="has-submenu">
                                <a href="order-management.php"> <i class="fas fa-cart-arrow-down"></i>Đơn hàng</a>
                            </li>
                            <li class="has-submenu">
                                <a href="list-product.php"> <i class="fas fa-mobile-alt"></i>Sản phẩm</a>
                                <ul class="submenu">
                                    <li><a href="list-product.php"><i class="far fa-list-alt"></i> Danh sách sản phẩm</a></li>
                                    <li><a href="add-product.php"><i class="far fa-plus-square"></i> Thêm sản phẩm</a></li>
                                    <li><a href="category.php"> <i class="fab fa-apple"></i> Hãng sản xuất</a></li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="list-user.php"> <i class="fas fa-users"></i>Người dùng</a>
                                <ul class="submenu">
                                    <li><a href="list-user.php"><i class="far fa-list-alt"></i> Danh sách người dùng</a></li>
                                    <li><a href="add-user.php"><i class="far fa-plus-square"></i> Thêm người dùng</a></li>
                                </ul>
                            </li>
                            <li class="has-submenu">
                                <a href="#"> <i class="fas fa-toolbox"></i>Khác <i class="fas fa-sort-down"></i></a>
                                <ul class="submenu">
                                    <li><a href="./list-binhluan.php"><i class="far fa-comments"></i> Bình luận</a></li>
                                    <li><a href="./list-banner.php"><i class="far fa-images"></i> Banner</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="../logout.php"> <i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                            </li>
                        </ul>
                        <!-- End navigation menu -->

                        <div class="clearfix"></div>
                    </div>
                    <!-- end #navigation -->
                </div>
                <!-- end container -->
            </div>
            <!-- end navbar-custom -->
        </header>
        <!-- End Navigation Bar-->