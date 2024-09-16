<?php
//if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
  require 'common.php';
  session_start();

  if(isset($_POST["productId"])){
    echo $_POST["productId"];
    array_push($cartIds, $_POST["productId"]);
    echo $cartIds;
  } else {
    $cartIds = [];
  }

  //displaying all products
  $query = "SELECT * FROM products";
  $stmt = $pdo->prepare(query: $query);
  $stmt->execute();

  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($products as $row) {
    foreach ($row as $key => $value) {
      echo $key . ": " . $value, "<br>\n";
    }
    //button to update the cart variable(sends the id of the current product)
    echo "<form method='post' action='index.php'>
          <input type='hidden' name='productId' id='productId' value=" . htmlspecialchars($row["id"]) . ">
          <input type='submit' value='Submit'>
    </form>";
  }

  $pdo = null;
  $stmt = null;
//}
