<?php
require_once 'head.php';

$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 10;

if (isset($_GET["timkiem"])) {
    $total_records = runSQL("SELECT count(hien_sp) as 'count' FROM sanpham WHERE ten_sp LIKE '%" . $_GET['timkiem'] . "%'")[0]['count'];
    $total_page = $total_records != 0 ? ceil($total_records / $limit) : 1;

    if ($current_page > $total_page) {
        $current_page = $total_page;
    } else if ($current_page < 1) {
        $current_page = 1;
    }

    $start = ($current_page - 1) * $limit;
    $curent_link = 'list-product.php?timkiem=' . $_GET['timkiem'];

    $listProduct = runSQL("SELECT * FROM sanpham WHERE ten_sp LIKE '%" . $_GET['timkiem'] . "%' ORDER BY id_sp DESC LIMIT " . $start . ", " . $limit);
    $status = "Kết quả tìm kiếm cho: <strong>" . $_GET["timkiem"] . "</strong> (" . $total_records . " sản phẩm)";
} else {
    $total_records = runSQL("SELECT count(hien_sp) as 'count' FROM sanpham")[0]['count'];
    $total_page = $total_records != 0 ? ceil($total_records / $limit) : 1;

    if ($current_page > $total_page) {
        $current_page = $total_page;
    } else if ($current_page < 1) {
        $current_page = 1;
    }

    $start = ($current_page - 1) * $limit;
    $curent_link = 'list-product.php?';

    $listProduct = runSQL("SELECT * FROM sanpham  ORDER BY id_sp DESC LIMIT " . $start . ", " . $limit);
    $status = "Danh sách sản phẩm (" . $total_records . " sản phẩm)";
}

?>

<style>
    .status-blue {
        display: block;
        color: white;
        background-color: #3030f2;
        border-radius: 10px;
        font-size: 16px;
        padding: 5px 10px;
    }

    .status-black {
        color: white;
        background-color: black;
        border-radius: 10px;
        font-size: 16px;
        padding: 5px 10px;
    }

    .search-bar input {
        height: 33px;
    }

    .search-bar button {
        padding: 0;
    }
</style>
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
                    <div class="card">
                        <div class="card-body">
                            <form class="search-bar input-group nav col-12 col-md-auto justify-content-center mb-md-0" action="list-product.php" method="GET">
                                <input type="text" name="timkiem" class="form-control" placeholder="Nhập tên điện thoại cần tìm kiếm..." aria-describedby="button-addon2" require>
                                <button class="btn btn-outline-light" type="submit" id="button-addon2"><i class="fas fa-search btn btn-danger"></i></button>
                            </form>
                            <br>

                            <h5 class="header-title"><?= $status ?></h5>

                            <div class="table-responsive">
                                <table class="table table-centered mb-0" id="btn-editable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Hình ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Giá</th>
                                            <th>Trạng thái</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($listProduct as $i => $sanpham) : ?>
                                            <tr>
                                                <td><?= $sanpham['id_sp'] ?></td>
                                                <td><img src="../assets/img/product/<?= $sanpham['anh_sp'] ?>" style="width: 100px;height:110px;object-fit:contain"></td>
                                                <td><a href="../product-detail.php?id=<?= $sanpham["id_sp"] ?>" target="_blank" rel="noopener noreferrer"><?= $sanpham['ten_sp'] ?></a></td>
                                                <td style="font-size: 19px; color:red;font-weight: bold;"><?= price_format($sanpham['gia_moi']) ?></td>
                                                <td><?php echo $sanpham['hien_sp'] == 1 ? '<span class="status-blue">Hiện</span>' : '<span class="status-black">ẩn</span>' ?></td>
                                                <td>
                                                    <a href="edit-product.php?id=<?= $sanpham['id_sp'] ?>"><button class="btn btn-icon waves-effect waves-light btn-warning"> <i class="fas fa-wrench"></i></button></a>
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
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <img src="https://www.google.com.vn/imgres?imgurl=https%3A%2F%2Fproduct.hstatic.net%2F1000300544%2Fproduct%2Fiphone-13-pink-select-2021_d3ad549275cd49f4aef49722410002e5.png&imgrefurl=https%3A%2F%2Fpgluxury.com%2Fproducts%2Fiphone-13-128gb-chinh-hang-vn-a&tbnid=4FtPLoL_CZUlHM&vet=12ahUKEwi25Neay5X0AhVGR5QKHVXNCrkQMygBegUIARDWAQ..i&docid=DjaK_jUfajba4M&w=940&h=1112&q=iphone&ved=2ahUKEwi25Neay5X0AhVGR5QKHVXNCrkQMygBegUIARDWAQ" alt="">
    </body>

    </html>