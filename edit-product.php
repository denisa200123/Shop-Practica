<?php

session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    die();    
}

require_once 'common.php';

if (isset($_POST['productId']) && filter_var($_POST['productId'], FILTER_VALIDATE_INT)) {
    $productId = $_POST['productId'];
    $_SESSION['product_id'] = $productId; // in case the validation fails, we won't lose the id
} elseif (isset($_SESSION['product_id']) && filter_var($_SESSION['product_id'], FILTER_VALIDATE_INT)) {
    $productId = $_SESSION['product_id'];
} else {
    header('Location: products.php');
    die();
}

$query = "SELECT * FROM products WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $productId);
$stmt->execute();

$selectedProduct = $stmt->fetch(PDO::FETCH_ASSOC);

$name = $selectedProduct['title'];
$description = $selectedProduct['description'];
$price = $selectedProduct['price'];
$image = $selectedProduct['image'];

$productEditingErrors = $_SESSION['product_editing_errors'] ?? [];
$imageErrors = $_SESSION['image_errors'] ?? [];
unset($_SESSION['image_errors'], $_SESSION['product_editing_errors']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Edit product') ?></title>
</head>

<body>
    <?php if ($selectedProduct): ?>
        <?php require_once 'language-switcher.php'; ?>

        <form action="edit-product-processing.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="productId" id="id" value="<?= htmlspecialchars($productId) ?>">
            <input type="hidden" name="image" id="image" value="img/<?= htmlspecialchars($image) ?>">

            <label for="name"><?= translateLabels('Name') ?></label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>">

            <br>
            <label for="description"><?= translateLabels('Description') ?></label>
            <input type="text" name="description" id="description" value="<?= htmlspecialchars($description) ?>">

            <br>
            <label for="price"><?= translateLabels('Price') ?></label>
            <input type="number" name="price" id="price" step="0.01" min="0" value="<?= htmlspecialchars($price) ?>">

            <br>
            <label for="fileToUpload"><?= translateLabels('Image') ?></label>
            <input type="file" name="fileToUpload" id="fileToUpload">

            <br><br>
            <input type="submit" value="<?= translateLabels("Edit") ?>">
        </form>

        <br>
        <!-- display the image errors, if there are any -->
        <?php if (!empty($imageErrors)): ?>
            <?php foreach ($imageErrors as $error): ?>
                <?= $error ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- display the editing errors, if there are any -->
        <?php if (!empty($productEditingErrors)): ?>
            <?php foreach ($productEditingErrors as $error): ?>
                <?= $error ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>

        <br>
    <?php endif; ?>
    <a href='products.php'><?= translateLabels('Products page') ?></a>
</body>

</html>
