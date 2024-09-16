<?php

//if ($_SERVER['REQUEST_METHOD'] == "POST") { 
  require 'common.php';
  session_start();

  //initialize cardIds
  if(!isset($_SESSION["cartIds"])) {
    $_SESSION["cartIds"] = [];
  }

  //if a product is selected, it'll be added to the cartIds to remember it
  if(isset($_POST["productSelected"])){
    if (!in_array($_POST["productSelected"], $_SESSION["cartIds"])) {
      array_push($_SESSION["cartIds"], ($_POST["productSelected"]));
    }
  }

  $cartIds = implode(",", $_SESSION["cartIds"]);
  echo "in cart: " . $cartIds . "<br><br>";

  //displaying products
  $query = "SELECT * FROM products WHERE id NOT IN ('$cartIds')";
  $stmt = $pdo->prepare(query: $query);
  $stmt->execute();

  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($products as $row) {
    foreach ($row as $key => $value) {
      echo $key . ": " . $value, "<br>\n";
    }
    //button to update the cart variable(sends the id of the current product)
    echo "<form method='post'>
          <input type='hidden' name='productSelected' id='productSelected' value=" . htmlspecialchars($row["id"]) . ">
          <input type='submit' value='Add'>
    </form>";
    
  }

  echo "<a href='cart.php'>Go to cart</a>";

  $pdo = null;
  $stmt = null;
//}
