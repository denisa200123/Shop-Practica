<?php

require_once 'config.php';

//db connection
try {
    $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME, USERNAME, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "We encountered a problem " . $e->getMessage();
}

//used to translate labels
function translateLabels(string $label) {
    $labels = [
        'Name' => 'Nume',
        'Price' => 'Pret',
        'Description' => 'Descriere',
        'Image' => 'Imagine',
        'Add' => 'Adauga',
        'Add to cart' => 'Adauga in cos',
        'Remove from cart' => 'Sterge din cos',
        'Remove'=> 'Sterge',
        'Go to cart' => 'Spre cos',
        'Go to main page' => 'Mergi la pagina principala',
        'Your cart' => 'Cosul tau',
        'What you can buy:'=> 'Ce poti cumpara:',
        'You bought everything!'=> 'Ai cumparat tot!',
        'Something is not right!'=> 'Ceva nu a mers bine!',
        'The cart is empty'=> 'Cosul e gol',
        'Contact details' => 'Detalii de contact',
        'Comments'=> 'Comentarii',
        'Not all fields were filled!'=> 'Nu toate campurile au fost completate!',
        "The 'name' field contains invalid characters!"=> "Campul 'nume' contine caractere interzise!",
        'Information sent successfully!' => 'Informatii trimise cu succes!',
        "Information couldn't be sent!" => 'Informatiile nu au putut fi trimise!',
        'Please fill out this form in order to complete your order' => 'Va rugam sa completati formularul pentru finalizarea comenzii',
        'Information about your order' => 'Informatii despre comanda ta',
        'Checkout information' => 'Informatii comanda',
        'Do you have an admin account?' => 'Ai cont de admin?',
        'Login' => 'Logare',
        'Username' => 'Nume utilizator',
        'Password' => 'Parola',
        'Login failed!'=> 'Logare nereusita!',
    ];
    return $labels[$label] ?? $label;
}

//used to check if any user input is empty
function isInputEmpty(array $inputs)
{
    foreach ($inputs as $input) {
        if (!$input) {
            return true;
        } 
    }
    return false;
}
