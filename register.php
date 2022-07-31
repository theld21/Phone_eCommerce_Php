<?php
require_once './header.php';
require_once './module/db.php';
if (isset($_SESSION['user'])) {
    echo '<script>window.location.href="../trangchu.php"</script>';
}
if (isset($_POST['submit'])) {
    $error      = array();
    $date       = new DateTime();
    $createdAt  = $date->format("Y-m-d H:i:s");
    $ho_ten   = $_POST['ho_ten'];
    $username   = $_POST['username'];
    $sdt      = $_POST['sdt'];
    $password   = $_POST['password'];
    $confirmPass = $_POST['confirm-password'];

    if (strlen($ho_ten) <= 10) {
        $error['ho_ten'] = 'Họ và tên phải lớn hơn 10 kí tự';
    } elseif (strlen($ho_ten) >= 30) {
        $error['ho_ten'] = 'Họ và tên phải nhỏ hơn 30 kí tự';
    }

    if (strlen($username) <= 4) {
        $error['username'] = 'Tên đăng nhập phải trên 4 kí tự';
    } elseif (strlen($username) >= 16) {
        $error['username'] = 'Tên đăng nhập phải nhỏ hơn 16 kí tự';
    } elseif (runSQL("SELECT username FROM user WHERE username='".$username."'")!=NULL) {
        $error['username'] = 'Tên đăng nhập đã tồn tại';
    }

    if (strlen($sdt) < 10 || strlen($sdt) > 12 || !str_starts_with($sdt, 0)) {
        $error['sdt'] = 'Sai số điện thoại';
    }

    if (strlen($password) < 6) {
        $error['password'] = 'Mật khẩu phải lớn hơn 5 kí tự';
    } else if (strlen($password) > 15) {
        $error['confirm-password'] = 'Mật khẩu phải nhỏ hơn 15 ký tự';
    } else if ($password != $confirmPass) {
        $error['confirm-password'] = 'Xác nhận mật khẩu không đúng';
    }

    if (count($error) == 0) {
        $password = md5($password);
        $sql = runSQL("INSERT INTO user VALUES(null, '$ho_ten', '$username', '$password', '$sdt', 0, 0)");
        
        $_SESSION['verified']['title'] = "Đăng ký thành công";
        $_SESSION['verified']['content'] = "Hãy đăng nhập để tiến hành đặt hàng";
        $_SESSION['verified']['link'] = "login.php";
        echo '<script>window.location.href="verified.php"</script>';
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
    <title>Đăng ký | Hoàng Kiên</title>
    <link rel="icon" href="./assets/img/icon.png">
</head>


<body style="background-image: linear-gradient(180deg, #c7d1ea, #a3b0d1)">

    <div class="account-pages mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card">

                        <div class="text-center account-logo-box">
                            <div class="mt-2 mb-2">
                                <span class="title">Đăng Ký</span>
                            </div>
                        </div>
                        <div class="card-body">

                            <form action="" method="POST" enctype="multipart/form-data">

                                <div class="row log-img">
                                    <div class="col-sm-12 text-center">
                                        <p class="text-muted"><img src="./assets/img/user.png" width="80px"></p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input class="form-control" value="<?php echo (isset($ho_ten)) ? $ho_ten : '' ?>" type="text" name="ho_ten" placeholder="Họ và tên" required>
                                    <span class="error"><?php echo (isset($error['ho_ten'])) ? $error['ho_ten'] : '' ?></span>
                                </div>

                                <div class="form-group">
                                    <input class="form-control" value="<?php echo (isset($username)) ? $username : '' ?>" type="text" name="username" placeholder="Tên đăng nhập" required>
                                    <span class="error"><?php echo (isset($error['username'])) ? $error['username'] : '' ?></span>
                                </div>

                                <div class="form-group">
                                    <input class="form-control" value="<?php echo (isset($sdt)) ? $sdt : '' ?>" type="sdt" name="sdt" placeholder="Số điện thoại" required>
                                    <span class="error"><?php echo (isset($error['sdt'])) ? $error['sdt'] : '' ?></span>
                                </div>

                                <div class="form-group">
                                    <input class="form-control" value="<?php echo (isset($password)) ? $password : '' ?>" type="password" name="password" placeholder="Mật khẩu" required>
                                    <span class="error"><?php echo (isset($error['password'])) ? $error['password'] : '' ?></span>
                                </div>

                                <div class="form-group">
                                    <input class="form-control" value="<?php echo (isset($confirmPass)) ? $confirmPass : '' ?>" type="password" name="confirm-password" placeholder="Nhập lại mật khẩu" required>
                                    <span class="error"><?php echo (isset($error['confirm-password'])) ? $error['confirm-password'] : '' ?></span>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-sm-12 text-right">
                                        <p class="text-muted">Bạn đã có tài khoản? <a href="login.php" class="text-primary ml-1"><b>Đăng nhập</b></a></p>
                                    </div>
                                </div>

                                <div class="form-group account-btn text-center mt-2">
                                    <div class="col-12">
                                        <button class="btn width-md btn-bordered btn-danger waves-effect waves-light" type="submit" name="submit">Đăng ký</button>
                                    </div>
                                </div>
                            </form>

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
    <script src="assets\js\vendor.min.js"></script>

    <!-- App js -->
    <script src="assets\js\app.min.js"></script>
    <script src="assets/libs/bootstrap-filestyle2/bootstrap-filestyle.min.js"></script>

</body>

</html>