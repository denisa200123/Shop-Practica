<?php
require 'common.php';
session_start();

$query = "SELECT DISTINCT orderId FROM ordersproducts;";
$stmt = $pdo->prepare($query);
$stmt->execute();

$ordersId = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($ordersId as $id => $order) {
    echo "Order Id: " . $order["orderId"];
    $query = 'SELECT products.title
    FROM products 
    INNER JOIN ordersproducts 
    ON products.id = ordersproducts.productId
    WHERE orderId = :orderId';

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':orderId', $order["orderId"]);
    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<br>Products: ";
    foreach ($products as $product) {
        echo $product["title"] . " ";
    }
    echo "<br><br>";
}

$stmt = null;
$pdo = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
</head>
<body>
    
</body>
</html>
