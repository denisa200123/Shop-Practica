<?php
if(isset($_SESSION["cart"])) {
  echo "The products: ";
} else {
  echo "The cart is empty!";
}
