<?php
    require_once '../module/db.php';
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM binhluan WHERE id_bl = $id";
    $query = $db->prepare($sql);
    $query->execute();
    echo '<script>window.location.href="list-binhluan.php"</script>';
?>