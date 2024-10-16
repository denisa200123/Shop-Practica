<?php 

session_start();
require_once 'common.php';

$id = $_POST['productId'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && filter_var($id, FILTER_VALIDATE_INT)) {
    $uploadedImage = 0;
    $target_dir = 'img/';
    if (isset($_FILES['fileToUpload']['tmp_name']) && !empty($_FILES['fileToUpload']['tmp_name'])) {
        // ALL THIS SECTION IS FOR VALIDATING THE UPLOADED IMAGE
        $check = getimagesize($_FILES['fileToUpload']['tmp_name']);
        $target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $imgErrors = [];
        if (!$check) {
            $imgErrors['notImage'] = translateLabels('File is not an image');
        }

        if (!in_array($imageFileType, $imgExtensions)) {
            $imgErrors['invalidExtension'] = translateLabels('Extension is not supported');
        }

        $image = basename($_FILES['fileToUpload']['name']);
        $uploadedImage = 1;
    } else {
        $image = isset($_POST['image']) ? $_POST['image'] : '';
    }

    //THIS SECTION IS FOR VALIDATING ALL DATA
    $name = isset($_POST['name']) ? strip_tags($_POST['name']) : '';
    $description = isset($_POST['description']) ? strip_tags($_POST['description']) : '';
    $price = isset($_POST['price']) ? strip_tags($_POST['price']) : '';
    $image = strip_tags($image);
    
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $price = filter_var($price, FILTER_VALIDATE_FLOAT);
    $image = filter_var($image, FILTER_SANITIZE_STRING);

    $userInput = [$name, $description, $price, $image];

    $editingErrors = [];

    if (isInputEmpty($userInput)) {
        $editingErrors['emptyInput'] = translateLabels( 'Not all fields were filled!');
    }

    if (isPriceInvalid($price)) {
        $editingErrors['invalidPrice'] = translateLabels( 'Price does not have a valid value!');
    }

    if (empty($editingErrors) && empty($imgErrors)) {
        $name = htmlspecialchars_decode($name);
        $description = htmlspecialchars_decode($description);
        $price = htmlspecialchars_decode($price);
        $image = htmlspecialchars_decode($image);

        //rename file if it already exists
        if (file_exists($target_file) && $uploadedImage) {
            $imgNoExtension = pathinfo(basename($_FILES['fileToUpload']['name']), PATHINFO_FILENAME);
            $extension = strtolower(pathinfo(basename($_FILES['fileToUpload']['name']), PATHINFO_EXTENSION));
            
            //if the file exists => increase index
            //for example: if you want to upload 'img1.png', but the file already exists, check first if 'img11.png' doesn't exist (in order to not overwrite it)
            //if 'img11.png' exists, try 'img12' etc.
            $index = 1;
            while (file_exists($target_dir . $imgNoExtension . $index . '.' . $extension)) {
                $index++;
            }

            $image =  $imgNoExtension . $index . '.' . $extension;
            $target_file = $target_dir . $image;
        }
        
        $image = str_replace($target_dir, '', $image);
        
        $query = "UPDATE products SET title = :name, description = :description, price = :price, image = :image WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image);
        $stmt->execute();

        $stmt = null;
        $pdo = null;
        unset($_SESSION['productId']);

        if ($uploadedImage) { 
            move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file);
        }
    } else {
        $_SESSION['imageErrors'] = $imgErrors;
        $_SESSION['editing_errors'] = $editingErrors;
        header('Location: edit-product.php');
        die();
    }
}
header('Location: products.php');
die();
