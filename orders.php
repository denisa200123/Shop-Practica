<?php
require 'common.php';
session_start();

$query = "SELECT o.id, o.creation_date, p.title, p.price 
          FROM ordersproducts op
          JOIN orders o ON op.orderId = o.id
          JOIN products p ON op.productId = p.id
          GROUP BY o.id, p.title;";

$stmt = $pdo->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$currentOrderId = null;

foreach ($orders as $order) {
    $total = 0;
    if ($currentOrderId !== $order['id']) {
        if ($currentOrderId !== null) {
            echo "<br><br>";
        }
        echo "Order Id: " . $order["id"] . "<br>";
        echo "Creation date: " . $order["creation_date"] . "<br>Products: ";
        $currentOrderId = $order['id'];
    }
    echo $order['title'] . ", ";
}

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

    <?php if (isset($_SESSION["admin_logged"])): ?>

    <?php else: ?>
        <?php header("Location: index.php"); ?>
        <?php die(); ?>
    <?php endif; ?>
</body>
</html>
