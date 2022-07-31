<?php
require_once 'head.php';

if (isset($_POST['submit'])) {
    $error      = array();
    $date       = new DateTime();
    $id         = $_GET['id'];
    $createdAt  = $date->format("Y-m-d H:i:s");
    $chan_user = $_POST['chan_user'];

    if (count($error) == 0) {
        runSQL("UPDATE user SET chan_user = " . $chan_user . " WHERE id_user = " . $id);
        runSQL("DELETE FROM binhluan WHERE id_user = $id");
        echo '<script>alert("Cập nhật tài khoản thành công");window.location.href="list-user.php"</script>';
    } else {
        echo '<script>alert("Cập nhật tài khoản thất bại")</script>';
    }
}

$user = runSQL("SELECT * FROM user WHERE id_user=" . $_GET['id'])[0];
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
                    <div class="card-box">

                        <div class="row mt-4">
                            <div class="col-sm-12">
                                <h4 class="header-title">Cập nhật tài khoản</h4>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <div class="form-group">
                                        <label>Họ và tên:</label>
                                        <input class="form-control" value="<?php echo $user['ho_ten'] ?>" type="text" name="ho_ten" placeholder="Họ và tên" disabled>
                                    </div>

                                    <div class="form-group">
                                        <label>Tên đăng nhập:</label>
                                        <input class="form-control" value="<?php echo $user['username'] ?>" type="text" name="username" placeholder="Tên đăng nhập" disabled>
                                    </div>

                                    <div class="form-group">
                                        <label>Số điện thoại:</label>
                                        <input class="form-control" value="<?php echo $user['sdt'] ?>" type="sdt" name="sdt" placeholder="Số điện thoại" disabled>
                                        <span class="error"></span>
                                    </div>

                                    <?php if ($user["chan_user"] == 0) : ?>

                                        <div class="form-group">
                                            <label>Chặn người dùng:</label>
                                            <select class="form-control" name="chan_user">
                                                <option value="0" selected>Không</option>
                                                <option value="1">Khoá tài khoản</option>
                                            </select>
                                        </div>
                            </div>
                        <?php else : ?>
                            <p style="color: red;">Đã khoá tài khoản và xoá tất cả bình luận</p]>
                            <?php endif ?>

                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-success mx-3" value="Update">
                            </div>

                            </form>
                        </div>
                    </div>

                </div>
                <!-- end card-box -->
            </div>
            <!-- end col-->

        </div>
        <!-- end row -->

    </div>
    <!-- end container-fluid -->

</div>
<!-- end content -->
<?php
require 'foot.php';
?>