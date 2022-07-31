<?php
require_once 'head.php';
if (isset($_POST['submit'])) {
    $error      = array();
    $date       = new DateTime();
    $createdAt  = $date->format("Y-m-d H:i:s");
    $id         = $_POST['id'];
    $danhmuc   = $_POST['danhmuc'];
    $danhmuc   = ucwords(strtolower($danhmuc));
    if (strlen($danhmuc) <= 0) {
        $error['danhmuc'] = 'Vui lòng nhập tên danh mục';
    } else {
        $sql = "SELECT * FROM danhmuc WHERE ten_dm = '$danhmuc' AND id_dm <> $id";
        $query = $db->prepare($sql);
        $query->execute();
        if ($query->rowCount() > 0) {
            $error['danhmuc'] = 'Danh mục đã tồn tại';
        }
    }
    if (count($error) == 0) {
        $sql = "UPDATE danhmuc SET ten_dm = '$danhmuc' WHERE id_dm = $id";
        $query = $db->prepare($sql);
        $query->execute();
        echo '<script>alert("Cập nhật danh mục thành công");window.location.href="category.php"</script>';
    } else {
        echo '<script>alert("Cập nhật danh mục thất bại")</script>';
    }
}
$id = (int)$_GET['id'];
$sql = "SELECT * FROM danhmuc WHERE id_dm = $id";
$query = $db->prepare($sql);
$query->execute();
$danhmuc = $query->fetch(PDO::FETCH_ASSOC)['ten_dm'];
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
                                <h4 class="header-title">Sửa danh mục</h4>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label>Tên danh mục * :</label>
                                        <input type="text" class="form-control" value="<?php echo (isset($danhmuc)) ? $danhmuc : '' ?>" name="danhmuc" required>
                                        <span class="error"><?php echo isset($error['danhmuc']) ? $error['danhmuc'] : '' ?></span>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $id ?>">
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
        </div>
        <!-- end container-fluid -->

    </div>
    <!-- end content -->
    <?php
    require_once 'foot.php';
    ?>