<?php 

session_start();
require_once 'common.php';

$productId = (int)(htmlspecialchars($_POST["productId"]));
// check if the id is an int
if (is_int($productId)) {
    $selectedProduct = $_SESSION['products'][$productId-1];
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
    <title>Remove product</title>
    <style>
        img {
            width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <?php if ($_SERVER['REQUEST_METHOD'] === "POST" && $selectedProduct): ?>
        <h1><?= translateLabels("Are you sure you want to delete this item?"); ?></h1>

        <form method='post' action = "delete-product-processing.php">
            <input type='hidden' name='productId' value = "<?= htmlspecialchars($productId) ?>">
            <input type='submit' value= "<?= translateLabels('Yes'); ?>" >
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
                <td><img src="<?= htmlspecialchars($selectedProduct['image']) ?>"</td>
            </tr>
        </table>

        <br>

        <a href="products.php"><?= translateLabels("Products page") ?></a>
    <?php else: ?>
        <?php header("Location: products.php");?>
        <?php die(); ?>
    <?php endif; ?>
</body>
</html>
