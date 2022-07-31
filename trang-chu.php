<?php
include_once('./header.php');

$filters = [];
$status = '';
$where = " WHERE";
$curent_link = 'trang-chu.php?';
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 16;

//Nếu người dùng tìm kiếm
if (isset($_GET["timkiem"])) {
    $total_records = runSQL("SELECT count(hien_sp) as 'count' FROM sanpham WHERE hien_sp=1 AND ten_sp LIKE '%" . $_GET['timkiem'] . "%'")[0]['count'];
    $total_page = $total_records != 0 ? ceil($total_records / $limit) : 1;
    if ($current_page > $total_page) {
        $current_page = $total_page;
    } else if ($current_page < 1) {
        $current_page = 1;
    }
    $start = ($current_page - 1) * $limit;
    $curent_link = $curent_link = $curent_link . 'timkiem=' . $_GET['timkiem'];
    $listProduct = runSQL("SELECT * FROM sanpham WHERE hien_sp=1 AND ten_sp LIKE '%" . $_GET['timkiem'] . "%' ORDER BY id_sp DESC LIMIT " . $start . ", " . $limit);
    $status = "Kết quả tìm kiếm cho: <strong>" . $_GET["timkiem"] . "</strong> (" . $total_records . " sản phẩm)<hr>";

}
//nếu người dùng lọc sản phẩm
elseif (isset($_GET["dm"]) || isset($_GET["gia"])) {

    if (isset($_GET["dm"])) {
        $curent_link = $curent_link . 'dm=' . $_GET["dm"];
        $filters["dm"] = $_GET["dm"];
        $where = $where . " id_dm=" . $_GET["dm"];
    }

    if (isset($_GET["gia"])) {
        $curent_link = $curent_link . '&gia=' . $_GET["gia"];
        $filters["gia"] = $_GET["gia"];

        if (isset($_GET["dm"])) {
            $where = $where . " and";
        }
        switch ($_GET["gia"]) {
            case 'duoi2':
                $where = $where . " gia_moi<=2000000";
                break;
            case '2den6':
                $where = $where . " gia_moi>=2000000 and gia_moi<=6000000";
                break;
            case '6den10':
                $where = $where . " gia_moi>=6000000 and gia_moi<=10000000";
                break;
            case '10den16':
                $where = $where . " gia_moi>=10000000 and gia_moi<=16000000";
                break;
            case 'tren16':
                $where = $where . " gia_moi>=16000000";
                break;
        }
    }
    $total_records = runSQL("SELECT count(hien_sp) as 'count' FROM sanpham " . $where . ' and hien_sp=1')[0]['count'];
    $total_page = $total_records != 0 ? ceil($total_records / $limit) : 1;
    if ($current_page > $total_page) {
        $current_page = $total_page;
    } else if ($current_page < 1) {
        $current_page = 1;
    }
    $start = ($current_page - 1) * $limit;

    $listProduct = runSQL("SELECT * FROM sanpham " . $where . ' and hien_sp=1 ORDER BY id_sp DESC LIMIT ' . $start . ", " . $limit);
    $status = $total_records . " sản phẩm<hr>";

}
//Nếu không có bộ lọc
else {
    $total_records = runSQL("SELECT count(hien_sp) as 'count' FROM sanpham WHERE hien_sp=1")[0]['count'];
    $total_page = $total_records != 0 ? ceil($total_records / $limit) : 1;
    if ($current_page > $total_page) {
        $current_page = $total_page;
    } else if ($current_page < 1) {
        $current_page = 1;
    }
    $start = ($current_page - 1) * $limit;
    $curent_link = 'trang-chu.php?';
    $listProduct = runSQL('SELECT * FROM sanpham WHERE hien_sp=1 ORDER BY id_sp DESC LIMIT ' . $start . ", " . $limit);
}

//return link filter
function setFilter($type, $value)
{
    global $filters;
    $temp = $filters;
    $temp[$type] = $value;
    $link = "";
    foreach ($temp as $i => $v) {
        $link = $link . "&" . $i . "=" . $v;
    }
    return $link;
}

//Lấy danh sách danh mục
$listDM = runSQL("SELECT * FROM danhmuc");
?>


<link rel="stylesheet" href="./assets/css/trang-chu.css">

<!-- CONTENT -->
<div class="container">

    <?php include_once('./banner.php') ?>

    <!-- Filter -->
    <div class="safe-area filter d-flex flex-wrap">
        <div class="filter-btn">
            <span><i class="fas fa-filter"></i> Bộ lọc</span>
        </div>
        <!-- item -->
        <div class="dropdown">
            <button class="filter-item btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <span>
                    <?php
                    if (isset($_GET["dm"])) {
                        foreach ($listDM as $dm) :
                            if ($dm["id_dm"] == $_GET["dm"]) {
                                echo $dm["ten_dm"];
                                break;
                            }
                        endforeach;
                    } else {
                        echo "Hãng sản xuất";
                    }
                    ?>
                </span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <?php foreach ($listDM as $dm) : ?>
                    <li><a class="dropdown-item" href="trang-chu.php?<?= setFilter("dm", $dm['id_dm']) ?>"><?= $dm['ten_dm'] ?></a></li>
                <?php endforeach ?>
                <li>
                    <hr><a class="dropdown-item" href="trang-chu.php">Tất cả</a>
                </li>
            </ul>
        </div>
        <!-- item -->
        <div class="dropdown">
            <button class="filter-item btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <span>
                    <?php
                    if (isset($_GET["gia"])) {
                        switch ($_GET["gia"]) {
                            case 'duoi2':
                                echo "Dưới 2 triệu";
                                break;
                            case '2den6':
                                echo "Từ 2 đến 6 triệu";
                                break;
                            case '6den10':
                                echo "Từ 6 đến 10 triệu";
                                break;
                            case '10den16':
                                echo "Từ 10 đến 16 triệu";
                                break;
                            case 'tren16':
                                echo "Trên 16 triệu";
                                break;
                            default:
                                break;
                        }
                    } else {
                        echo "Mức giá";
                    }
                    ?>
                </span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="trang-chu.php?<?= setFilter("gia", "duoi2") ?>">Dưới 2 triệu</a></li>
                <li><a class="dropdown-item" href="trang-chu.php?<?= setFilter("gia", "2den6") ?>">Từ 2 đến 6 triệu</a></li>
                <li><a class="dropdown-item" href="trang-chu.php?<?= setFilter("gia", "6den10") ?>">Từ 6 đến 10 triệu</a></li>
                <li><a class="dropdown-item" href="trang-chu.php?<?= setFilter("gia", "10den16") ?>">Từ 10 đến 16 triệu</a></li>
                <li><a class="dropdown-item" href="trang-chu.php?<?= setFilter("gia", "tren16") ?>">Trên 16 triệu</a></li>
            </ul>
        </div>
        <!-- item -->
        <?php
        if (count($filters) > 0) {
            echo '<a href="trang-chu.php"><button type="button" class="btn btn-danger mx-2">Xoá bộ lọc</button></a>';
        }
        ?>
        <!-- end item -->
    </div>
    <!-- end Filter -->

    <!-- Product -->
    <div class="safe-area products">
        <?php echo $status!=''? $status : ''?>
        <div class="row">
            <?php foreach ($listProduct as $product) : ?>
                <div class="product-item col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <div class="product-img">
                        <a href="product-detail.php?id=<?= $product['id_sp'] ?>" class="justify-content-center">
                            <img src="./assets/img/product/<?= $product['anh_sp'] ?>">
                        </a>
                    </div>
                    <div class="product-name"><a href="product-detail.php?id=<?= $product['id_sp'] ?>"><?= $product['ten_sp'] ?></a></div>
                    <span class="old-price"><?= price_format($product["gia_cu"]) ?></span>
                    <div class="product-price"><?= price_format($product['gia_moi']) ?></div>
                    <div class="product-open-btn">
                        <a href="product-detail.php?id=<?= $product['id_sp'] ?>"><button type="button" class="btn btn-danger">MUA NGAY</button></a>
                    </div>
                </div>
            <?php endforeach ?>

        </div>
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
    <!-- end Product -->

</div>
<!-- END CONTENT -->

<?php include_once('./footer.php'); ?>