<?php

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not permitted');
}

require_once 'config.php';

//db connection
try {
    $pdo = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, USERNAME, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed.');
}


//allowed image extensions
$imgExtensions = array('png', 'jpeg', 'gif', 'svg', 'jpg');

//available translations
$locales = [
    'en' => translateLabels('English'),
    'ro' => translateLabels('Romanian'),
];

$translations;

function translateLabels($label)
{
    global $translations;
    $language = $_SESSION['language'] ?? '';

    if (empty($translations)) {
        if ($language === 'ro') {
            $translations = require_once 'translations.ro.php';
        } elseif ($language === 'en') {
            $translations = require_once 'translations.en.php';
        }
    }

    return $translations[$label] ?? $label;
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
