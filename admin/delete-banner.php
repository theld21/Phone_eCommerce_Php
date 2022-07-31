<?php
    require_once '../module/function.php';
    unlink("../assets/img/banner/" . runSQL('SELECT img_banner FROM banner WHERE id_banner=' . $_GET['id'])[0]['img_banner']);
    runSQL("DELETE FROM banner WHERE id_banner=".$_GET['id']);
    echo '<script>window.location.href="list-banner.php"</script>';
?>