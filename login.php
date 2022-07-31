<?php
require_once './module/db.php';
session_start();

if (isset($_SESSION['user'])) {
    echo '<script>window.location.href="trang-chu.php"</script>';
}
if (isset($_POST['submit'])) {
    $error = array();
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (strlen($username) <= 4) {
        $error['username'] = 'Tên đăng nhập phải trên 4 kí tự';
    } elseif (strlen($username) >= 16) {
        $error['username'] = 'Tên đăng nhập phải nhỏ hơn 16 kí tự';
    } else {
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $query = $db->prepare($sql);
        $query->execute();
        if ($query->rowCount() == 0) {
            $error['username'] = 'Tên đăng nhập không tồn tại';
        }
    }
    if (count($error) == 0) {
        $sql = "SELECT * FROM user WHERE username = '$username' AND password = '" . md5($password) . "'";
        $query = $db->prepare($sql);
        $query->execute();
        if ($query->rowCount() > 0) {
            $user = $query->fetch(PDO::FETCH_ASSOC);
            if ($user['chan_user'] == 1) {
                $error['username'] = 'Tài khoản đã bị khoá';
            } else {
                unset($user['mat_khau']);
                $_SESSION['user'] = $user;

                if ($user['phan_quyen'] == 1) {
                    $_SESSION['verified']['title'] = "Đăng nhập thành công";
                    $_SESSION['verified']['content'] = "Bạn sẽ được chuyển hướng đến Dashboard";
                    $_SESSION['verified']['link'] = "./admin/index.php";
                } else {
                    if (isset($_GET['idsp'])) {
                        $_SESSION['verified']['title'] = "Đăng nhập thành công";
                        $_SESSION['verified']['content'] = "Bạn sẽ được chuyển hướng đến trang sản phẩm";
                        $_SESSION['verified']['link'] = "./product-detail.php?id=" . $_GET["idsp"];
                    } else {
                        $_SESSION['verified']['title'] = "Đăng nhập thành công";
                        $_SESSION['verified']['content'] = "Bạn sẽ được chuyển hướng đến Trang chủ";
                        $_SESSION['verified']['link'] = "./trang-chu.php";
                    }
                }
                echo '<script>window.location.href="verified.php"</script>';
            }
        } else {
            $error['password'] = 'Mật khẩu không chính xác';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App css -->
    <link href="./admin/assets\css\bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet">
    <link href="./admin/assets\css\icons.min.css" rel="stylesheet" type="text/css">
    <link href="./admin/assets\css\app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="./assets/css/header-footer.css">
    <title>Đăng nhập | Hoàng Kiên</title>
    <link rel="icon" href="./assets/img/icon.png">
</head>

<body style="background-image: linear-gradient(180deg, #c7d1ea, #a3b0d1)">

    <!-- Header -->
    <header class="p-3 bg-dark2 text-white">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between">

                <a href="./trang-chu.php" class="justify-content-start"><img src="./assets/img/logo.png" class="header-logo" height="32" alt="Logo"></a>

                <form class="search-bar input-group nav col-12 col-md-auto justify-content-center mb-md-0" action="trang-chu.php" method="GET">
                    <input type="text" name="timkiem" class="form-control" placeholder="Nhập tên điện thoại cần tìm kiếm..." aria-describedby="button-addon2" require>
                    <button class="btn btn-outline-light" type="submit" id="button-addon2"><i class="fas fa-search"></i></button>
                </form>

                <?php
                if (!isset($_SESSION['user'])) {
                    echo '
                            <div class="text-end">
                                <a href="./login.php"><button type="button" class="btn btn-outline-light me-2">Đăng nhập</button></a>
                                <a href="./register.php"><button type="button" class="btn btn-danger">Đăng ký</button></a>
                            </div>';
                } else {
                    if ($_SESSION['user']['phan_quyen'] == 1) {
                        $img = $adminImg;
                        echo '<a href="./admin/index.php"><button type="button" class="btn btn-danger me-2 "><i class="fas fa-wrench"></i></i> Dashboard</button></a>';
                    } else {
                        $img = $userImg;
                        echo '<a href="gio-hang.php"><button type="button" class="btn btn-danger me-2"><i class="fas fa-shopping-cart"></i></i> Giỏ hàng</button></a>';
                    }
                    echo '
                            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="' . $img . '" alt="user-image" width="38" height="38" class="rounded-circle">
                            </a>
                            <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
                                <li class="text-center"><strong>' . $_SESSION['user']['ho_ten'] . '</strong><hr></li>';

                    echo $_SESSION['user']['phan_quyen'] == 0 ? '<li><a class="dropdown-item" href="./lich-su.php">Lịch sử mua hàng</a></li>' : "";
                    echo '<li><a class="dropdown-item" href="./logout.php">Đăng xuất</a></li>
                            </ul>';
                }
                ?>
            </div>
        </div>
    </header>
    <!-- end Header -->

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card">

                        <div class="text-center account-logo-box">
                            <div class="mt-2 mb-2">
                                <span class="title">Đăng nhập</span>
                            </div>
                        </div>
                        <div class="card-body">

                            <form action="" method="POST">

                                <div class="row log-img">
                                    <div class="col-sm-12 text-center">
                                        <p class="text-muted"><img src="./assets/img/user.png" width="80px"></p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input class="form-control" value="<?php echo (isset($username)) ? $username : '' ?>" type="text" name="username" placeholder="Tên đăng nhập" required>
                                    <span class="error"><?php echo (isset($error['username'])) ? $error['username'] : '' ?></span>
                                </div>

                                <div class="form-group">
                                    <input class="form-control" value="<?php echo (isset($password)) ? $password : '' ?>" type="password" name="password" placeholder="Mật khẩu" required>
                                    <span class="error"><?php echo (isset($error['password'])) ? $error['password'] : '' ?></span>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-sm-12 text-right">
                                        <p class="text-muted">Bạn chưa có tài khoản? <a href="register.php" class="text-primary ml-1"><b>Đăng ký</b></a></p>
                                    </div>
                                </div>

                                <div class="form-group account-btn text-center mt-2">
                                    <div class="col-12">
                                        <button class="btn width-md btn-bordered btn-danger waves-effect waves-light" name="submit" type="submit">Đăng nhập</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <script>

                    </script>


                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <!-- Vendor js -->
    <script src="assets\js\vendor.min.js"></script>

    <!-- App js -->
    <script src="assets\js\app.min.js"></script>

</body>

</html>