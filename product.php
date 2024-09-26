<?php

session_start();
require_once 'common.php';

//check for adding function errors
if (isset($_SESSION["addErrors"]) && !empty($_SESSION["addErrors"])) {
    $addErrors = $_SESSION["addErrors"];
    unset($_SESSION["addErrors"]);
}

//check for image errors
if (isset($_SESSION["imageErrors"]) && !empty($_SESSION["imageErrors"])) {
    $imageErrors = $_SESSION["imageErrors"];
    unset($_SESSION["imageErrors"]);
}

if (isset($_SESSION["imageUploaded"]) && !empty($_SESSION["imageUploaded"])) {
    $imgForm = $_SESSION["imageUploaded"];
} else {
    $imgForm = "";
}
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

        <form action="process-image.php" method="post" enctype="multipart/form-data">
            <label for="fileToUpload"><?= translateLabels('Image'); ?></label>
            <input type="hidden" name="originFile" id="originFile" value="<?= htmlspecialchars("product.php") ?>">
            <input type="file" name="fileToUpload" id="fileToUpload" title=" ">
            <input type="submit" value="<?= translateLabels("Save the image") ?>" name="submitImage">
        </form>

        <form action="add-product-processing.php" method="POST">
            <input type="hidden" name="imageName" id="imageName" value="<?= 'img/' . htmlspecialchars($imgForm) ?>">

            <label for="name"><?= translateLabels('Name'); ?></label>
            <input type="text" name="name" id="name" required>

            <br>
            <label for="description"><?= translateLabels('Description'); ?></label>
            <input type="text" name="description" id="description" required>

            <br>
            <label for="price"><?= translateLabels('Price'); ?></label>
            <input type="number" name="price" id="price" step="0.1" min="0" required>

            <br><br>
            <input type="submit" value=" <?= translateLabels("Add") ?> ">
        </form>

        <?php if(isset($_SESSION["imageUploaded"]) && !empty($_SESSION["imageUploaded"])): ?>
            <?= translateLabels("Image uploaded") ?>
            <?php unset($_SESSION["imageUploaded"]); ?>
            <br>
        <!-- display the image errors, if there are any -->
        <?php elseif (!empty($imageErrors)): ?>
            <?php foreach ($imageErrors as $error): ?>
                <?= $error ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($addErrors)): ?>
            <?php foreach ($addErrors as $error): ?>
                <?= $error ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>

        <br>
        <a href="products.php"><?= translateLabels("Products page") ?></a>
    <?php else: ?>
        <?php header("Location: index.php");?>
        <?php die(); ?>
    <?php endif; ?>
</body>
</html>