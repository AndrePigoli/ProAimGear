<?php
session_start();
include '../includes/db-connect.php';
include '../includes/functions.php';


if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}


if(!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$product_id = (int)$_GET['id'];


$query = "DELETE FROM products WHERE id = $product_id";
if(mysqli_query($conn, $query)) {
    header('Location: products.php?deleted=1');
} else {
    header('Location: products.php?error=1');
}
exit;
?>