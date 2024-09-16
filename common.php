<?php

require_once 'config.php';

try {
  $pdo = new PDO("mysql:host=$host;dbname=test", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "We encountered a problem " . $e->getMessage();
}
