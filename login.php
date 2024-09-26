<?php

session_start();
require "common.php";

$username = isset($_SESSION["login_username"]) ? $_SESSION["login_username"] : "";
unset($_SESSION["login_username"]);

//check if there are login errors
if(isset($_SESSION["login_errors"]) &&  !empty($_SESSION["login_errors"])) {
    $errors = $_SESSION["login_errors"];
    unset($_SESSION["login_errors"]);
}

$loginFailed = false;
if (isset($_SESSION["login_failed"]) && !empty($_SESSION["login_failed"])) {
    $loginFailed = true;
    unset($_SESSION["login_failed"]);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

    <!-- if admin is logged in, he should be redirected to products -->
    <?php if(!isset($_SESSION["admin_logged"])): ?>

        <form action="login-processing.php" method="POST">
            <label for="username"> <?= translateLabels("Username") ?></label>
            <input type="text" name="username" id="username" required value="<?= $username ?>">
            <br>
            <label for="password"> <?= translateLabels("Password") ?></label>
            <input type="password" name="password" id="password" required>
            <br>
            <input type="submit" value="<?= translateLabels("Login") ?>">
        </form>

        <?php if ($loginFailed): ?>
            <?= translateLabels("Login failed!") ?>
        <?php endif; ?>

        <br>
        
        <!-- display the login errors, if there are any -->
        <?php if(!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <?= $error ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php else: ?>
        <?php header("Location: products.php"); ?>
        <?php die(); ?>
    <?php endif; ?>

</body>
</html>
