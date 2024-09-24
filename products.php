<?php

require_once 'common.php';
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body>
    <?php if (isset($_SESSION['admin_logged'])): ?>
        <p> <?= translateLabels("Admin logged") ?> </p>
        <span> <?= translateLabels("Want to logout?")?> </span>
        <a href="logout.php"> <?= translateLabels("Logout") ?> </a>

    <?php else: ?>
        <?php header("Location: index.php"); ?>
        <?php die(); ?>
    <?php endif; ?>
</body>
</html>