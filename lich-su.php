<?php
include_once('./header.php');

if (!isset($_SESSION["user"]))
    echo '<script>window.location.href="login.php"</script>';
elseif ($_SESSION["user"]['phan_quyen'] == 2)
    echo '<script>window.location.href="trang_chu.php"</script>';

$curent_link = 'lich-su.php?';
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 4;
$total_records = runSQL("SELECT count(id_dh) as 'count' FROM donhang ")[0]['count'];
$total_page = $total_records != 0 ? ceil($total_records / $limit) : 1;

if ($current_page > $total_page) {
    $current_page = $total_page;
} else if ($current_page < 1) {
    $current_page = 1;
}

$start = ($current_page - 1) * $limit;

$listBill = runSQL("SELECT * FROM donhang WHERE id_user=" . $_SESSION['user']['id_user'] . ' ORDER BY id_dh DESC LIMIT ' . $start . ", " . $limit);
if ($listBill == null) {
    echo '
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <div class="mx-auto py-2 bg-danger text-white text-center">Bạn chưa có đơn hàng nào</div> 
        <a href="./trang-chu.php"><div class="mx-auto my-3 text-center"><button class="btn btn-primary"><i class="fas fa-arrow-left"></i> Tiếp tục mua sắm</button></div></a> ';
    die;
}
?>

<link rel="stylesheet" href="./assets/css/lich-su.css">
<style>
    span.error {
        color: red;
    }
</style>

<!-- CONTENT -->
<div class="container cart-content">

    <h4 class="my-4">Lịch sử mua hàng</h4>

    <?php foreach ($listBill as $bill) : ?>
        <div class="safe-area my-4">
            <div class="row">
                <h5>Chi tiết đơn hàng</h5>
                <br>
                <!-- info -->
                <div class="table-responsive-md col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <table class="table-order-detail" width="100%">

                        <tbody>
                            <tr>
                                <td>Mã đơn hàng:</td>
                                <td><?= $bill['id_dh'] ?></td>
                            </tr>
                            <tr>
                                <td>Tên khách hàng:</td>
                                <td><?= $bill['ho_ten'] ?></td>
                            </tr>
                            <tr>
                                <td>Số điện thoại:</td>
                                <td><?= $bill['sdt'] ?></td>
                            </tr>
                            <tr>
                                <td>Địa chỉ:</td>
                                <td><?= $bill['dia_chi'] ?></td>
                            </tr>
                            <tr>
                                <td>Ghi chú:</td>
                                <td><?= $bill['ghi_chu'] ?></td>
                            </tr>
                            <tr>
                                <td>ngày đặt hàng:</td>
                                <td><?= $bill['ngay_tao'] ?></td>
                            </tr>
                            <tr>
                                <td>Tổng tiền:</td>
                                <td><?= price_format($bill['tong_tien']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- end info -->

                <!-- list-product -->
                <div class="list-product col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="table-responsive-md">
                        <table class="table table-centered" id="btn-editable">
                            <thead>
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Thành tiền</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $listSP = runSQL("SELECT chitietdonhang.don_gia, chitietdonhang.so_luong, chitietdonhang.id_sp, sanpham.ten_sp FROM chitietdonhang JOIN sanpham on chitietdonhang.id_sp = sanpham.id_sp WHERE id_dh=" . $bill['id_dh']);
                                foreach ($listSP as $x) : ?>
                                    <tr>
                                        <td><a style="text-decoration: none;" href="../product-detail.php?id=<?= $x["id_sp"] ?>" target="_blank" rel="noopener noreferrer"><?= $x['ten_sp'] ?></a></td>
                                        <td><?= $x['so_luong'] ?></td>
                                        <td><?= price_format($x['don_gia']) ?></td>
                                        <td><?= price_format($x['don_gia'] * $x['so_luong']) ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end list product -->

                <div>
                    <div style="background-color: #bbc1cc;" class="text-center col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php
                        switch ($bill['trang_thai']) {
                            case '0':
                                echo '<span>Chưa xác nhận</span>';
                                break;
                            case '1':
                                echo '<span>Đang chuẩn bị hàng</span>';
                                break;
                            case '2':
                                echo '<span>Đang gửi hàng</span>';
                                break;
                            case '3':
                                echo '<span>Giao hàng thành công</span>';
                                break;
                            case '4':
                                echo '<span>Đã huỷ</span>';
                                break;
                        }
                        ?>
                    </div>
                </div>


            </div>

        </div>
    <?php endforeach ?>

    <!-- Pagination -->
    <?php if ($total_page > 1) : ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($i == $current_page) {
                        echo '<li class="page-item"><a class="page-link border-secondary" style="background-color:grey;color:white">' . $i . '</a></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link border-secondary" href="' . $curent_link  . '&page=' . $i . '">' . $i . '</a></li>';
                    }
                }
                ?>
            </ul>
        </nav>
    <?php endif ?>
    <!-- end Pagination -->
</div>

</div><br><br><br>
<!-- END CONTENT -->


<?php include_once('./footer.php'); ?>