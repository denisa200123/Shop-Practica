<?php
require 'common.php';
session_start();

//initialize cardIds(stores the ids of the products)
if (!isset($_SESSION["cartIds"])) {
  $_SESSION["cartIds"] = [];
}

// if a product is selected, add it to the cart if it's not already there
if (isset($_POST["productSelected"]) && !in_array($_POST["productSelected"], $_SESSION["cartIds"])){
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
  $stmt->execute($_SESSION["cartIds"]);;
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
  <?php //display message (are there products in the cart or not)
  if (count($productsNotInCart) === 0):
    echo translateLabels('You bought everything!');
  elseif(count($productsNotInCart) > 0):
    echo translateLabels('What you can buy:');
  else:
    echo translateLabels('Something is not right!');
  endif;
  ?>
  <br><br>

  <?php  //display the products
  foreach ($productsNotInCart as $product): ?>
    <img src="<?= htmlspecialchars($product['image']) ?>">

    <div>
      <b><?= translateLabels('Name') ?>:</b> <?= htmlspecialchars($product['title']) ?><br>
      <b><?= translateLabels('Price') ?>:</b> <?= htmlspecialchars($product['price']) ?><br>
      <b><?= translateLabels('Description') ?>:</b> <?= htmlspecialchars($product['description']) ?><br>
    </div>

    <form method='post'>
          <input type='hidden' name='productSelected' value= <?= htmlspecialchars($product["id"]) ?> >
          <input type='submit' value= <?= translateLabels('Add'); ?> >
    </form>
    <br><hr>
  <?php endforeach; ?>

  <a href="cart.php"><?= translateLabels('Go to cart'); ?></a>
</body>

</html>
