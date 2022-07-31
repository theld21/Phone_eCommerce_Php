<?php
define('host', 'mysql:host=localhost;dbname=duan1;charset=utf8');
define('dbuser', 'root');
define('dbpass', '');
try {
    $db = new PDO(host, dbuser, dbpass);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
// Nhánh kết nối thất bại
catch (PDOException $e) {
    die('Kết nối thất bại: ' . $e->getMessage());
}

function runSQL($sql)
{
    global $db;
    $result = $db->prepare($sql);
    $result->execute();
    return $result->fetchAll(PDO::FETCH_ASSOC);
}
?>
