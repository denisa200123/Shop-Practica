<?php

session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    die();    
}

require_once 'common.php';

$id = isset($_POST['productId']) ? strip_tags($_POST['productId']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && filter_var($id, FILTER_VALIDATE_INT)) {

    $query = "DELETE FROM order_product WHERE product_id = :id;";
    $stmt = $pdo->prepare(query: $query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $query = "DELETE FROM products WHERE id = :id;";
    $stmt = $pdo->prepare(query: $query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $stmt = null;
    $pdo = null;
}
header('Location: products.php');
die();
