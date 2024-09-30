<?php

require_once 'config.php';
require_once 'common.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_SESSION["productsInCart"])) {
    //using strip_tags to sanitize user input(all the html and php tags are removed)
    $name = isset($_POST["name"]) ? strip_tags($_POST["name"]) : "";
    $contactDetails = isset($_POST["name"]) ? strip_tags($_POST["contactDetails"]) : "";
    $comments = isset($_POST["name"]) ? strip_tags($_POST["comments"]) : "";
    
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $contactDetails = filter_var($contactDetails, FILTER_SANITIZE_STRING);
    $comments = filter_var($comments, FILTER_SANITIZE_STRING);

    //comments can be empty
    $userInput = [$name, $contactDetails];

    $errors = [];
    if(isInputEmpty($userInput)){
        $errors["emptyInput"] = translateLabels( "Not all fields were filled!");
    } 

    //name should contain only letters, spaces, dashes
    $filteredName=str_replace(array(" ", "-"), "", $name);
    if(!ctype_alpha($filteredName) && strlen($filteredName) > 0) {
        $errors["invalidName"] = translateLabels("The 'name' field contains invalid characters!");
    } 

    //save user input; used when validation fails so user doesn't have to write again and also for sending the email to the manager
    $checkout_data = [
        "name" => htmlspecialchars_decode($name),
        "contactDetails" => htmlspecialchars_decode($contactDetails),
        "comments" => htmlspecialchars_decode($comments),
    ];
    $_SESSION["user_input"] = $checkout_data;

    if($errors) {
        $_SESSION["checkout_errors"] = $errors;
    } else {
        ob_start();
        include 'mail-template.php';
        $cartContents = ob_get_clean();

        //if the file that contains info from the cart page was found, try to send email
        if($cartContents) {
            $mail = require __DIR__ . "/mailer.php";

            //add inline attachments for images
            foreach ($_SESSION["productsInCart"] as $id => $product) {
                if(isset($product["image"])) {
                    $mail->addEmbeddedImage(htmlspecialchars("img/" . $product['image']), "img_embedded_$id");
                }
            }

            $mail->setFrom("user@gmail.com");
            $mail->addAddress(SHOP_EMAIL);
            $mail->Subject = translateLabels("Checkout information");
            $mail->Body = $cartContents;  
    
            try {
                $mail->send();
                $_SESSION["checkout_success"] = true;
                unset($_SESSION["cartIds"]);
                unset($_SESSION["productsInCart"]);
            } catch (Exception $e) {
                echo "The message couldn't be sent!: {$mail->ErrorInfo}";
            }
        } else {
            $_SESSION["checkout_failed"] = true;
        }
    }
}

header("Location: cart.php");
die();
