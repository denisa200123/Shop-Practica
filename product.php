<?php

session_start();
require_once 'common.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add product</title>
</head>
<body>
    <?php if(isset($_SESSION["admin_logged"])): ?>
        <form action="add-product-processing.php" method="POST">
            <label for="name"><?= translateLabels('Name'); ?></label>
            <input type="text" name="name" id="name">

            <br>
            <label for="description"><?= translateLabels('Description'); ?></label>
            <input type="text" name="description" id="description">
            
            <br>
            <label for="price"><?= translateLabels('Price'); ?></label>
            <input type="number" name="price" id="price" step="0.1" min = "0">

            <br>
            <input type="submit" value=" <?= translateLabels("Save") ?> ">
        </form>

        <a href="products.php"><?= translateLabels("Products page") ?></a>
    <?php else: ?>
        <?php header("Location: index.php");?>
        <?php die(); ?>
    <?php endif; ?>
</body>
</html>