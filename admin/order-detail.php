<?php
require_once 'head.php';
$bill = runSQL("SELECT * FROM donhang WHERE id_dh=" . $_GET["id"]);

if ($bill == null) {
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

$bill = $bill[0];
$listSP = runSQL("SELECT chitietdonhang.don_gia, chitietdonhang.so_luong, chitietdonhang.id_sp, sanpham.ten_sp FROM chitietdonhang JOIN sanpham on chitietdonhang.id_sp = sanpham.id_sp WHERE id_dh=" . $_GET["id"]);

if (isset($_POST["status"])) {
    if ($_POST["status"] == 3) {
        runSQL("UPDATE donhang SET trang_thai = " . $_POST["status"] . ", ngay_hoan_thanh = now() WHERE id_dh=" . $_GET["id"]);
    }
    runSQL("UPDATE donhang SET trang_thai = " . $_POST["status"] . " WHERE id_dh=" . $_GET["id"]);
    echo '<script>window.location.href="./order-detail.php?id=' . $_GET["id"] . '"</script>';
}
?>


<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Quản lý đơn hàng</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="header-title">Thông tin đơn hàng</h5>
                            <hr>

                            <div class="table-responsive-md">
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
                                        <tr>
                                            <td>trạng thái:</td>
                                            <td>
                                                <?php
                                                if ($bill['trang_thai'] == 3) {
                                                    echo ' <span> Giao hàng thành công (' . $bill['ngay_hoan_thanh'] . ')</span>';
                                                } elseif ($bill['trang_thai'] == 4) {
                                                    echo '<span> Huỷ</span>';
                                                } else {
                                                    echo '
                                    <form method="POST">
                                        <select class="form-control" name="status" onchange="this.form.submit()">';
                                                    for ($i = $bill['trang_thai']; $i <= 4; $i++) {
                                                        echo '<option value="' . $i . '"' . '>';
                                                        switch ($i) {
                                                            case '0':
                                                                echo 'Chưa xử lý</option>';
                                                                break;
                                                            case '1':
                                                                echo 'đang chuẩn bị hàng</option>';
                                                                break;
                                                            case '2':
                                                                echo 'đang gửi hàng</option>';
                                                                break;
                                                            case '3':
                                                                echo 'Giao hàng thành công</option>';
                                                                break;
                                                            case '4':
                                                                echo 'Huỷ</option>';
                                                                break;
                                                        }
                                                    }
                                                    echo '</select>
                                        <noscript><input type="submit" value="submit"></noscript>
                                    </form>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->

                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="header-title">Danh sách sản phẩm</h5>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-centered mb-0" id="btn-editable">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Số lượng</th>
                                            <th>Giá</th>
                                            <th>Thành tiền</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $i = 0;
                                        foreach ($listSP as $x) : ++$i; ?>
                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><a href="../product-detail.php?id=<?= $x["id_sp"] ?>" target="_blank" rel="noopener noreferrer"><?= $x['ten_sp'] ?></a></td>
                                                <td><?= $x['so_luong'] ?></td>
                                                <td><?= price_format($x['don_gia']) ?></td>
                                                <td><?= price_format($x['don_gia'] * $x['so_luong']) ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- end .table-responsive-->

                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->

                </div>
                <!-- end col -->
            </div>
            <!-- end row -->

        </div>
        <!-- end container-fluid -->

    </div>
    <!-- end content -->

    <?php
    require 'foot.php';
    ?>