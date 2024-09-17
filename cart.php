<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart</title>
</head>
<body>
  <h1>Your cart:</h1>
</body>
</html>
<?php
require 'common.php';
session_start();

//if there are products in the cart, display them
if (isset($_SESSION["cartIds"])) {
  $cartIds = $_SESSION["cartIds"];

  if (!empty($cartIds)) {
    $cartProducts = implode(',', array_fill(0, count($cartIds), '?'));
    $query = "SELECT * FROM products WHERE id IN ($cartProducts)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($cartIds);

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $row) {
      foreach ($row as $key => $value) {
        if ($key === "image") {
          echo "<img src='$value' style='width:150px; height:auto'>";
        } else {
          echo htmlspecialchars($key) . ": " . htmlspecialchars($value), "<br>\n";
        }
      }
      echo "<br><hr>";
    }
  } else {
    echo "The cart is empty!";
  }
} else {
  echo "The cart is empty!";
}

echo "<br><a href='index.php'>Go to index</a>";
?>
