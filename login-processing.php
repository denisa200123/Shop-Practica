<?php

session_start();
require 'common.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //using strip_tags to sanitize user input(all the html and php tags are removed)
    $username = isset($_POST['username']) ? strip_tags($_POST['username']) : '';
    $password = isset($_POST['password']) ? strip_tags($_POST['password']) : '';

    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    $userInput = [$username, $password];

    $errors = [];
    if (isInputEmpty($userInput)) {
        $errors['emptyInput'] = translateLabels('Not all fields were filled!');
    }

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged'] = true;
        header('Location: products.php');
        die();
    } else {
        $_SESSION['login_username'] = $username; //remember username
        $errors['incorrectCredentials'] = translateLabels('Incorrect login information!');
        $_SESSION['login_failed'] = true;
    }

    if ($errors) {
        $_SESSION['login_errors'] = $errors;
        $_SESSION['login_failed'] = true;
    }
}

header('Location: login.php');
die();
