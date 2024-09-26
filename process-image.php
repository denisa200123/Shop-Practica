<?php
session_start();
require_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_FILES["fileToUpload"]["tmp_name"]) && !empty($_FILES["fileToUpload"]["tmp_name"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    $target_dir = "img/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadSuccessful = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $errors = [];
    if ($check !== false) {
        $uploadSuccessful = 1;
    } else {
        $errors["notImage"] = translateLabels("File is not an image");
        $uploadSuccessful = 0;
    }

    if (in_array($imageFileType, $imgExtensions)) {
        $errors["invalidExtension"] = translateLabels("Extension is not supported");
        $uploadSuccessful = 0;
    }

    if ($uploadSuccessful) {
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        $_SESSION["imageUploaded"] = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
    } else {
        $errors["uploadFailed"] = translateLabels("Couldn't upload image");
    }

    $_SESSION["imageErrors"] = $errors;
}
header("Location: edit-product.php");
die();
