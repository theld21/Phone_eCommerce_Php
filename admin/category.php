<?php
require_once 'head.php';
if (isset($_POST['submit'])) {
    $error      = array();
    $date       = new DateTime();
    $createdAt  = $date->format("Y-m-d H:i:s");
    $danhmuc   = $_POST['danhmuc'];
    $danhmuc   = ucwords(strtolower($danhmuc));
    if (strlen($danhmuc) <= 3) {
        $error['danhmuc'] = 'Tên danh mục phải lớn hơn 3 kí tự';
    } else {
        $sql = "SELECT * FROM danhmuc WHERE ten_dm = '$danhmuc'";
        $query = $db->prepare($sql);
        $query->execute();
        if ($query->rowCount() > 0) {
            $error['danhmuc'] = 'Danh mục đã tồn tại';
        }
    }
    if (count($error) == 0) {
        $sql = "INSERT INTO danhmuc VALUES(null, '$danhmuc')";
        $query = $db->prepare($sql);
        $query->execute();
        echo '<script>alert("Thêm danh mục thành công")</script>';
    } else {
        echo '<script>alert("Thêm danh mục thất bại")</script>';
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
                        <h4 class="page-title">Quản lý danh mục</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card-box">

                        <div class="row mt-4">
                            <div class="col-sm-12">
                                <h4 class="header-title">Thêm hãng sản xuất</h4>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label>Tên Hãng * :</label>
                                        <input type="text" class="form-control" value="<?php echo (isset($danhmuc)) ? $danhmuc : '' ?>" name="danhmuc" required>
                                        <span class="error"><?php echo isset($error['danhmuc']) ? $error['danhmuc'] : '' ?></span>
                                    </div>

                                    <div class="form-group">
                                        <input type="submit" name="submit" class="btn btn-success" value="Thêm">
                                    </div>

                                </form>
                            </div>
                        </div>

                    </div>
                    <!-- end card-box -->
                </div>
                <!-- end col-->

            </div>
            <?php
            $sql = "SELECT * FROM danhmuc";
            $result = $db->prepare($sql);
            $result->execute();
            $listCat = $result->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <!-- end row -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="header-title">Danh sách hãng điện thoại</h5>

                            <div class="table-responsive">
                                <table class="table table-centered mb-0" id="btn-editable">
                                    <thead>
                                        <tr>
                                            <th>Tên Hãng</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($listCat as $cat) : ?>
                                            <tr>
                                                <td><?= $cat['ten_dm'] ?></td>
                                                <td>
                                                    <a href="edit-cat.php?id=<?= $cat['id_dm'] ?>"><button class="btn btn-icon waves-effect waves-light btn-warning"> <i class="fas fa-wrench"></i></button></a>
                                                </td>
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
    require_once 'foot.php';
    ?>
    </body>

    </html>