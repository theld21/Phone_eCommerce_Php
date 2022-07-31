<?php
require_once 'head.php';
require_once '../module/function.php';

$curent_link = 'list-binhluan.php?';
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 20;
$total_records = runSQL("SELECT count(id_bl) as 'count' FROM binhluan ")[0]['count'];
$total_page = $total_records != 0 ? ceil($total_records / $limit) : 1;

if ($current_page > $total_page) {
    $current_page = $total_page;
} else if ($current_page < 1) {
    $current_page = 1;
}

$start = ($current_page - 1) * $limit;

$sql = "SELECT * FROM binhluan LIMIT " . $start . ", " . $limit;
$result = $db->prepare($sql);
$result->execute();
$listBinhLuan = $result->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Quản lý bình luận</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="header-title">Bình luận</h5>
                            <div class="table-responsive">
                                <table class="table table-centered mb-0" id="btn-editable">
                                    <thead>
                                        <tr>
                                            <th>Bình luận</th>
                                            <th>Người dùng</th>
                                            <th>Link sản phẩm</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($listBinhLuan as $binhluan) : ?>
                                            <tr>
                                                <td><?= $binhluan['noi_dung'] ?></td>
                                                <td><a href="edit-user.php?id=<?=$binhluan['id_user']?>"><?php echo runSQL("SELECT ho_ten FROM user WHERE id_user=" . $binhluan['id_user'])[0]["ho_ten"] ?></a></td>
                                                <td><a href="../product-detail.php?id=<?= $binhluan["id_sp"] ?>" target="_blank" rel="noopener noreferrer"><?php echo runSQL("SELECT ten_sp FROM sanpham WHERE id_sp=" . $binhluan['id_sp'])[0]['ten_sp']  ?></a></td>
                                                <td>
                                                    <a href="delete-binhluan.php?id=<?= $binhluan['id_bl'] ?>" onclick="return confirm('Bạn có muốn xóa bình luận: <?= $binhluan['noi_dung'] ?> ?')"><button class="btn btn-icon waves-effect waves-light btn-danger"><i class="fas fa-times"></i></button></a>
                                                    <a href="ban-user.php?id=<?= $binhluan['id_user'] ?>" onclick="return confirm('Bạn có muốn chặn và xoá tất cả bình luận của người dùng: <?= $binhluan['id_user'] ?> ?')"><button class="btn btn-icon waves-effect waves-light btn-danger"><i class="fas fa-user-slash"></i></button></a>
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