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
        'Add' => 'Adauga',
        'Remove'=> 'Sterge',
        'Go to cart' => 'Spre cos',
        'Go to main page' => 'Mergi la pagina principala',
        'Your cart' => 'Cosul tau',
        'What you can buy:'=> 'Ce poti cumpara:',
        'You bought everything!'=> 'Ai cumparat tot!',
        'Something is not right!'=> 'Ceva nu a mers bine!',
        'The cart is empty'=> 'Cosul e gol',
    ];
    return $labels[$label] ?? $label;
}
