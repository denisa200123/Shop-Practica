<?php
require 'common.php';
session_start();

// if a product is selected, then it'll be removed
if (isset($_POST["productSelected"]) && ($key = array_search($_POST["productSelected"], $_SESSION["cartIds"])) !== false) {
    unset($_SESSION["cartIds"][$key]); //unset value
    $_SESSION["cartIds"] = array_values($_SESSION["cartIds"]); // reindex array
}

if (isset($_SESSION["cartIds"]) && !empty($_SESSION["cartIds"])) {
    //when there are products in the cart, select all the products that are in it
    $cartProducts = implode(',', array_fill(0, count($_SESSION["cartIds"]), '?'));
    $query = "SELECT * FROM products WHERE id IN ($cartProducts)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($_SESSION["cartIds"]);

    $productsInCart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $pdo = null;
    $stmt = null;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        img {
            width: 150px;
            height: auto;
        }
    </style>
</head>

<body>
    <h1><?= translateLabels('Your cart'); ?></h1>

    <!-- display the cart products if the cart is not empty; if the cart is empty display message -->
    <?php if (isset($_SESSION["cartIds"]) && !empty($_SESSION["cartIds"])): ?>
        <?php foreach ($productsInCart as $product): ?>
            <img src="<?= htmlspecialchars($product['image']) ?>">

            <div>
                <b><?= translateLabels('Name') ?>:</b> <?= htmlspecialchars($product['title']) ?><br>
                <b><?= translateLabels('Price') ?>:</b> <?= htmlspecialchars($product['price']) ?><br>
                <b><?= translateLabels('Description') ?>:</b> <?= htmlspecialchars($product['description']) ?><br>
            </div>

            <form method='post'>
                <input type='hidden' name='productSelected' value=<?= htmlspecialchars($product["id"]) ?> >
                <input type='submit' value=<?= translateLabels('Remove'); ?> >
            </form>

            <br><hr>
        <?php endforeach; ?>
    <?php else: ?>
        <?= translateLabels('Cosul e gol'); ?>
    <?php endif; ?>
    <br>

    <!-- test form for sending user info to shop email-->
    <form method="POST" action="send-mail.php">
        <label for="name"><?= translateLabels('Name'); ?></label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="name"><?= translateLabels(label: 'Contact details'); ?></label>
        <input type="text" name="contactDetails" id="contactDetails" required>
        <br>
        <label for="name"><?= translateLabels(label: 'Comments'); ?></label>
        <input type="text" name="comments" id="comments">
        <br>

        <input type="submit" value="Checkout">
    </form>
    <!-- -->
    <br>
    <a href="index.php"><?= translateLabels('Go to main page'); ?></a>

</body>
</html>
