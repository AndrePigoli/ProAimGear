<?php

$db_host = 'localhost:3307';
$db_user = 'root';
$db_password = '';
$db_name = 'proaim_gear';


$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);


if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}


mysqli_set_charset($conn, "utf8");
?>