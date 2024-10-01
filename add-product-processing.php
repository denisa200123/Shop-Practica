<?php 

session_start();
require_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_FILES["fileToUpload"]["tmp_name"]) && !empty($_FILES["fileToUpload"]["tmp_name"])) {
    $target_dir = "img/";
    // ALL THIS SECTION IS FOR VALIDATING THE UPLOADED IMAGE
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $imgErrors = [];
    if (!$check) {
        $imgErrors["notImage"] = translateLabels("File is not an image");
    }

    if (!in_array($imageFileType, $imgExtensions)) {
        $imgErrors["invalidExtension"] = translateLabels("Extension is not supported");
    }

    $image = basename($_FILES["fileToUpload"]["name"]);


    $name = isset($_POST["name"]) ? strip_tags($_POST["name"]) : "";
    $description = isset($_POST["description"]) ? strip_tags($_POST["description"]) : "";
    $price = isset($_POST["price"]) ? strip_tags($_POST["price"]) : "";
    $image = strip_tags($image);

    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $price = filter_var($price, FILTER_VALIDATE_FLOAT);
    $image = filter_var($image, FILTER_SANITIZE_STRING);

    $userInput = [$name, $description, $price, $image];

    $addErrors = [];

    if (isInputEmpty($userInput)) {
        $addErrors["emptyInput"] = translateLabels( "Not all fields were filled!");
    }

    if (isPriceInvalid($price)) {
        $addErrors["invalidPrice"] = translateLabels( "Price doesn't have a valid value!");
    }

    if (empty($addErrors) && empty($imgErrors)) {
        $name = htmlspecialchars_decode($name);
        $description = htmlspecialchars_decode($description);
        $price = htmlspecialchars_decode($price);
        $image = htmlspecialchars_decode($image);

        //rename file if it already exists
        if (file_exists($target_file)) {
            $imgNoExtension = pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_FILENAME);
            $extension = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION));
            
            //if the file exists => increase index
            //for example: if you want to upload "img1.png", but the file already exists, check first if "img11.png" doesn't exist (in order to not overwrite it)
            //if "img11.png" exists, try "img12" etc.
            $index = 1;
            while (file_exists($target_dir . $imgNoExtension . $index . '.' . $extension)) {
                $index++;
            }

            $image =  $imgNoExtension . $index . "." . $extension;
            $target_file = $target_dir . $image;
        }
        
        $image = str_replace($target_dir, "", $image);

        $query = "INSERT INTO products(title, description, price, image) VALUES (:name,  :description,  :price,  :image);";
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":image", $image);
        $stmt->execute();

        $stmt = null;
        $pdo = null;

        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        header("Location: products.php");
        die();
    } else {
        $adding_data = [
            "name" => htmlspecialchars_decode($name),
            "description" => htmlspecialchars_decode($description),
            "price" => htmlspecialchars_decode($price),
        ];
        $_SESSION["adding_input"] = $adding_data;
        $_SESSION["imgErrors"] = $imgErrors;
        $_SESSION["addErrors"] = $addErrors;
    }
}
header("Location: product.php");
die();
