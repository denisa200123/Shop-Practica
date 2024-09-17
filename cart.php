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

// if a product is selected, remove it from the cart
if (isset($_POST["productSelected"])) {
  echo "id-uri: ";
  print_r($_SESSION["cartIds"]);
  echo "<br>";

  $productSelected = $_POST["productSelected"];
  echo "produs selectat: $productSelected<br>";

  if (($key = array_search($productSelected, $_SESSION["cartIds"])) !== false) {
    unset($_SESSION["cartIds"][$key]);
  }
  echo "dupa remove: ";
  print_r($_SESSION["cartIds"]);
  echo "<br><br>";
}

//if there are products in the cart, display them
if (isset($_SESSION["cartIds"])) {  
  if (!empty($_SESSION["cartIds"])) {
    print_r($_SESSION["cartIds"]);
    echo "<br><br>";
    $cartProducts = implode(',', array_fill(0, count($_SESSION["cartIds"]), '?'));
    print_r($cartProducts);
    echo "<br>";
    $query = "SELECT * FROM products WHERE id IN ($cartProducts)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($_SESSION["cartIds"]);

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //dipsay the products
    foreach ($products as $row) {
      foreach ($row as $key => $value) {
        if ($key === "image") {
          echo "<img src='$value' style='width:150px; height:auto'>";
        } else {
          echo htmlspecialchars($key) . ": " . htmlspecialchars($value), "<br>\n";
        }
      }
      echo "<form method='post'>
          <input type='hidden' name='productSelected' value='" . htmlspecialchars($row["id"]) . "'>
          <input type='submit' value='Remove'>
      </form>";
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
