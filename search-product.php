<?php 

session_start();
require_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET["productToSearch"]) && !empty($_GET["productToSearch"])) {
    $productName = strip_tags($_GET["productToSearch"]);
    $productName = filter_var($productName, FILTER_SANITIZE_STRING);
    $productName = htmlspecialchars_decode($productName);
    $productName = "%" . $productName . "%";
    $productName = strtolower($productName);


    $query = "SELECT * FROM products WHERE lower(title) LIKE :productName";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":productName", $productName);
    $stmt->execute();

    $productsFound = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = null;
    $pdo = null;
} else {
    header("Location: products.php");
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search product</title>
    <style>
        img {
            width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <!-- display the found products -->
    <?php if ($productsFound): ?>
        <h1> <?= translateLabels("Products found"); ?></h1>
        <table border="1" cellpadding="10">
            <tr>
                <th><?= translateLabels('Name') ?></th>
                <th><?= translateLabels('Price') ?></th>
                <th><?= translateLabels('Description') ?></th>
                <th><?= translateLabels('Image') ?></th>    
            </tr>
            <?php foreach ($productsFound as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['title']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td><img src="<?= "img/" . htmlspecialchars($product['image']) ?>"></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <h1> <?= translateLabels("Sorry, we didn't find the product"); ?> </h1>
    <?php endif; ?>
    <br>
    <a href="products.php"><?= translateLabels('Products page'); ?></a>
</body>
</html>
