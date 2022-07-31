<?php
include_once('./module/function.php');

session_start();

$adminImg = './assets/img/icon.png';
$userImg = './assets/img/user.png';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="./assets/css/header-footer.css">
    <title>Điện thoại chính hãng Hoàng Kiên</title>
    <link rel="icon" href="./assets/img/icon.png">
</head>

<body>

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