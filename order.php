<?php

session_start();
require 'common.php';

$orderId = $_GET['orderId'] ?? '';

if (filter_var($orderId, FILTER_VALIDATE_INT)) {
    $query = "SELECT products.title, products.description, products.price, products.image
    FROM products
    INNER JOIN ordersproducts
    ON products.id = ordersproducts.product_id
    WHERE order_id = :orderId;";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':orderId', $orderId);
    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header('Location: orders.php');
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Order') ?></title>
    <style>
        img {
            width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['admin_logged'])): ?>
        <?php include_once 'language-switcher.php'; ?>
        
        <?php if ($products): ?>
            <h1><?= translateLabels('Order id') . ': ' . htmlspecialchars($orderId)?></h1>

            <table border="1" cellpadding="10">
                <tr>
                <th><?= translateLabels('Product name') ?></th>
                <th><?= translateLabels('Description') ?></th>
                <th><?= translateLabels('Price') ?></th>
                <th><?= translateLabels('Image') ?></th>
                </tr>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['title']) ?></td>
                        <td><?= htmlspecialchars($product['description']) ?></td>
                        <td><?= htmlspecialchars($product['price']) ?></td>
                        <td><img src="img/<?= htmlspecialchars($product['image']) ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </table>

        <?php else: ?>
            <h1><?= translateLabels('We did not find an order with this id')?></h1>
        <?php endif; ?>

        <a href="orders.php"><?= translateLabels('Go to orders page') ?></a>

    <?php else: ?>
        <?php header('Location: index.php'); ?>
        <?php die(); ?>
    <?php endif; ?>
</body>
</html>
