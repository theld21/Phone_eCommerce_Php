<?php
    session_start();
    session_destroy();
    echo '<script>window.location.href="verified.php"</script>';
?>