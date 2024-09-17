<?php
require 'common.php';
session_start();

// if a product is selected, then it'll be removed
if (isset($_POST["productSelected"])) {
  $productSelected = $_POST["productSelected"];

  if (($key = array_search($productSelected, $_SESSION["cartIds"])) !== false) {
    unset($_SESSION["cartIds"][$key]); //unset value
    $_SESSION["cartIds"] = array_values($_SESSION["cartIds"]); // reindex array
  }
}

function displayCart($pdo)
{
  if (isset($_SESSION["cartIds"]) && !empty($_SESSION["cartIds"])) {
    $cartProducts = implode(',', array_fill(0, count($_SESSION["cartIds"]), '?'));
    $query = "SELECT * FROM products WHERE id IN ($cartProducts)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($_SESSION["cartIds"]);

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //display the products
    foreach ($products as $row) {
      foreach ($row as $key => $value) {
        if ($key === "image") {
          echo "<img src='" . htmlspecialchars($value) . "' style='width:150px; height:auto'>";
        } else {
          echo htmlspecialchars($key) . ": " . htmlspecialchars($value), "<br>\n";
        }
      }
      echo "<form method='post'>
                <input type='hidden' name='productSelected' value='" . htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8') . "'>
                <input type='submit' value='Remove'>
            </form>";
      echo "<br><hr>";
    }

    echo "<form action='' method='get'>
    
    </form>";
  } else {
    echo "The cart is empty!";
  }
}

?>
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
  <?php displayCart($pdo); ?>
  <br><a href='index.php'>Go to index</a>
</body>

</html>