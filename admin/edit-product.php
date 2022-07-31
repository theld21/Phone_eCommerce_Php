<?php
require_once 'head.php';

if (isset($_POST['update-product'])) {
    $status       = 'error';
    $error        = array();
    $date         = new DateTime();
    $id           = (int)$_POST['id'];
    $createdAt    = $date->format("Y-m-d H:i:s");
    $ten_sp         = ucwords(strtolower($_POST['ten_sp']));
    $gia_cu        = preg_replace("#\.|\,|-#", "", $_POST['gia_cu']);
    $gia_moi     = preg_replace("#\.|\,|-#", "", $_POST['gia_moi']);
    $mo_ta  = $_POST['mo_ta'];
    $cau_hinh  = $_POST['cau_hinh'];
    $hien_sp  = $_POST['hien_sp'];
    $danhmuc        = (int)$_POST['danhmuc'];
    if (strlen($ten_sp) < 5) {
        $error['ten_sp'] = 'Tên sản phẩm phải lớn hơn 5 kí tự';
    }
    if ($mo_ta == '') {
        $error['mo_ta'] = 'Mô tả sản phẩm không được để trống';
    }
    if ($cau_hinh == '') {
        $error['cau_hinh'] = 'Cấu hình không được để trống';
    }
    if ($danhmuc == 0) {
        $error['danhmuc'] = 'Vui lòng chọn danh mục';
    }
    if (count($error) == 0) {
        try {
            $sql = "UPDATE sanpham SET ten_sp = '$ten_sp', gia_cu = $gia_cu, gia_moi = $gia_moi, mo_ta = '$mo_ta', id_dm = $danhmuc , cau_hinh = '$cau_hinh', hien_sp = '$hien_sp' WHERE id_sp = $id";
            $query = $db->prepare($sql);
            $query->execute();
        } catch (PDOException $e) {
            $error[] = $e->getMessage();
        }
        if (isset($_FILES['anh_sp']) && $_FILES['anh_sp']['size'] > 0) {
            $fileName = $_FILES['anh_sp']['name'];
            $file = $_FILES['anh_sp']['tmp_name'];
            $path = "../assets/img/product/" . $_FILES['anh_sp']['name'];
            if (move_uploaded_file($file, $path)) {
                unlink("../assets/img/product/" . runSQL('SELECT anh_sp FROM sanpham WHERE id_sp=' . $_GET['id'])[0]['anh_sp']);
                try {
                    $sql = "UPDATE sanpham SET anh_sp = '$fileName' WHERE id_sp = $id";
                    $query = $db->prepare($sql);
                    $query->execute();
                } catch (PDOException $e) {
                    $error[] = $e->getMessage();
                }
            }
        }
    }
    if (count($error) == 0) {
        $status = 'success';
        echo '<script>alert("Cập nhật sản phẩm thành công");window.location.href="edit-product.php?id='.$_GET['id'].'"</script>';
    } else {
        echo '<script>alert("Cập nhật sản phẩm thất bại")</script>';
    }
}
$id = (int)$_GET['id'];
$sql = "SELECT * FROM sanpham WHERE id_sp = $id";
$query = $db->prepare($sql);
$query->execute();
$sanpham = $query->fetch(PDO::FETCH_ASSOC);
?>

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Quản lý sản phẩm</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card-box">

                        <div class="row mt-4">
                            <div class="col-sm-12">
                                <h4 class="header-title">Sửa sản phẩm</h4>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $id ?>">

                                    <a href="../product-detail.php?id=<?= $sanpham["id_sp"] ?>" target="_blank" rel="noopener noreferrer">
                                        <img src="../assets/img/product/<?= $sanpham['anh_sp'] ?>" style="width: 250px;display: block;margin-left: auto;margin-right: auto;">
                                    </a>

                                    <div class="form-group">
                                        <label>Tên sản phẩm:</label>
                                        <input type="text" value="<?php echo (isset($ten_sp)) ? $ten_sp : $sanpham['ten_sp'] ?>" class="form-control" name="ten_sp" required <?php echo (isset($status) && $status == 'success') ? 'disabled' : '' ?>>
                                        <span class="error"><?php echo isset($error['ten_sp']) ? $error['ten_sp'] : '' ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Giá cũ:</label>
                                        <input type="number" value="<?php echo (isset($gia_cu)) ? $gia_cu : $sanpham['gia_cu'] ?>" class="form-control" name="gia_cu" required <?php echo (isset($status) && $status == 'success') ? 'disabled' : '' ?>>
                                    </div>

                                    <div class="form-group">
                                        <label>Giá mới:</label>
                                        <input type="number" value="<?php echo (isset($gia_moi)) ? $gia_moi : $sanpham['gia_moi'] ?>" class="form-control" name="gia_moi" required <?php echo (isset($status) && $status == 'success') ? 'disabled' : '' ?>>
                                    </div>

                                    <div class="form-group">
                                        <label>Trạng thái sản phẩm:</label>
                                        <select class="form-control" name="hien_sp">
                                            <option value="0" <?php echo $sanpham['hien_sp'] == 0 ? 'selected' : '' ?>>Ẩn</option>
                                            <option value="1" <?php echo $sanpham['hien_sp'] == 1 ? 'selected' : '' ?>>Hiện</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Ảnh sản phẩm:</label>
                                        <input type="file" accept=".png, .jpg, .jpeg, .jfif" name="anh_sp" class="filestyle" data-buttonbefore="true" id="filestyle-6" tabindex="-1" style="display: none;" <?php echo (isset($status) && $status == 'success') ? 'disabled' : '' ?>>
                                        <span class="error"><?php echo isset($error['anh_sp']) ? $error['anh_sp'] : '' ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Danh mục:</label>
                                        <select class="form-control" name="danhmuc">
                                            <?php
                                            $listCat = runSQL("SELECT * FROM danhmuc");
                                            foreach ($listCat as $cat) :
                                            ?>
                                                <option value="<?php echo $cat['id_dm'] ?>" <?php echo $cat['id_dm'] == $sanpham['id_dm'] ? 'selected' : '' ?>><?php echo $cat['ten_dm'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <span class="error"><?php echo isset($error['danhmuc']) ? $error['danhmuc'] : '' ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Cấu hình:</label>
                                        <textarea class="form-control" name="cau_hinh" id="productcauhinh" <?php echo (isset($status) && $status == 'success') ? 'disabled' : '' ?>><?php echo isset($cau_hinh) ? $cau_hinh : $sanpham['cau_hinh'] ?></textarea>
                                        <span class="error"><?php echo isset($error['cau_hinh']) ? $error['cau_hinh'] : '' ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Mô tả:</label>
                                        <textarea class="form-control" name="mo_ta" id="productdescription" <?php echo (isset($status) && $status == 'success') ? 'disabled' : '' ?>><?php echo isset($mo_ta) ? $mo_ta : $sanpham['mo_ta'] ?></textarea>
                                        <span class="error"><?php echo isset($error['mo_ta']) ? $error['mo_ta'] : '' ?></span>
                                    </div>

                                    <div class="form-group">
                                        <input type="submit" name="update-product" class="btn btn-success" value="Cập nhật sản phẩm">
                                        <a href="list-product.php"><input type="button" class="btn btn-info" value="Trở về"></a>
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
    require_once 'foot.php';
    ?>
    <script>
        CKEDITOR.replace('productdescription');
        CKEDITOR.replace('productcauhinh');
    </script>
    </body>

    </html>