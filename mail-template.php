<?php

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not permitted');
}

require_once 'common.php';
$productsInCart = $_SESSION['products_in_cart'] ?? '';
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
    <h1><?= translateLabels('Information about your order') ?></h1>

    <p><?= translateLabels('Name')?>: <?= htmlspecialchars($_SESSION['user_input']['name']) ?></p>
    <p><?= translateLabels('Contact details')?>: <?= htmlspecialchars($_SESSION['user_input']['contactDetails'])?></p>
    <p><?= translateLabels('Comments')?>: <?= htmlspecialchars($_SESSION['user_input']['comments'])?></p>

    <table border="1" cellpadding="10">
        <tr>
            <th><?= translateLabels('Name') ?></th>
            <th><?= translateLabels('Price') ?></th>
            <th><?= translateLabels('Description') ?></th>
            <th><?= translateLabels('Image') ?></th>
        </tr>
        <!-- display the info about each product -->
        <?php if ($productsInCart): ?>
            <?php foreach ($productsInCart as $id => $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['title']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td><img src="cid:img_embedded_<?= $id ?>"></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <h1><?= translateLabels('No products selected') ?></h1>
        <?php endif; ?>
    </table>

</body>
</html>
