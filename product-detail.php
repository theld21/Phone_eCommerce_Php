<?php
include_once('./header.php');

$sp = runSQL("SELECT * FROM sanpham WHERE id_sp=" . $_GET["id"]);

if ($sp == null) {
    echo ' 
     <div class="mx-auto py-2 bg-danger text-white text-center">Sản phẩm không tồn tại</div> 
     <div class="mx-auto my-3 text-center"><button class="btn btn-primary" onclick="goBack()"><i class="fas fa-arrow-left"></i> Trở lại trang trước</button></div>

    <script>
    function goBack() {
      window.history.back();
    }
    </script>
    ';
    die;
}
$sp = $sp[0];
$dm = runSQL("SELECT * FROM danhmuc WHERE id_dm=" . $sp["id_dm"]);
$listBL = runSQL("SELECT * FROM binhluan WHERE id_sp=" . $_GET["id"] . " ORDER BY id_bl DESC LIMIT 5");
$listProduct = runSQL("SELECT * FROM sanpham WHERE hien_sp=1 ORDER BY id_sp DESC LIMIT 4");


// comment
if (isset($_POST['submit'])) {
    if (isset($_SESSION["user"])) {
        if (runSQL("SELECT chan_user from user where id_user=" . $_SESSION['user']['id_user'])[0]["chan_user"] == 1) {
            echo '<script>window.location.href="logout.php"</script>';
        } else {
            $cmt = $_POST['cmt'];
            if (strlen($cmt) < 10) {
                $error = array();
                $error['comment'] = 'Bình luận phải lớn hơn 10 ký tự';
            } else {
                $idsp = $_GET["id"];
                $idusr = $_SESSION["user"]["id_user"];
                runSQL("INSERT INTO binhluan VALUES(null, '$idsp', '$idusr', '$cmt')");
                echo '<script>window.location.href="product-detail.php?id=' . $_GET["id"] . '#comment"</script>';
            }
        }
    } else {
        echo '<script>window.location.href="login.php"</script>';
    }
}
if (isset($_POST['addProduct'])) {
    if (isset($_SESSION["user"])) {
        if (runSQL("SELECT chan_user from user where id_user=" . $_SESSION['user']['id_user'])[0]["chan_user"] == 1) {
            echo '<script>window.location.href="logout.php"</script>';
        } else {
            if (!isset($_SESSION["cart"][$_GET["id"]])) {
                $_SESSION["cart"][$_GET["id"]] = 1;
            } else {
                ++$_SESSION["cart"][$_GET["id"]];
            }
            echo '<script>window.location.href="gio-hang.php"</script>';
        }
    } else {
        echo '<script>window.location.href="login.php?idsp=' . $_GET["id"] .'</script>';
    }
}
?>


<link rel="stylesheet" href="./assets/css/product-detail.css">
<link rel="stylesheet" href="./assets/css/trang-chu.css">
<style>
    span.error {
        color: red;
        font-weight: normal;
    }
</style>

<!-- CONTENT -->
<div class="container">
    <!-- phone & detail -->
    <div class="safe-area">
        <div class="title-link d-flex justify-content-between">
            <div>
                <a href="trang-chu.php">Điện thoại</a><span> / </span><a href="trang-chu.php?dm=<?= $dm[0]["id_dm"] ?>"><?= $dm[0]["ten_dm"] ?></a>
            </div>
            <a href="#comment">Hỏi đáp</a>
        </div>
        <hr>
        <!-- phone -->
        <div class="product row my-5">
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                <div class="product-img">
                    <img src="./assets/img/product/<?= $sp["anh_sp"] ?>" width="100%">
                </div>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                <div>
                    <br>
                    <h1><?= $sp["ten_sp"] ?></h1>
                    <span class="new-price"><?= price_format($sp["gia_moi"]) ?></span>
                    <span class="old-price"><?= price_format($sp["gia_cu"]) ?></span>
                    <br>
                    <div class="product-detail-text">
                        <br>
                        <h4>Thông tin sản phẩm</h4>
                        <p>
                            <?= $sp["cau_hinh"] ?>
                        </p>
                    </div>
                    <?php
                    if ($sp['hien_sp'] == 1) {
                        if (isset($_SESSION['user']) && $_SESSION['user']['phan_quyen'] == 1) {
                            echo '<h3 class="btn btn-danger">Thêm vào giỏ hàng</h3>';
                        }
                        else
                            echo '<form method="POST">
                                    <button type="submit" name="addProduct" class="btn btn-danger">Thêm vào giỏ hàng</button>
                                </form>';
                    } else {
                        echo '<h3 class="btn btn-danger">Sản phẩm đã ngừng kinh doanh</h3>';
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>

    <!-- info -->
    <div class="d-flex flex-wrap">
        <!-- mô tả -->
        <div class="info col-lg-8 col-md-7 col-sm-12 col-xs-12">
            <div class="safe-area">
                <h4>Thông tin nổi bật</h4>
                <hr>
                <p><?= $sp['mo_ta'] ?></p>
            </div>
        </div>
        <!-- bình luận -->
        <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
            <div class="safe-area comment" id="comment">
                <h4>Bình luận</h4>
                <hr>
                <?php
                if (!isset($_SESSION['user'])) {
                    echo '<p>Hãy <a href="./login.php?idsp=' . $_GET["id"] . '">Đăng Nhập</a> để gửi bình luận</p><br>';
                } else {
                    echo '
                        <span class="error">';
                    echo (isset($error["comment"])) ? $error["comment"] : "";
                    echo '</span>
                        <form class="input-group mb-3" method="POST">
                            <input type="text" class="form-control" name="cmt" placeholder="Nhập bình luận" require>
                            <button class="btn btn-outline-secondary" type="submit" name="submit">Gửi</button>
                        </form>';
                }
                ?>



                <ul>
                    <?php foreach ($listBL as $bl) : ?>
                        <li>
                            <?php
                            if ($usr = runSQL("SELECT phan_quyen FROM user WHERE id_user=" . $bl['id_user'])[0]["phan_quyen"] == "1") {
                                echo '<img src="' . $adminImg . '" width="50px" height="50px">';
                                echo '<span><b style="background-color: #edca6a; margin: 2px 5px;"> Admin <i class="fas fa-crown"></i> </b> </span>';
                            } else {
                                echo '<img src="' . $userImg . '" width="50px" height="50px">';
                                echo '<span> ';
                                echo runSQL("SELECT ho_ten FROM user WHERE id_user=" . $bl['id_user'])[0]["ho_ten"];
                                if (isset($_SESSION["user"])) {
                                    if ($bl['id_user'] == $_SESSION["user"]['id_user']) echo " (bạn)";
                                }
                                echo '</span>';
                            }
                            ?>
                            <p><?= $bl['noi_dung'] ?></p>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- same-product -->
    <div class="safe-area products">
        <h4>Sản phẩm mới về</h4>
        <hr>
        <div class="row justify-content-center">
            <?php foreach ($listProduct as $product) : ?>
                <div class="product-item col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="product-img">
                        <a href="product-detail.php?id=<?= $product['id_sp'] ?>" class="justify-content-center"><img src="./assets/img/product/<?= $product['anh_sp'] ?>"></a>
                    </div>
                    <div class="product-name"><a href="product-detail.php?id=<?= $product['id_sp'] ?>"><?= $product['ten_sp'] ?></a></div>
                    <span class="old-price"><?= price_format($sp["gia_cu"]) ?></span>
                    <div class="product-price"><?= price_format($product['gia_moi']) ?></div>
                    <div class="product-open-btn">
                        <a href="product-detail.php?id=<?= $product['id_sp'] ?>"><button type="button" class="btn btn-danger">MUA NGAY</button></a>
                    </div>
                </div>
            <?php endforeach ?>

        </div>
    </div>
    <!-- end same-product -->

</div>
<!-- END CONTENT -->

<?php include_once('./footer.php'); ?>