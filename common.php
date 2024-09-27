<?php

require_once 'config.php';

//db connection
try {
    $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME, USERNAME, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "We encountered a problem " . $e->getMessage();
}

//allowed image extensions
$imgExtensions = array("png","jpeg","gif","webp","svg","jpg", "jfif");

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
        'Incorrect login information!'=> 'Informatiile de logare sunt gresite!',
        'Admin logged'=> 'Admin logat',
        'Want to logout?'=> 'Vrei sa iesi din cont?',
        'Logout'=> 'Delogare',
        'Edit'=> 'Editeaza',
        'Products page'=> 'Pagina produse',
        'Are you sure you want to delete this item?'=> 'Esti sigur ca vrei sa stergi acest produs?',
        'Yes'=> 'Da',
        "Price doesn't have a valid value!" => "Pretul nu are o valoare valida!",
        'Add product' => 'Adauga produs',
        'Save'=> 'Salveaza',
        'Save the image'=> 'Salveaza imaginea',
        'File is not an image'=> 'Fisierul nu e o imagine',
        'File uploaded already'=> 'Fiserul exista deja',
        'Extension is not supported'=> 'Extensia nu este acceptata',
        "Couldn't upload image"=> "Imaginea nu a putut fi incarcata",
        'Image uploaded'=> 'Imagine incarcata',
        'Choose a language:'=> 'Alege o limba:',
        'Change language'=> 'Schimba limba',
        'english'=> 'engleza',
        'romanian'=> 'romana',
    ];
    if(isset($_SESSION["language"]) &&  $_SESSION["language"] === "RO"){
        return $labels[$label];
    }
    return $label;
}

//used to check if any user input is empty
function isInputEmpty(array $inputs)
{
    foreach ($inputs as $input) {
        if (!$input && $input !== '0') {
            return true;
        } 
    }
    return false;
}

function isPriceInvalid(float $price)
{
    if ($price <= 0) {
        return true;
    }
    return false;
}