<?php

session_start();
require "common.php";

if($_SERVER['REQUEST_METHOD'] === "POST") {
    //using strip_tags to sanitize user input(all the html and php tags are removed)
    $username = strip_tags($_POST["username"]);
    $password = strip_tags($_POST["password"]);

    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING); //no need to hash it, it's not stored anywhere

    $userInput = [$username, $password];

    $errors = [];
    if(isInputEmpty($userInput)){
        $errors["emptyInput"] = translateLabels( "Not all fields were filled!");
    } 
    // don't have details about username and password yet, TO DO(MAYBE): user validation - shouldn't contain special chars?
    // password - minimum length? can contain any char?
    //or I simply just test if the username and password match with the ones in config?

    $_SESSION["login_username"] = $username;

    if($errors) {
        $_SESSION["login_errors"] = $errors;
        $_SESSION["login_failed"] = true;
    } 

    if($username === USERNAME && $password === PASSWORD) {
        header("Location: products.php");
    } else {
        $_SESSION["login_failed"] = true;
    }
}

header("Location: login.php");
die();
