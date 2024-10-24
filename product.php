<?php

session_start();

require_once 'common.php';

$productCreationErrors = $_SESSION['product_creation_errors'] ?? [];
$imageErrors = $_SESSION['img_errors'] ?? [];
unset($_SESSION['product_creation_errors'], $_SESSION['img_errors']);

//if validation fails, remember the form fields
$name = $_SESSION['product_info']['name'] ?? '';
$description = $_SESSION['product_info']['description'] ?? '';
$price = $_SESSION['product_info']['price'] ?? '';
unset($_SESSION['product_info']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Add product') ?></title>
</head>
<body>
    <?php if (isset($_SESSION['admin_logged_in'])): ?>
        <?php include_once 'language-switcher.php'; ?>

        <form action="create-product-processing.php" enctype="multipart/form-data" method="POST">
            <label for="name"><?= translateLabels('Name') ?></label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>" required>

            <br>
            <label for="description"><?= translateLabels('Description') ?></label>
            <input type="text" name="description" id="description" value="<?= htmlspecialchars($description) ?>" required>

            <br>
            <label for="price"><?= translateLabels('Price') ?></label>
            <input type="number" name="price" id="price" step="0.01" min="0" value="<?= htmlspecialchars($price) ?>" required>

            <br>
            <label for="fileToUpload"><?= translateLabels('Image') ?></label>
            <input type="file" name="fileToUpload" id="fileToUpload" required>

            <br><br>
            <input type="submit" value="<?= translateLabels('Add') ?>">
        </form>

        <!-- display the image errors, if there are any -->
        <?php if (!empty($imageErrors)): ?>
            <?php foreach ($imageErrors as $error): ?>
                <?= $error ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($productCreationErrors)): ?>
            <?php foreach ($productCreationErrors as $error): ?>
                <?= $error ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>

        <br>
        <a href="products.php"><?= translateLabels('Products page') ?></a>
    <?php else: ?>
        <?php header('Location: index.php');
        die(); ?>
    <?php endif; ?>
</body>
</html>
