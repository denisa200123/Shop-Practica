<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Index</title>
</head>

<body>
  <p>What you can buy:</p>
  <br>
</body>

</html>

<?php
require 'common.php';
session_start();

//initialize cardIds
if (!isset($_SESSION["cartIds"])) {
  $_SESSION["cartIds"] = [];
}

// if a product is selected, add it to the cart if it's not already there
if (isset($_POST["productSelected"])) {
  $productSelected = $_POST["productSelected"];
  if (!in_array($productSelected, $_SESSION["cartIds"])) {
    array_push($_SESSION["cartIds"], $productSelected);
  }
}

$cartIds = $_SESSION["cartIds"];

if (!empty($cartIds)) {
  //when there are products in the cart, select all the products that are not in it
  $cartProducts = implode(',', array_fill(0, count($cartIds), '?'));
  $query = "SELECT * FROM products WHERE id NOT IN ($cartProducts)";
  $stmt = $pdo->prepare($query);
  $stmt->execute($cartIds);
} else {
  // when the cart is empty, select all products
  $query = "SELECT * FROM products";
  $stmt = $pdo->prepare($query);
  $stmt->execute($cartIds);;
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
//display the products
foreach ($products as $row) {
  foreach ($row as $key => $value) {
    if($key === "image") {
      echo "<img src='$value' style='width:150px; height:auto'>";
    } else {
      echo htmlspecialchars($key) . ": " . htmlspecialchars($value), "<br>\n";
    }
  }
  echo "<form method='post'>
          <input type='hidden' name='productSelected' value='" . htmlspecialchars($row["id"]) . "'>
          <input type='submit' value='Add'>
    </form>";
  echo "<hr>";
}

//if all the products are in the cart, display message
  if(count($products) == 0) {
    echo "You bought everything!<br>";
  } 

echo "<a href='cart.php'>Go to cart</a>";

$pdo = null;
$stmt = null;
?>
