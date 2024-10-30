<?php

session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    die();    
}

require_once 'common.php';

$productId = isset($_POST['productId']) ? strip_tags($_POST['productId']) : '';
// check if the id is an int
if (filter_var($productId, FILTER_VALIDATE_INT) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = "SELECT * FROM products WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $productId);
    $stmt->execute();

    $selectedProduct = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    header('Location: products.php');
    die();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Remove product') ?></title>
    <style>
        img {
            width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <?php if ($selectedProduct): ?>
        <h1><?= translateLabels('Are you sure you want to delete this item?') ?></h1>

        <form method="post" action="delete-product-processing.php">
            <input type="hidden" name="productId" value="<?= htmlspecialchars($productId) ?>">
            <input type="submit" value="<?= translateLabels('Yes') ?>">
        </form>
        <br>
        <table border="1" cellpadding="10">
            <tr>
                <th><?= translateLabels('Name') ?></th>
                <th><?= translateLabels('Price') ?></th>
                <th><?= translateLabels('Description') ?></th>
                <th><?= translateLabels('Image') ?></th>
            </tr>
            <tr>
                <td><?= htmlspecialchars($selectedProduct['title']) ?></td>
                <td><?= htmlspecialchars($selectedProduct['price']) ?></td>
                <td><?= htmlspecialchars($selectedProduct['description']) ?></td>
                <td><img src="img/<?= htmlspecialchars($selectedProduct['image']) ?>"></td>
            </tr>
        </table>

        <br>
    <?php endif; ?>
    <a href='products.php'><?= translateLabels('Products page') ?></a>
</body>
</html>
