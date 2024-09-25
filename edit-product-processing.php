<?php 

session_start();
require_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $id = strip_tags($_POST["id"]);
    $name = strip_tags($_POST["name"]);
    $description = strip_tags($_POST["description"]);
    $price = strip_tags($_POST["price"]);

    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $price = filter_var($price, FILTER_VALIDATE_FLOAT);

    $query = "UPDATE products SET title = :name, description = :description, price = :price WHERE id = :id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":description", $description);
    $stmt->bindParam(":price", $price);
    $stmt->execute();

    $stmt = null;
    $pdo = null;
    unset($_SESSION["products"]);
}
header("Location: products.php");
die();
