<?php

require_once 'config.php';
require_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    //using strip_tags to sanitize user input(all the html and php tags are removed)
    $name = strip_tags($_POST["name"]);
    $contactDetails = strip_tags($_POST["contactDetails"]);
    $comments = strip_tags($_POST["comments"]);


    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $contactDetails = filter_var($contactDetails, FILTER_SANITIZE_STRING);
    $comments = filter_var($comments, FILTER_SANITIZE_STRING);

    //comments can be empty
    $userInput = [$name, $contactDetails];

    //i have to add errors array, not just text
    if(isInputEmpty($userInput)){
        echo "empty";
    } else{
        echo "not empty - ok";
    }

    //name should contain only letters
    //i have to add errors array, not just text
    if(!ctype_alpha($name)) {
        echo "invalid name";
    } else {
        echo "name - ok";
    }
}
/*
//send mail if all fields are valid
$mail = require __DIR__ . "/mailer.php";

$mail->setFrom("user@gmail.com");
$mail->addAddress(SHOP_EMAIL);
$mail->Subject = "Checkout information";
$mail->Body = <<<END

    Checkout info.

    END;

try {
    $mail->send();
    echo "Message sent!";
} catch (Exception $e) {
    echo "The message couldn't be sent!: {$mail->ErrorInfo}";
}*/
