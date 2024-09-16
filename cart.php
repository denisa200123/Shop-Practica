<?php
require 'common.php';
session_start();

if(isset($_SESSION["cartIds"])) {
  //displaying products
  print_r($_SESSION["cartIds"]);
  echo "<br>";
  $cartIds = $_SESSION["cartIds"];
  $cartIds = implode(",", $_SESSION["cartIds"]);
  $query = "SELECT * FROM products WHERE id IN ('$cartIds')";
  $stmt = $pdo->prepare(query: $query);
  $stmt->execute();
  
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($products as $row) {
    foreach ($row as $key => $value) {
      echo $key . ": " . $value, "<br>\n";
    }
  }
} else {
  echo "The cart is empty!";
}

echo "<br><a href='index.php'>Go to index</a>";