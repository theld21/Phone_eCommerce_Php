<?php
require_once 'head.php';

$curent_link = 'order-management.php?';
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 20;


if (isset($_GET['filter'])) {
    if ($_GET['fromDate'] != null || $_GET['toDate'] != null || $_GET['trangThai'] != null) {
        $where = " WHERE";
        $count = 0;
        if ($_GET['fromDate'] != null) {
            $count++;
            $curent_link = $curent_link = $curent_link . '&fromDate=' . $_GET['fromDate'];
            $where = $where . " ngay_tao>='" . $_GET["fromDate"] . "'";
        }
        if ($_GET['toDate'] != null) {
            if ($count > 0) $sql = $sql . " AND";
            $count++;
            $curent_link = $curent_link = $curent_link . '&toDate=' . $_GET['toDate'];
            $where = $where . " ngay_tao<='" . $_GET["toDate"] . "'";
        }
        if ($_GET['trangThai'] != null) {
            if ($count > 0) $sql = $sql . " AND";
            $count++;
            $curent_link = $curent_link = $curent_link . '&trangThai=' . $_GET['trangThai'];
            $where = $where . " trang_thai=" . $_GET["trangThai"];
        }

        $total_records = runSQL("SELECT count(id_dh) as 'count' FROM donhang " . $where)[0]['count'];
        $total_page = $total_records != 0 ? ceil($total_records / $limit) : 1;
        if ($current_page > $total_page) {
            $current_page = $total_page;
        } else if ($current_page < 1) {
            $current_page = 1;
        }
        $start = ($current_page - 1) * $limit;

        $listBill = runSQL("SELECT * FROM donhang" . $where . " ORDER BY donhang.id_dh DESC LIMIT " . $start . ", " . $limit);
    } else {
        echo '<script>window.location.href="order-management.php"</script>';
    }
} else {
    $total_records = runSQL("SELECT count(id_dh) as 'count' FROM donhang ")[0]['count'];
    $total_page = $total_records != 0 ? ceil($total_records / $limit) : 1;
    if ($current_page > $total_page) {
        $current_page = $total_page;
    } else if ($current_page < 1) {
        $current_page = 1;
    }
    $start = ($current_page - 1) * $limit;
    $listBill = runSQL("SELECT * FROM donhang ORDER BY donhang.id_dh DESC LIMIT " . $start . ", " . $limit);
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

                            <h5 class="header-title">Danh sách đơn hàng</h5>

                            <div class="filter">
                                <form method="GET" class="selectDate my-3">
                                    <span style="color: black;">Ngày bắt đầu</span>
                                    <input type="date" id="fromDate" name="fromDate" <?php echo isset($_GET['fromDate']) ? 'value="' . $_GET['fromDate'] . '"' : "" ?> onchange="setLimitDate()" max="<?= date("Y-m-d") ?>">
                                    <span style="color: black; margin-left: 15px;">Ngày kết thúc</span>
                                    <input type="date" id="toDate" name="toDate" <?php echo isset($_GET['toDate']) ? 'value="' . $_GET['toDate'] . '"' : "" ?> onchange="setLimitDate()" max="<?= date("Y-m-d") ?>">

                                    <select name="trangThai" class="select-status">
                                        <option value="">Tất cả</option>
                                        <option value="0" <?php echo (isset($_GET['trangThai']) && $_GET['trangThai'] == 0) ? 'selected' : '' ?>>Chưa xử lý</option>
                                        <option value="1" <?php echo (isset($_GET['trangThai']) && $_GET['trangThai'] == 1) ? 'selected' : '' ?>>Đang chuẩn bị hàng</option>
                                        <option value="2" <?php echo (isset($_GET['trangThai']) && $_GET['trangThai'] == 2) ? 'selected' : '' ?>>Đang gửi hàng</option>
                                        <option value="3" <?php echo (isset($_GET['trangThai']) && $_GET['trangThai'] == 3) ? 'selected' : '' ?>>Giao hàng thành công</option>
                                        <option value="4" <?php echo (isset($_GET['trangThai']) && $_GET['trangThai'] == 4) ? 'selected' : '' ?>>Đã huỷ</option>
                                    </select>

                                    <button class="btn btn-danger mx-3" type="submit" name="filter">Lọc</button>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-centered mb-0" id="btn-editable">
                                    <thead>
                                        <tr>
                                            <th>Mã đơn</th>
                                            <th>Ngày tạo đơn</th>
                                            <th>Khách hàng</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($listBill as $bill) : ?>
                                            <tr>
                                                <td><?= $bill['id_dh'] ?></td>
                                                <td><?= $bill['ngay_tao'] ?></td>
                                                <td><?= $bill['ho_ten'] ?></td>
                                                <td><?= price_format($bill['tong_tien']) ?></td>
                                                <td>
                                                    <?php
                                                    switch ($bill["trang_thai"]) {
                                                        case '0':
                                                            echo '<span class="status-red">Chưa xử lý</span>';
                                                            break;
                                                        case '1':
                                                            echo '<span class="status-yellow">đang chuẩn bị hàng</span>';
                                                            break;
                                                        case '2':
                                                            echo '<span class="status-blue">đang gửi hàng</span>';
                                                            break;
                                                        case '3':
                                                            echo '<span class="status-green">Giao hàng thành công</span>';
                                                            break;
                                                        case '4':
                                                            echo '<span class="status-black">Huỷ</span>';
                                                            break;
                                                    }
                                                    ?>
                                                </td>

                                                <td>
                                                    <a href="order-detail.php?id=<?= $bill['id_dh'] ?>"><button class="btn btn-icon waves-effect waves-light btn-warning"> <i class="fas fa-wrench"></i></button></a>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- end .table-responsive-->

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

    <script>
        function setLimitDate() {
            document.querySelector('#toDate').min = document.querySelector('#fromDate').value;
            document.querySelector('#fromDate').max = document.querySelector('#toDate').value;
        }
    </script>

    <?php
    require 'foot.php';
    ?>