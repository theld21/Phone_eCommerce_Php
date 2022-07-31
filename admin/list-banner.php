<?php
require_once 'head.php';

if (isset($_POST['upload-banner'])) {
    $error = [];
    $image_info = getimagesize($_FILES['myFile']['tmp_name']);

    if ($image_info[0] == 1200 && $image_info[1] == 300) {
        $fileName = $_FILES['myFile']['name'];
        $file = $_FILES['myFile']['tmp_name'];
        $path = "../assets/img/banner/" . $_FILES['myFile']['name'];
        $url = $_POST['url'];

        if (move_uploaded_file($file, $path)) {
            try {
                $sql = "INSERT INTO banner VALUES (NULL, '$fileName', '$url')";
                $query = $db->prepare($sql);
                $query->execute();
            } catch (PDOException $e) {
                $error[] = $e->getMessage();
            }
        } else {
            $error['img_banner'] = 'Vui lòng tải ảnh';
        }

        if (count($error) == 0) {
            $status = 'success';
            echo '<script>alert("Thêm banner thành công")</script>';
        } else {
            echo '<script>alert("Thêm banner thất bại")</script>';
        }
    } else {
        $error['banner'] = 'Size banner không hợp lệ<br>';
    }
}
$listBanner = runSQL("SELECT * FROM banner");
?>


<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Quản lý Banner</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card-box">

                        <div class="row mt-4">
                            <div class="col-sm-12">
                                <h4 class="header-title">Thêm banner (1200px : 300px)</h4>

                                <form action="" method="POST" enctype="multipart/form-data" class="filestyle">
                                    <span class="error"><?php echo (isset($error['banner'])) ? $error['banner'] : '' ?></span>
                                    <input type="file" accept=".png, .jpg, .jpeg, .jfif" name="myFile">
                                    <input class="form-control my-2" value="<?php echo (isset($url)) ? $url : '' ?>" name="url" placeholder="URL khi click vào banner">
                                    <input type="submit" name="upload-banner" class="btn btn-success">
                                </form>

                            </div>
                        </div>

                    </div>
                    <!-- end card-box -->
                </div>
                <!-- end col-->

            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="header-title">Danh sách banner</h5>
                            <div class="table-responsive">
                                <table class="table table-centered mb-0" id="btn-editable">
                                    <thead>
                                        <tr>
                                            <th>Banner</th>
                                            <th>Url</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($listBanner as $banner) : ?>
                                            <tr>
                                                <td><img src="../assets/img/banner/<?= $banner['img_banner'] ?>" style="width: 100%;object-fit:contain"></td>
                                                <td><a href="<?= $banner['url'] ?>"><?= $banner['url'] ?></a></td>
                                                <td><a href="delete-banner.php?id=<?= $banner['id_banner'] ?>" onclick="return confirm('Bạn muốn xóa banner<?= $banner['img_banner'] ?>')"><button class="btn btn-icon waves-effect waves-light btn-danger"> <i class="fas fa-times"></i></button></a></td>
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