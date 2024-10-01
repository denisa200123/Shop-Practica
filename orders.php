<?php
require 'common.php';
session_start();

$query = "SELECT * FROM orders;";
$stmt = $pdo->prepare($query);
$stmt->execute();

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
</head>
<body>
    <?php include_once "language-switcher.php"; ?>

    <?php if (isset($_SESSION['admin_logged'])): ?>

        <?php if (!empty($orders)): ?>
            <!-- display the orders -->
            <table border="1" cellpadding="10">
                <tr>
                    <th><?= ('Id') ?></th>
                    <th><?= translateLabels('Date') ?></th>
                </tr>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['creation_date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

        <?php else: ?>
            <?= translateLabels('No orders'); ?>
        <?php endif; ?>

        <br><br>
        <a href="index.php"><?= translateLabels('Go to main page'); ?></a>

    <?php else: ?>
        <?php header("Location: index.php"); ?>
        <?php die(); ?>
    <?php endif; ?>
</body>
</html>
