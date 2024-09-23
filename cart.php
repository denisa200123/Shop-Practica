<?php
require 'common.php';
session_start();

// if a product is selected, then it'll be removed
if (isset($_POST["productSelected"]) && ($key = array_search($_POST["productSelected"], $_SESSION["cartIds"])) !== false) {
    unset($_SESSION["cartIds"][$key]); //unset value
    $_SESSION["cartIds"] = array_values($_SESSION["cartIds"]); // reindex array
}

//when there are products in the cart, select all the products that are in it
if (isset($_SESSION["cartIds"]) && !empty($_SESSION["cartIds"])) {
    $cartProducts = implode(',', array_fill(0, count($_SESSION["cartIds"]), '?'));
    $query = "SELECT * FROM products WHERE id IN ($cartProducts)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($_SESSION["cartIds"]);

    $productsInCart = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION["productsInCart"] = $productsInCart; //used in mail-template to display the products

    $pdo = null;
    $stmt = null;
}

//if validation fails, remember the form fields
$name = isset($_SESSION["user_input"]["name"]) ? $_SESSION["user_input"]["name"] : "";
$contactDetails = isset($_SESSION["user_input"]["contactDetails"]) ? $_SESSION["user_input"]["contactDetails"] : "";
$comments = isset($_SESSION["user_input"]["comments"]) ? $_SESSION["user_input"]["comments"] : "";
unset($_SESSION["user_input"]);

//check if there are checkout errors
if(isset($_SESSION["checkout_errors"]) &&  !empty($_SESSION["checkout_errors"])) {
    $errors = $_SESSION["checkout_errors"];
    unset($_SESSION["checkout_errors"]);
}

$checkoutSuccess = false;
if (isset($_SESSION["checkout_success"]) && !empty($_SESSION["checkout_success"])) {
    $checkoutSuccess = true;
    unset($_SESSION["checkout_success"]);
}

$checkoutFailed = false;
if (isset($_SESSION["checkout_failed"]) && !empty($_SESSION["checkout_failed"])) {
    $checkoutFailed = true;
    unset($_SESSION["checkout_failed"]);
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
                    <td><img src="<?= htmlspecialchars($product['image']) ?>"></td>
                    <td>
                        <form method='post'>
                            <input type='hidden' name='productSelected' value= <?= htmlspecialchars($product["id"]) ?> >
                            <input type='submit' value= <?= translateLabels('Remove'); ?> >
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <br><br>
        <!-- form for sending checkout info-->
        <p><?= translateLabels('Please fill out this form in order to complete your order'); ?></p>
        <form method="POST" action="send-mail.php">
            <label for="name"><?= translateLabels('Name'); ?></label>
            <input type="text" name="name" id="name" value = "<?= $name ?>" required>
            <br>
            <label for="name"><?= translateLabels(label: 'Contact details'); ?></label>
            <input type="text" name="contactDetails" id="contactDetails" value = "<?= $contactDetails ?>" required>
            <br>
            <label for="name"><?= translateLabels(label: 'Comments'); ?></label>
            <input type="text" name="comments" id="comments" value = "<?= $comments ?>" >
            <br>

            <input type="submit" value="Checkout">
        </form>
    <?php else: ?>
        <?= translateLabels('Cosul e gol'); ?>
    <?php endif; ?>
    <br>
    
    <!-- display the checkout errors, if there are any -->
    <?php if(!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <?= $error ?>
            <br>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- display a success message if all went good -->
    <?php if ($checkoutSuccess): ?>
        <?= translateLabels("Information sent successfully!") ?>
    <?php endif; ?>
    
    <?php if ($checkoutFailed): ?>
        <?= translateLabels("Information couldn't be sent!") ?>
    <?php endif; ?>

    <br><br>
    <a href="index.php"><?= translateLabels('Go to main page'); ?></a>
    
</body>
</html>
