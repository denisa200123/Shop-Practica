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

  <?php  //display the cart products if the cart is not empty; if the cart is empty display message
  if (isset($_SESSION["cartIds"]) && !empty($_SESSION["cartIds"])):
    foreach ($productsInCart as $product): ?>
          <img src="<?= htmlspecialchars($product['image']) ?>">

          <div>
            <b><?= translatelabels('Name') ?>:</b> <?= htmlspecialchars($product['title']) ?><br>
            <b><?= translatelabels('Price') ?>:</b> <?= htmlspecialchars($product['price']) ?><br>
            <b><?= translatelabels('Description') ?>:</b> <?= htmlspecialchars($product['description']) ?><br>
          </div>

          <form method='post'>
            <input type='hidden' name='productSelected' value=<?= htmlspecialchars($product["id"]) ?>>
            <input type='submit' value=<?= translatelabels('Remove'); ?>>
          </form>
          <br>
          <hr>
      <?php endforeach;
  else:
    echo translateLabels('Cosul e gol');;
  endif; ?>
  <br>
  <a href="index.php"><?= translateLabels('Go to main page'); ?></a>
</body>

</html>
