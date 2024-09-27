<?php

require_once 'common.php';
session_start();

$query = "SELECT * FROM products";
$stmt = $pdo->prepare($query);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = null;
$pdo = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        img {
            width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['admin_logged'])): ?>
        <?php include_once "language-switcher.php"; ?>
        
        <p> <?= translateLabels("Admin logged") ?> </p>
        <span> <?= translateLabels("Want to logout?")?> </span>
        <a href="logout.php"> <?= translateLabels("Logout") ?> </a>
        <br>
        
        <!-- display the products -->
        <table border="1" cellpadding="10">
            <tr>
                <th><?= translateLabels('Name') ?></th>
                <th><?= translateLabels('Price') ?></th>
                <th><?= translateLabels('Description') ?></th>
                <th><?= translateLabels('Image') ?></th>    
                <th><?= translateLabels('Edit') ?></th>   
                <th><?= translateLabels('Remove') ?></th> 
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['title']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td><img src="<?= "img/" . htmlspecialchars($product['image']) ?>"></td>
                    <td>
                        <form method="post" action="edit-product.php">
                            <input type="hidden" name="productId" value="<?= htmlspecialchars($product["id"]) ?>" >
                            <input type="submit" value="<?= translateLabels('Edit'); ?>" >
                        </form>
                    </td>
                    <td>
                        <form method="post" action="delete-product.php">
                            <input type="hidden" name="productId" value="<?= htmlspecialchars($product["id"]) ?>" >
                            <input type="submit" value="<?= translateLabels('Remove'); ?>" >
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <a href="product.php"><?= translateLabels("Add product") ?></a>
        <br><br>
        <a href="index.php"><?= translateLabels('Go to main page'); ?></a>
    <?php else: ?>
        <?php header("Location: index.php"); ?>
        <?php die(); ?>
    <?php endif; ?>
</body>
</html>
