<?php
require 'common.php';
session_start();

//initialize cardIds(stores the ids of the products)
if (!isset($_SESSION["cartIds"])) {
    $_SESSION["cartIds"] = [];
}

// if a product is selected, add it to the cart if it's not already there
if (isset($_POST["productSelected"]) && !in_array($_POST["productSelected"], $_SESSION["cartIds"])) {
    array_push($_SESSION["cartIds"], $_POST["productSelected"]);
}

if (!empty($_SESSION["cartIds"])) {
    //when there are products in the cart, select all the products that are not in it
    $cartProducts = implode(',', array_fill(0, count($_SESSION["cartIds"]), '?'));
    $query = "SELECT * FROM products WHERE id NOT IN ($cartProducts)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($_SESSION["cartIds"]);
} else {
    // when the cart is empty, select all products
    $query = "SELECT * FROM products";
    $stmt = $pdo->prepare($query);
    $stmt->execute($_SESSION["cartIds"]);
}

//fetch all products
$productsNotInCart = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdo = null;
$stmt = null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <style>
        img {
            width: 150px;
            height: auto;
        }
    </style>
</head>

<body>

    <?php if(isset($_SESSION["admin_logged"])): ?>
        <span> <?= translateLabels("Admin logged") ?> </span>
        <a href="products.php"><?= translateLabels('Products page'); ?></a>
    <?php else: ?>
        <span><?= translateLabels("Do you have an admin account?") ?></span>
        <a href="login.php"><?= translateLabels("Login") ?></a>
    <?php endif; ?>

    <!-- display message (are there products in the cart or not) -->
    <h1>
        <?php if (count($productsNotInCart) === 0): ?>
            <?= translateLabels('You bought everything!'); ?>
        <?php elseif(count($productsNotInCart) > 0): ?>
            <?= translateLabels('What you can buy:'); ?>
        <?php else: ?>
            <?= translateLabels('Something is not right!'); ?>
        <?php endif; ?>
    </h1>
    <br>

    <!-- display the products -->
    <table border="1" cellpadding="10">
        <tr>
            <th><?= translateLabels('Name') ?></th>
            <th><?= translateLabels('Price') ?></th>
            <th><?= translateLabels('Description') ?></th>
            <th><?= translateLabels('Image') ?></th>    
            <th><?= translateLabels('Add to cart') ?></th>   
        </tr>
        <?php foreach ($productsNotInCart as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['title']) ?></td>
                <td><?= htmlspecialchars($product['price']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td><img src="<?= htmlspecialchars($product['image']) ?>"></td>
                <td>
                    <form method='post'>
                        <input type='hidden' name='productSelected' value= "<?= htmlspecialchars($product["id"]) ?>" >
                        <input type='submit' value= "<?= translateLabels('Add'); ?>" >
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="cart.php"><?= translateLabels('Go to cart'); ?></a>
</body>

</html>
