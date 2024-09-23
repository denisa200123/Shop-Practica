<?php 

//session_start();

require_once "common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        img {
            width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Information about your order</h1>

    <p>Name: <?= $_SESSION["user_input"]["name"]?></p>
    <p>Contact details: <?= $_SESSION["user_input"]["contactDetails"]?></p>
    <p>Comments: <?= $_SESSION["user_input"]["comments"]?></p>

    <?php $productsInCart = $_SESSION["productsInCart"]; ?>
    <table border="1" cellpadding="10">
        <tr>
            <th><?= translateLabels('Name') ?></th>
            <th><?= translateLabels('Price') ?></th>
            <th><?= translateLabels('Description') ?></th>
            <th><?= translateLabels('Image') ?></th>       
        </tr>
        <!-- display the info about each product -->
        <?php foreach ($productsInCart as $id => $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['title']) ?></td>
                <td><?= htmlspecialchars($product['price']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td><img src="cid:img_embedded_<?=$id?>"></td>
            </tr>
        <?php endforeach; ?>
    </table>
            
</body>
</html>
