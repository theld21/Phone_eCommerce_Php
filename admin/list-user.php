<?php
require_once 'head.php';

$curent_link = 'list-user.php?';
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 20;
$total_records = runSQL("SELECT count(id_user) as 'count' FROM user ")[0]['count'];
$total_page = $total_records != 0 ? ceil($total_records / $limit) : 1;

if ($current_page > $total_page) {
    $current_page = $total_page;
} else if ($current_page < 1) {
    $current_page = 1;
}

$start = ($current_page - 1) * $limit;

$sql = "SELECT * FROM user where chan_user=0 ORDER BY id_user DESC LIMIT " . $start . ", " . $limit;
$result = $db->prepare($sql);
$result->execute();
$listUser = $result->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Quản lý người dùng</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="header-title">Danh sách tài khoản</h5>

                            <div class="table-responsive">
                                <table class="table table-centered mb-0" id="btn-editable">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Họ và tên</th>
                                            <th>Số điện thoại</th>
                                            <th>Quyền</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($listUser as $user) : ?>
                                            <tr>
                                                <td><?= $user['username'] ?></td>
                                                <td><?= $user['ho_ten'] ?></td>
                                                <td><?= $user['sdt'] ?></td>
                                                <td>
                                                    <?php echo $user['phan_quyen'] == 1 ? "Quản trị" : "Người dùng"; ?>
                                                </td>
                                                <td>
                                                    <a href="edit-user.php?id=<?= $user['id_user'] ?>"><button class="btn btn-icon waves-effect waves-light btn-warning"> <i class="fas fa-wrench"></i></button></a>
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

    <?php
    require 'foot.php';
    ?>