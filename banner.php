<?php
$sql = "SELECT * FROM banner";
$result = $db->prepare($sql);
$result->execute();
$banners = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Đặt trong container - body -->
<!-- Banner -->
<div class="safe-area banner">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">

            <?php foreach ($banners as $i=>$banner) : ?>
                <div class="carousel-item <?php if ($i==1) echo "active"; ?>">
                    <a href="<?= $banner['url'] ?>">
                        <img src="./assets/img/banner/<?= $banner['img_banner'] ?>" class="d-block w-100">
                    </a>
                </div>
            <?php endforeach ?>

        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
<!-- end Banner -->