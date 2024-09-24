<?php 

session_start();
require_once 'common.php';

$selectedProduct = $_SESSION['products'][$_POST["productId"]-1];
$name = htmlspecialchars($selectedProduct["title"]);
$description = htmlspecialchars($selectedProduct["description"]);
$price = htmlspecialchars($selectedProduct["price"]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit product</title>
</head>
<body>
    <?php if ($_SERVER['REQUEST_METHOD'] === "POST"): ?>
        <form action= "edit-product-processing.php" method="POST">
            <label for="name"><?= translateLabels('Name'); ?></label>
            <input type="text" name="name" id="name" value = "<?= $name ?>">
            <br>
            <label for="description"><?= translateLabels('Description'); ?></label>
            <input type="text" name="description" id="description" value = "<?= $description ?>">
            <br>
            <label for="price"><?= translateLabels('Price'); ?></label>
            <input type="number" name="price" id="price" step="0.1" min = "0"value = "<?= $price ?>">
            <br>
            <input type="submit" value=" <?= translateLabels("Edit") ?> ">
        </form>
        <br>
        <a href="products.php"><?= translateLabels("Products page") ?></a>
    <?php else: ?>
        <?php header("Location: products.php");?>
        <?php die(); ?>
    <?php endif; ?>
</body>
</html>
