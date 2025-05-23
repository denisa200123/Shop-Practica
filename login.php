<?php

session_start();

if (isset($_SESSION['admin_logged_in'])) {
    header('Location: products.php');
    die();    
}

require_once 'common.php';

$username = $_SESSION['login_username'] ?? '';
unset($_SESSION['login_username']);

//check if there are login errors
if (!empty($_SESSION['login_errors'])) {
    $errors = $_SESSION['login_errors'];
    unset($_SESSION['login_errors']);
}

$loginFailed = false;
if (!empty($_SESSION['login_failed'])) {
    $loginFailed = true;
    unset($_SESSION['login_failed']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Login') ?></title>
</head>
<body>
    <?php require_once 'language-switcher.php'; ?>

    <form action="login-processing.php" method="POST">
        <label for="username"><?= translateLabels('Username') ?></label>
        <input type="text" name="username" id="username" required value="<?= $username ?>">
        <br>
        <label for="password"><?= translateLabels('Password') ?></label>
        <input type="password" name="password" id="password" required>
        <br>
        <input type="submit" value="<?= translateLabels('Login') ?>">
    </form>

    <?php if ($loginFailed): ?>
        <?= translateLabels('Login failed!') ?>
    <?php endif; ?>

    <br>

    <!-- display the login errors, if there are any -->
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <?= $error ?>
            <br>
        <?php endforeach; ?>
    <?php endif; ?>

    <br><br>
    <a href='index.php'><?= translateLabels('Go to main page') ?></a>
</body>
</html>
