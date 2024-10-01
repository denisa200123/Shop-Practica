<?php

require_once 'config.php';
require_once 'common.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_SESSION["productsInCart"])) {
    //using strip_tags to sanitize user input(all the html and php tags are removed)
    $name = isset($_POST["name"]) ? strip_tags($_POST["name"]) : "";
    $contactDetails = isset($_POST["name"]) ? strip_tags($_POST["contactDetails"]) : "";
    $comments = isset($_POST["name"]) ? strip_tags($_POST["comments"]) : "";
    $date = isset($_POST["date"]) ? strip_tags($_POST["date"]) : "";
   
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $contactDetails = filter_var($contactDetails, FILTER_SANITIZE_STRING);
    $comments = filter_var($comments, FILTER_SANITIZE_STRING);
    $date = preg_replace("([^0-9/])", "", $date);
    
    //comments can be empty
    $userInput = [$name, $contactDetails, $date];

    $errors = [];
    if (isInputEmpty($userInput)) {
        $errors["emptyInput"] = translateLabels( "Not all fields were filled!");
    } 

    //name should contain only letters, spaces, dashes
    $filteredName=str_replace(array(" ", "-"), "", $name);
    if (!ctype_alpha($filteredName) && strlen($filteredName) > 0) {
        $errors["invalidName"] = translateLabels("The 'name' field contains invalid characters!");
    } 

    //save user input; used when validation fails so user doesn't have to write again and also for sending the email to the manager
    $checkout_data = [
        "name" => htmlspecialchars_decode($name),
        "contactDetails" => htmlspecialchars_decode($contactDetails),
        "comments" => htmlspecialchars_decode($comments)
    ];
    $_SESSION["user_input"] = $checkout_data;

    if ($errors) {
        $_SESSION["checkout_errors"] = $errors;
    } else {
        ob_start();
        include 'mail-template.php';
        $cartContents = ob_get_clean();

        //if the file that contains info from the cart page was found, try to send email
        if ($cartContents) {
            $mail = require __DIR__ . "/mailer.php";

            //add inline attachments for images and calculate the summed price
            $_SESSION["totalPrice"] = 0;
            foreach ($_SESSION["productsInCart"] as $id => $product) {
                if (isset($product["image"]) && isset($product["price"])) {
                    $_SESSION["totalPrice"] += $product["price"];
                    $mail->addEmbeddedImage(htmlspecialchars("img/" . $product['image']), "img_embedded_$id");
                }
            }

            $mail->setFrom("user@gmail.com");
            $mail->addAddress(SHOP_EMAIL);
            $mail->Subject = translateLabels("Checkout information");
            $mail->Body = $cartContents;  

            try {
                //send mail
                $mail->send();

                //include the order details in "orders" table
                $customerDetails = "";
                foreach ($_SESSION["user_input"] as $detail => $input) {
                    if ($input) {
                        $customerDetails .= $detail . ": " . $input . "\n";
                    }
                }
                
                $cartProducts = "";
                foreach ($_SESSION["productsInCart"] as $productName) {
                    $cartProducts .= $productName["title"] . ", ";
                }
                
                $query = "INSERT INTO orders(creation_date, customer_details, purchased_products, total_price) VALUES (:date, :customer_details, :products_id, :total)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(":date", $date);
                $stmt->bindParam(":customer_details", $customerDetails);
                $stmt->bindParam(":products_id", $cartProducts);
                $stmt->bindParam(":total", $_SESSION["totalPrice"]);
                $stmt->execute();
            
                $stmt = null;
                $pdo = null;

                $_SESSION["checkout_success"] = true;
                unset($_SESSION["cartIds"]);
                unset($_SESSION["totalPrice"]);
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
