<?php

require_once 'config.php';
require_once 'translations.ro.php';

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not permitted');
}

//db connection
try {
    $pdo = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, USERNAME, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed.');
}


//allowed image extensions
$imgExtensions = array('png', 'jpeg', 'gif', 'webp', 'svg', 'jpg');

//available translations
$locales = [
	'en' => translateLabels('English'),
	'ro' => translateLabels('Romanian'),
];

function translateLabels($label) 
{
    if (isset($_SESSION['language']) && $_SESSION['language'] === 'ro') {
        return RO_TRANSLATIONS[$label] ?? $label;
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

//used in pagination
function createPageLink($pageNum, $text = null)
{
    if (is_null($text)) {
        $text = $pageNum;
    }
    return "<a href='products.php?page=$pageNum'>$text</a>";
}