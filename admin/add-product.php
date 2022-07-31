<?php
require_once 'head.php';
if (isset($_POST['add-product'])) {
    $status       = 'error';
    $error        = array();
    $date         = new DateTime();
    $createdAt    = $date->format("Y-m-d H:i:s");
    $ten_sp         = ucwords(strtolower($_POST['ten_sp']));
    $gia_cu        = preg_replace("#\.|\,|-#", "", $_POST['gia_cu']);
    $gia_moi     = preg_replace("#\.|\,|-#", "", $_POST['gia_moi']);
    $cau_hinh  = $_POST['cau_hinh'];
    $mo_ta  = $_POST['mo_ta'];
    $danhmuc        = (int)$_POST['danhmuc'];
    if (strlen($ten_sp) < 5) {
        $error['ten_sp'] = 'Tên sản phẩm phải lớn hơn 5 kí tự';
    }
    if ($cau_hinh == '') {
        $error['mo_ta'] = 'Mô tả sản phẩm không được để trống';
    }
    if ($cau_hinh == '') {
        $error['mo_ta'] = 'Mô tả sản phẩm không được để trống';
    }
    if ($_FILES['anh_sp']['size'] == 0) {
        $error['anh_sp'] = 'Vui lòng tải lên ảnh sản phẩm';
    }
    if ($danhmuc == 0) {
        $error['danhmuc'] = 'Vui lòng chọn danh mục';
    }
    if (count($error) == 0) {
        $fileName = $_FILES['anh_sp']['name'];
        $file = $_FILES['anh_sp']['tmp_name'];
        $path = "../assets/img/product/" . $_FILES['anh_sp']['name'];
        move_uploaded_file($file, $path);
        try {
            $sql = "INSERT INTO sanpham VALUES (null, '$ten_sp', $gia_cu, $gia_moi, '$mo_ta', '$fileName', '$danhmuc', '$cau_hinh', 1)";
            $query = $db->prepare($sql);
            $query->execute();
        } catch (PDOException $e) {
            $error[] = $e->getMessage();
        }
    }
    if (count($error) == 0) {
        $status = 'success';
        echo '<script>alert("Thêm sản phẩm thành công")</script>';
    } else {
        echo '<script>alert("Thêm sản phẩm thất bại")</script>';
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
                                <h4 class="header-title">Thêm sản phẩm</h4>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>Tên sản phẩm:</label>
                                        <input type="text" value="<?php echo (isset($ten_sp)) ? $ten_sp : '' ?>" class="form-control" name="ten_sp" required <?php echo (isset($status) && $status == 'success') ? 'disabled' : '' ?>>
                                        <span class="error"><?php echo isset($error['ten_sp']) ? $error['ten_sp'] : '' ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Giá cũ:</label>
                                        <input type="number" min="0" value="<?php echo (isset($gia_cu)) ? $gia_cu : '' ?>" class="form-control" name="gia_cu" required <?php echo (isset($status) && $status == 'success') ? 'disabled' : '' ?>>
                                    </div>

                                    <div class="form-group">
                                        <label>Giá mới:</label>
                                        <input type="number" min="0" value="<?php echo (isset($gia_moi)) ? $gia_moi : '' ?>" class="form-control" name="gia_moi" required <?php echo (isset($status) && $status == 'success') ? 'disabled' : '' ?>>
                                    </div>

                                    <div class="form-group">
                                        <label>Danh mục:</label>
                                        <select class="form-control" name="danhmuc">
                                            <option value="0">Chọn danh mục</option>
                                            <?php
                                            $sql = "SELECT * FROM danhmuc";
                                            $query = $db->prepare($sql);
                                            $query->execute();
                                            $listCat = $query->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($listCat as $cat) :
                                            ?>
                                                <option value="<?php echo $cat['id_dm'] ?>"><?php echo $cat['ten_dm'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <span class="error"><?php echo isset($error['danhmuc']) ? $error['danhmuc'] : '' ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Cấu hình:</label>
                                        <textarea class="form-control" name="cau_hinh" id="productdetail" <?php echo (isset($status) && $status == 'success') ? 'disabled' : '' ?>><?php echo isset($description) ? $description : '' ?></textarea>
                                        <span class="error"><?php echo isset($error['cau_hinh']) ? $error['mo_ta'] : '' ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Mô tả:</label>
                                        <textarea class="form-control" name="mo_ta" id="productdescription" <?php echo (isset($status) && $status == 'success') ? 'disabled' : '' ?>><?php echo isset($description) ? $description : '' ?></textarea>
                                        <span class="error"><?php echo isset($error['mo_ta']) ? $error['mo_ta'] : '' ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Ảnh sản phẩm:</label>
                                        <input type="file" accept=".png, .jpg, .jpeg, .jfif" name="anh_sp" class="filestyle" data-buttonbefore="true" id="filestyle-6" tabindex="-1" style="display: none;" <?php echo (isset($status) && $status == 'success') ? 'disabled' : '' ?>>
                                        <span class="error"><?php echo isset($error['anh_sp']) ? $error['anh_sp'] : '' ?></span>
                                    </div>

                                    <div class="form-group">
                                        <input type="submit" name="add-product" class="btn btn-success" value="Thêm sản phẩm">
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
        CKEDITOR.replace('productdetail');
    </script>
    </body>

    </html>