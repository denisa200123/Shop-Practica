<?php

session_start();

require_once 'common.php';

$addProductErrors = isset($_SESSION['addProductErrors']) ? $_SESSION['addProductErrors'] : [];
$imageErrors = isset($_SESSION['imgErrors']) ? $_SESSION['imgErrors'] : [];
unset($_SESSION['addProductErrors'], $_SESSION['imgErrors']);

//if validation fails, remember the form fields
$name = $_SESSION['adding_input']['name'] ?? '';
$description = $_SESSION['adding_input']['description'] ?? '';
$price = $_SESSION['adding_input']['price'] ?? '';
unset($_SESSION['adding_input']);

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
    <?php if (isset($_SESSION['admin_logged'])): ?>
        <?php include_once 'language-switcher.php'; ?>

        <form action="add-product-processing.php" enctype="multipart/form-data" method="POST">
            <label for="name"><?= translateLabels('Name'); ?></label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>" required>

            <br>
            <label for="description"><?= translateLabels('Description'); ?></label>
            <input type="text" name="description" id="description" value="<?= htmlspecialchars($description) ?>" required>

            <br>
            <label for="price"><?= translateLabels('Price'); ?></label>
            <input type="number" name="price" id="price" step="0.01" min="0" value="<?= htmlspecialchars($price) ?>" required>

            <br>
            <label for="fileToUpload"><?= translateLabels('Image'); ?></label>
            <input type="file" name="fileToUpload" id="fileToUpload" required>

            <br><br>
            <input type="submit" value=" <?= translateLabels('Add') ?> ">
        </form>

        <!-- display the image errors, if there are any -->
        <?php if (!empty($imageErrors)): ?>
            <?php foreach ($imageErrors as $error): ?>
                <?= $error ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($addProductErrors)): ?>
            <?php foreach ($addProductErrors as $error): ?>
                <?= $error ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>

        <br>
        <a href="products.php"><?= translateLabels('Products page') ?></a>
    <?php else: ?>
        <?php header('Location: index.php');?>
        <?php die(); ?>
    <?php endif; ?>
</body>
</html>
