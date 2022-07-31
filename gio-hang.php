<?php
include_once('./header.php');

if (!isset($_SESSION["user"]))
    echo '<script>window.location.href="login.php"</script>';
elseif ($_SESSION["user"]['phan_quyen']==2)
    echo '<script>window.location.href="trang-chu.php"</script>';


$sql = "SELECT * FROM sanpham WHERE ";
$i = false;
$countProduct = 0;

if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
    foreach ($_SESSION["cart"] as $id_sp => $sl) {
        if ($i) {
            $sql = $sql . " or";
        }
        $i = true;
        $countProduct += $sl;
        $sql = $sql . " id_sp=" . $id_sp;
    }

    $listProduct = runSQL($sql);
} else {
    echo '
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <div class="mx-auto py-2 bg-danger text-white text-center">Bạn chưa chọn sản phẩm nào</div> 
        <a href="./trang-chu.php"><div class="mx-auto my-3 text-center"><button class="btn btn-primary"><i class="fas fa-arrow-left"></i> Tiếp tục mua sắm</button></div></a> ';
    die;
}
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'del':
            unset($_SESSION["cart"][$_GET['id']]);
            break;
        case 'sum':
            $_SESSION["cart"][$_GET['id']]++;
            break;
        case 'sub':
            if ($_SESSION["cart"][$_GET['id']] > 1)
                $_SESSION["cart"][$_GET['id']]--;
            break;
    }
    echo '<script>window.location.href="gio-hang.php"</script>';
}

$tongTien = 0;
foreach ($listProduct as $product) {
    $tongTien = $tongTien + $product['gia_moi'] * $_SESSION['cart'][$product['id_sp']];
}

if (isset($_POST['createBill'])) {
    $diaChi = $_POST["dia_chi"];
    if (strlen($diaChi) < 10) {
        $error = array();
        $error['dia_chi'] = 'Hãy nhập đúng địa chỉ';
    } else {
        if (runSQL("SELECT chan_user from user where id_user=" . $_SESSION['user']['id_user'])[0]["chan_user"] == 1) {
            echo '<script>window.location.href="logout.php"</script>';
        } else {
            $idUsr = $_SESSION["user"]["id_user"];
            $hoTen = $_SESSION["user"]["ho_ten"];
            $sdt = $_SESSION["user"]["sdt"];
            $ghiChu = $_POST["ghi_chu"];

            runSQL("INSERT INTO donhang VALUES(null, '$idUsr', '$hoTen', '$diaChi', '$sdt', '$tongTien', '$ghiChu', 0, now(), null)");

            foreach ($_SESSION["cart"] as $id_sp => $soLuong) {
                $donGia = runSQL("SELECT * FROM sanpham WHERE id_sp=" . $id_sp)[0]['gia_moi'];
                $iddh = runSQL("SELECT MAX(id_dh) FROM donhang")[0]["MAX(id_dh)"];
                runSQL("INSERT INTO chitietdonhang VALUES(null, '$donGia', '$soLuong', '$iddh', '$id_sp')");
            }
            unset($_SESSION['cart']);
            $_SESSION['verified']['title'] = "Đặt hàng thành công";
            $_SESSION['verified']['content'] = "Chúng tôi sẽ liên hệ với bạn sớm nhất";
            $_SESSION['verified']['link'] = "trang-chu.php";
            echo '<script>window.location.href="verified.php"</script>';
        }
    }
}
?>

<link rel="stylesheet" href="./assets/css/gio-hang.css">
<style>
    span.error {
        color: red;
    }
</style>

<!-- CONTENT -->
<div class="container cart-content">
    <div class="row">
        <!-- cart -->
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="safe-area">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Sản phẩm</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Số tiền</th>
                            <th scope="col" class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>


                        <?php foreach ($listProduct as $product) : ?>
                            <tr>
                                <th scope="row">
                                    <img class="col" src="./assets/img/product/<?= $product['anh_sp'] ?>" width="50" height="50" alt="">
                                    <span class="col"><?= $product['ten_sp'] ?></span>
                                </th>
                                <td style="line-height: 50px" class="q-btn">
                                    <a href="gio-hang.php?action=sub&id=<?= $product['id_sp'] ?>"><i class="fas fa-minus-square"></i></a>
                                    <?= $_SESSION['cart'][$product['id_sp']] ?>
                                    <a href="gio-hang.php?action=sum&id=<?= $product['id_sp'] ?>"><i class="fas fa-plus-square"></i></a>
                                </td>
                                <td style="line-height: 50px"><?= price_format($product['gia_moi'] * $_SESSION['cart'][$product['id_sp']]) ?></td>
                                <td class="text-end"><a href="gio-hang.php?action=del&id=<?= $product['id_sp'] ?>"><button type="button" class="btn btn-danger">Xoá</button></a></td>
                            </tr>
                        <?php endforeach ?>
                        <tr>
                            <th style="line-height: 50px;">Tổng tiền:</th>
                            <td></td>
                            <td style="line-height: 50px;"><strong><?= price_format($tongTien) ?></strong></td>
                            <td></td>
                        </tr>

                    </tbody>
                </table>

            </div>
        </div>
        <!-- info -->
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <form class="safe-area" method="POST">
                <h4>Thông tin khách hàng</h4>
                <p><strong>Tên khách hàng: </strong><?= $_SESSION["user"]["ho_ten"] ?></p>
                <p><strong>Số điện thoại: </strong><?= $_SESSION["user"]["sdt"] ?></p>
                <span class="error"><?php echo (isset($error['dia_chi'])) ? $error['dia_chi'] : '' ?></span>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Địa chỉ</span>
                    <input type="text" name="dia_chi" class="form-control" placeholder="Nhập địa chỉ giao hàng">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Ghi chú</span>
                    <input type="text" name="ghi_chu" class="form-control">
                </div>
                <br>
                <hr>
                <p class="text-end">Tổng tiền (<?= $countProduct ?> sản phẩm): <strong><?= price_format($tongTien) ?></strong> <br>
                    <button type="submit" name="createBill" class="btn btn-danger">Đặt hàng</button>
                </p>
            </form>
        </div>
    </div>

</div><br><br><br>
<!-- END CONTENT -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>
