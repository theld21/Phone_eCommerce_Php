<?php
require_once 'head.php';

if (isset($_GET["createReport"])) {
    if ($_GET["fromDate"] == null) {
        $error = "Bạn chưa chọn ngày bắt đầu";
    } elseif ($_GET["toDate"] == null) {
        $error = "Bạn chưa chọn ngày kết thúc";
    } else {
        $listDate = [];
        $beginDate = strtotime($_GET["fromDate"]);
        $enddate = strtotime($_GET["toDate"]);
        if ($beginDate > $enddate) {
            $error = "Ngày bắt đầu phải nhỏ hơn ngày kết thúc";
        } else {
            // Tạo ra một mảng đa chiều với tên các mảng con là ngày nằm trong khoảng đã chọn
            do {
                $listDate[date('Y-m-d', $beginDate)] = [];
                $beginDate += 86400;
            } while ($beginDate <= $enddate);


            $count_newOrder = 0;
            // Lấy số lượng đơn hàng mới theo ngày vào listDate
            $newOrder = runSQL("SELECT ngay_tao, COUNT(ngay_tao) as 'count' FROM `donhang`  WHERE ngay_tao>='" . $_GET["fromDate"] . "' AND ngay_tao<='" . $_GET["toDate"] . "' GROUP BY ngay_tao;");
            foreach ($newOrder as $i => $v) {
                $listDate[$v['ngay_tao']]['newOrder'] = $v['count'];
                $count_newOrder++;
            }

            $count_successOrder = 0;
            $tong_thu_nhap = 0;
            // Lấy số lượng đơn hàng thành công và tổng thu nhập theo ngày vào listDate
            $susscessOrder = runSQL("SELECT donhang.ngay_hoan_thanh, COUNT(donhang.ngay_hoan_thanh) as 'count', SUM(donhang.tong_tien) AS tong_tien FROM donhang JOIN chitietdonhang ON donhang.id_dh = chitietdonhang.id_dh WHERE donhang.ngay_hoan_thanh IS NOT NULL AND ngay_tao>='" . $_GET["fromDate"] . "' AND ngay_tao<='" . $_GET["toDate"] . "' GROUP BY donhang.ngay_hoan_thanh");
            foreach ($susscessOrder as $i => $v) {
                $listDate[$v['ngay_hoan_thanh']]['successOrder'] = $v['count'];
                $listDate[$v['ngay_hoan_thanh']]['tong_tien'] = $v['tong_tien'];
                $tong_thu_nhap += $v['tong_tien'];
                $count_successOrder++;
            }
        }
    }
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
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <!-- col -->
                <div class="col-lg-3 col-xl-3">
                    <div class="card no-shadown widget-box-three" style="background-color: #17A2B8;">
                        <div class="card-body">
                            <div class="float-right mt-2">
                                <i class="fas fa-shopping-bag display-3 m-0" style="color: #148A9D;"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-uppercase font-weight-medium text-truncate mb-2">Đơn hàng chưa xử lý</p>
                                <h2 class="mb-0"><span data-plugin="counterup"><?php echo runSQL("SELECT count(id_dh) as 'count' FROM donhang WHERE trang_thai = 0")[0]['count']  ?></span></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- col -->
                <div class="col-lg-3 col-xl-3">
                    <div class="card no-shadown widget-box-three" style="background-color: #28A745;">
                        <div class="card-body">
                            <div class="float-right mt-2">
                                <i class="fas fa-check-double display-3 m-0" style="color: #228E3B;"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-uppercase font-weight-medium text-truncate mb-2">Đơn hàng thành công</p>
                                <h2 class="mb-0"><span data-plugin="counterup"><?php echo runSQL("SELECT count(id_dh) as 'count' FROM donhang WHERE trang_thai = 3")[0]['count']  ?></span></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- col -->
                <div class="col-lg-3 col-xl-3">
                    <div class="card no-shadown widget-box-three" style="background-color: #FFC107;">
                        <div class="card-body">
                            <div class="float-right mt-2">
                                <i class="fas fa-mobile-alt display-3 m-0" style="color: #D9A406;"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-uppercase font-weight-medium text-truncate mb-2">Số lượng sp đã bán</p>
                                <h2 class="mb-0"><span data-plugin="counterup"><?php echo runSQL("SELECT sum(chitietdonhang.so_luong) as 'sum' FROM chitietdonhang join donhang ON chitietdonhang.id_dh = donhang.id_dh WHERE donhang.trang_thai = 3")[0]['sum']  ?></span></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- col -->
                <div class="col-lg-3 col-xl-3">
                    <div class="card no-shadown widget-box-three" style="background-color: #DC3545;">
                        <div class="card-body">
                            <div class="float-right mt-2">
                                <i class="fas fa-dollar-sign display-3 m-0" style="color: #BB2D3B;"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-uppercase font-weight-medium text-truncate mb-2">Tổng thu nhập</p>
                                <h2 class="mb-0"><span data-plugin="counterup"><?php echo runSQL("SELECT sum(tong_tien) as 'sum' FROM donhang WHERE trang_thai = 3")[0]['sum']  ?></span></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <h5>Thống kê doanh thu</h5>
                        <div id="datatable-fixed-header_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <form method="GET" class="selectDate my-3">
                                <p style="color: red;"><?php echo (isset($error)) ? $error : '' ?></p>
                                <span style="color: black;">Ngày bắt đầu</span>
                                <input type="date" id="fromDate" name="fromDate" <?php echo isset($_GET['fromDate']) ? 'value="' . $_GET['fromDate'] . '"' : "" ?> onchange="setLimitDate()" max="<?= date("Y-m-d") ?>">
                                <span style="color: black; margin-left: 15px;">Ngày kết thúc</span>
                                <input type="date" id="toDate" name="toDate" <?php echo isset($_GET['toDate']) ? 'value="' . $_GET['toDate'] . '"' : "" ?> onchange="setLimitDate()" max="<?= date("Y-m-d") ?>">
                                <button class="btn btn-danger mx-3" type="submit" name="createReport">Tạo thống kê</button>
                            </form>
                            <br>

                            <?php if (isset($listDate)) : ?>
                                <style>
                                    .table-bordered td,
                                    .table-bordered th {
                                        border: 1px solid grey;
                                    }

                                    tbody tr:nth-child(even) {
                                        background: #c3c3db;
                                    }
                                </style>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="datatable-fixed-header" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline" style="border-collapse: collapse; border-spacing: 0px; width: 100%;" role="grid" aria-describedby="datatable-fixed-header_info">
                                            <thead>
                                                <tr style="background-color: #eaa459;">
                                                    <th tabindex="0" aria-controls="datatable-fixed-header" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending">Ngày</th>
                                                    <th tabindex="0" aria-controls="datatable-fixed-header" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending">Đơn hàng mới</th>
                                                    <th tabindex="0" aria-controls="datatable-fixed-header" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending">Đơn hàng thành công</th>
                                                    <th class="sorting text-right" tabindex="0" aria-controls="datatable-fixed-header" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending">Thu nhập</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($listDate as $date => $data) : ?>
                                                    <tr>
                                                        <td><?= $date ?></td>
                                                        <td><?php echo isset($data['newOrder']) ? $data['newOrder'] : "0" ?></td>
                                                        <td><?php echo isset($data['successOrder']) ? $data['successOrder'] : "0" ?></td>
                                                        <td class="text-right"><?php echo isset($data['tong_tien']) ? price_format($data['tong_tien']) : "0đ" ?></td>
                                                    </tr>
                                                <?php endforeach ?>
                                                <tr class="odd" style="background-color: #eaa459;">
                                                    <td><strong>Tổng: (<?php echo count($listDate) ?> ngày)</strong></td>
                                                    <td><strong><?= $count_newOrder ?></strong></td>
                                                    <td><strong><?= $count_successOrder ?></strong></td>
                                                    <td class="text-right"><strong><?= price_format($tong_thu_nhap) ?></strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif ?>

                        </div>
                    </div>
                </div>
            </div>



        </div>
        <!-- end container-fluid -->

    </div>
    <!-- end content -->
    <script>
        function setLimitDate() {
            document.querySelector('#toDate').min = document.querySelector('#fromDate').value;
            document.querySelector('#fromDate').max = document.querySelector('#toDate').value;
        }
    </script>
    <?php require_once 'foot.php'; ?>
    </body>

    </html>