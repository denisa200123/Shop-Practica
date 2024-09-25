<?php

session_start();
require_once 'common.php';

$id = (int)(htmlspecialchars($_POST["productId"]));

if ($_SERVER['REQUEST_METHOD'] === "POST" && is_int($id)) {

    $query = "DELETE FROM products WHERE id = :id;";
    $stmt = $pdo->prepare(query: $query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    $stmt = null;
    $pdo = null;
    unset($_SESSION["products"]);
}
header("Location: products.php");
die();
