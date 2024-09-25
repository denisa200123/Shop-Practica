<?php

session_start();
require_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["productToDelete"])) {
    $query = "DELETE FROM products WHERE id = :id;";
    $stmt = $pdo->prepare(query: $query);
    $stmt->bindParam(":id", $_POST["productToDelete"]);
    $stmt->execute();

    $stmt = null;
    $pdo = null;
    unset($_SESSION["products"]);
}
header("Location: products.php");
die();
