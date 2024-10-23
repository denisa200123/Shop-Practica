<?php

session_start();
require_once 'config.php';
require_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_array($_SESSION['productsInCart']) && !empty($_SESSION['productsInCart'])) {

    //using strip_tags to sanitize user input(all the html and php tags are removed)
    $name = htmlspecialchars(strip_tags($_POST['name'] ?? ''));
    $contactDetails = htmlspecialchars(strip_tags($_POST['contactDetails'] ?? ''));
    $comments = htmlspecialchars(strip_tags($_POST['comments'] ?? ''));
    $date = htmlspecialchars(strip_tags($_POST['date'] ?? ''));


    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $contactDetails = filter_var($contactDetails, FILTER_SANITIZE_STRING);
    $comments = filter_var($comments, FILTER_SANITIZE_STRING);
    $date = preg_replace('([^0-9/])', '', $date);

    //comments can be empty
    $userInput = [$name, $contactDetails, $date];

    $errors = [];
    if (isInputEmpty($userInput)) {
        $errors['emptyInput'] = translateLabels('Not all fields were filled!');
    }

    //name should contain only letters, spaces, dashes
    $filteredName = str_replace(array(' ', '-'), '', $name);
    if (!ctype_alpha($filteredName) && strlen($filteredName) > 0) {
        $errors['invalidName'] = translateLabels('The "name" field contains invalid characters!');
    }

    //save user input; used when validation fails so user doesn't have to write again and also for sending the email to the manager
    $checkoutData = [
        'name' => htmlspecialchars_decode($name),
        'contactDetails' => htmlspecialchars_decode($contactDetails),
        'comments' => htmlspecialchars_decode($comments)
    ];
    $_SESSION['user_input'] = $checkoutData;

    if ($errors) {
        $_SESSION['checkout_errors'] = $errors;
    } else {
        ob_start();
        include 'mail-template.php';
        $cartContents = ob_get_clean();

        //if the file that contains info from the cart page was found, try to send email
        if ($cartContents) {
            $mail = require_once __DIR__ . '/mailer.php';
            $total = 0;
            foreach ($_SESSION['productsInCart'] as $id => $product) {
                if (isset($product['price'])) {
                    $total += $product['price'];
                }

                if (isset($product['image'])) {
                    $mail->addEmbeddedImage(htmlspecialchars('img/' . $product['image']), 'img_embedded_$id');
                }
            }

            $mail->setFrom('user@gmail.com');
            $mail->addAddress(SHOP_EMAIL);
            $mail->Subject = translateLabels('Checkout information');
            $mail->Body = $cartContents;

            //send mail
            $mail->send();
            $query = "INSERT INTO orders(creation_date, customer_name, contact_details, comments, total_price) VALUES
                        (:date, :customerName, :contactDetails, :comments, :total)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':customerName', $checkoutData['name']);
            $stmt->bindParam(':contactDetails', $checkoutData['contactDetails']);
            $stmt->bindParam(':comments', $checkoutData['comments']);
            $stmt->bindParam(':total', $total);
            $stmt->execute();
            $orderId = $pdo->lastInsertId();

            foreach ($_SESSION['productsInCart'] as $product) {
                $query = "INSERT INTO ordersproducts(order_id, product_id) VALUES (:orderId, :productId)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':orderId', $orderId);
                $stmt->bindParam(':productId', $product['id']);
                $stmt->execute();
            }

            $stmt = null;
            $pdo = null;

            $_SESSION['checkout_success'] = true;
            unset($_SESSION['cartIds']);
            unset($_SESSION['productsInCart']);
        } else {
            $_SESSION['checkout_failed'] = true;
        }
    }
}

header('Location: cart.php');
die();
