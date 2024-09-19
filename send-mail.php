<?php

require_once 'config.php';


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
}
