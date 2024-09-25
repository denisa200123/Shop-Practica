<?php 

session_start();
require_once 'common.php';

$id = $_POST["productId"];

if ($_SERVER['REQUEST_METHOD'] === "POST" && filter_var($id, FILTER_VALIDATE_INT)) {
    $name = strip_tags($_POST["name"]);
    $description = strip_tags($_POST["description"]);
    $price = strip_tags($_POST["price"]);

    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $price = filter_var($price, FILTER_VALIDATE_FLOAT);

    $userInput = [$name, $description, $price];

    $errors = [];

    if(isInputEmpty($userInput)){
        $errors["emptyInput"] = translateLabels( "Not all fields were filled!");
    }

    if(isPriceInvalid($price)){
        $errors["invalidPrice"] = translateLabels( "Price doesn't have a valid value!");
    }

    if($errors) {
        $_SESSION["editing_errors"] = $errors;
        header("Location: edit-product.php");
        die();
    } else {
        $name = htmlspecialchars_decode($name);
        $description = htmlspecialchars_decode($description);
        $price = htmlspecialchars_decode($price);

        $query = "UPDATE products SET title = :name, description = :description, price = :price WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":price", $price);
        $stmt->execute();

        $stmt = null;
        $pdo = null;
        unset($_SESSION["productId"]);
        unset($_SESSION["editing_input"]);
    }
}
header("Location: products.php");
die();
