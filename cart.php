<?php
session_start();
require_once 'common.php';

// if a product is selected, then it'll be removed
if (isset($_POST['id']) && ($key = array_search($_POST['id'], $_SESSION['cart_ids'])) !== false
    && filter_var($_POST['id'], FILTER_VALIDATE_INT)) {
    unset($_SESSION['cart_ids'][$key]); //unset value
    $_SESSION['cart_ids'] = array_values($_SESSION['cart_ids']); // reindex array
}

//when there are products in the cart, select all the products that are in it
if (!empty($_SESSION['cart_ids']) && is_array($_SESSION['cart_ids'])) {
    $cartProducts = implode(',', array_fill(0, count($_SESSION['cart_ids']), '?'));
    $query = "SELECT * FROM products WHERE id IN ($cartProducts)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($_SESSION['cart_ids']);

    $productsInCart = $stmt->fetchAll(PDO::FETCH_ASSOC); //used in mail-template to display the products
    $_SESSION['products_in_cart'] = $productsInCart;

} else {
    $productsInCart = [];
}

$pdo = null;
$stmt = null;

//if validation fails, remember the form fields
$name = $_SESSION['user_input']['name'] ?? '';
$contactDetails = $_SESSION['user_input']['contactDetails'] ?? '';
$comments = $_SESSION['user_input']['comments'] ?? '';
unset($_SESSION['user_input']);

//check if there are checkout errors
if (!empty($_SESSION['checkout_errors'])) {
    $errors = $_SESSION['checkout_errors'];
    unset($_SESSION['checkout_errors']);
}

$checkoutSuccess = isset($_SESSION['checkout_success']);
$checkoutFailed = isset($_SESSION['checkout_failed']);
unset($_SESSION['checkout_success'], $_SESSION['checkout_failed']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Cart') ?></title>
    <style>
        img {
            width: 150px;
            height: auto;
        }
    </style>
</head>

<body>
    <?php include_once 'language-switcher.php'; ?>

    <h1><?= translateLabels('Your cart') ?></h1>

    <!-- display the cart products and the checkout form if the cart is not empty;
    if the cart is empty display message -->
    <?php if (!empty($productsInCart)): ?>
        <!-- display the products -->
        <table border="1" cellpadding="10">
            <tr>
                <th><?= translateLabels('Name') ?></th>
                <th><?= translateLabels('Price') ?></th>
                <th><?= translateLabels('Description') ?></th>
                <th><?= translateLabels('Image') ?></th>
                <th><?= translateLabels('Remove from cart') ?></th>
            </tr>
            <?php foreach ($productsInCart as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['title']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td><img src='img/<?= htmlspecialchars($product['image']) ?>'></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                            <input type="submit" value="<?= translateLabels('Remove') ?>">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <br><br>
        <!-- form for sending checkout info-->
        <p><?= translateLabels('Please fill out this form in order to complete your order') ?></p>
        <form method="POST" action="cart-checkout.php">
            <label for="name"><?= translateLabels('Name') ?></label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>" required>
            <br>
            <label for="contactDetails"><?= translateLabels('Contact details') ?></label>
            <input type="text" name="contactDetails" id="contactDetails" value="<?= htmlspecialchars($contactDetails) ?>" required>
            <br>
            <label for="comments"><?= translateLabels('Comments') ?></label>
            <input type="text" name="comments" id="comments" value="<?= htmlspecialchars($comments) ?>">
            <br>

            <input type="submit" value="Checkout">
        </form>
    <?php else: ?>
        <?= translateLabels('The cart is empty') ?>
        <?php unset($_SESSION['products_in_cart']); ?>
    <?php endif; ?>
    <br>

    <!-- display the checkout errors, if there are any -->
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <?= htmlspecialchars($error) ?>
            <br>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- display a success message if all went good -->
    <?php if ($checkoutSuccess): ?>
        <?= translateLabels('Information sent successfully!') ?>
    <?php endif; ?>

    <?php if ($checkoutFailed): ?>
        <?= translateLabels('Information could not be sent!') ?>
    <?php endif; ?>

    <br><br>
    <a href="index.php"><?= translateLabels('Go to main page') ?></a>

</body>

</html>
