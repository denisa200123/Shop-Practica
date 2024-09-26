<?php 

session_start();
require_once 'common.php';

if(isset($_POST["productId"]) && filter_var($_POST["productId"], FILTER_VALIDATE_INT)) {
    $productId = $_POST["productId"];
    $_SESSION["productId"] = $productId; // in case the validation fails, we won't lose the id
} elseif (isset($_SESSION["productId"]) && filter_var($_SESSION["productId"], FILTER_VALIDATE_INT)) {
    $productId = $_SESSION["productId"];
} else {
    header("Location: products.php");
    die();
}

// check if the id is an int
if (filter_var($productId, FILTER_VALIDATE_INT)) {
    $query = "SELECT * FROM products WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $productId);
    $stmt->execute();
    
    $selectedProduct = $stmt->fetch(PDO::FETCH_ASSOC);

    $name = $selectedProduct["title"];
    $description = $selectedProduct["description"];
    $price = $selectedProduct["price"];

} else {
    header("Location: products.php");
    die();
}

//check for errors
if(isset($_SESSION["editing_errors"]) &&  !empty($_SESSION["editing_errors"])) {
    $errors = $_SESSION["editing_errors"];
    unset($_SESSION["editing_errors"]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit product</title>
</head>
<body>
    <?php if ($selectedProduct && isset($_SESSION["admin_logged"])): ?>
        <form action= "edit-product-processing.php" method="POST">
            <input type="hidden" name="productId" id="id" value = "<?= htmlspecialchars($productId) ?>">

            <label for="name"><?= translateLabels('Name'); ?></label>
            <input type="text" name="name" id="name" value = "<?= htmlspecialchars($name) ?>">

            <br>
            <label for="description"><?= translateLabels('Description'); ?></label>
            <input type="text" name="description" id="description" value = "<?= htmlspecialchars($description) ?>">
            
            <br>
            <label for="price"><?= translateLabels('Price'); ?></label>
            <input type="number" name="price" id="price" step="0.1" min = "0"value = "<?= htmlspecialchars($price) ?>">

            <br>
            <input type="submit" value=" <?= translateLabels("Edit") ?> ">
        </form>
        <br>

        <form action="upload-image.php" method="post" enctype="multipart/form-data">
            <label for="fileToUpload"><?= translateLabels('Image'); ?></label>
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
        </form>
           
        <!-- display the checkout errors, if there are any -->
        <?php if(!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <?= $error ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>

        <a href="products.php"><?= translateLabels("Products page") ?></a>
    <?php else: ?>
        <?php header("Location: products.php");?>
        <?php die(); ?>
    <?php endif; ?>
</body>
</html>
