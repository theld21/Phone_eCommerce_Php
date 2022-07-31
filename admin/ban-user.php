<?php
    require_once '../module/function.php';
    $sql = "UPDATE user SET chan_user=1 WHERE id_user=".$_GET['id'];
    $query = $db->prepare($sql);
    $query->execute();

    $sql = "DELETE FROM binhluan WHERE id_user=".$_GET['id'];
    $query = $db->prepare($sql);
    $query->execute();

    echo '<script>window.location.href="list-binhluan.php"</script>';
?>