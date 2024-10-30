<?php

session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    die();    
}

require_once 'common.php';

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
    <title><?= translateLabels('Orders') ?></title>
</head>
<body>
    <?php require_once 'language-switcher.php'; ?>

    <?php if ($orders): ?>
        <table border="1" cellpadding="10">
            <tr>
                <th><?= translateLabels('Order id') ?></th>
                <th><?= translateLabels('Date') ?></th>
                <th><?= translateLabels('Customer name') ?></th>
                <th><?= translateLabels('Contact details') ?></th>
                <th><?= translateLabels('Comments') ?></th>
                <th><?= translateLabels('Total price') ?></th>
                <th><?= translateLabels('Products') ?></th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['id']) ?></td>
                    <td><?= htmlspecialchars($order['creation_date']) ?></td>
                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                    <td><?= htmlspecialchars($order['contact_details']) ?></td>
                    <td><?= htmlspecialchars($order['comments']) ?></td>
                    <td><?= htmlspecialchars($order['total_price']) ?></td>
                    <td>
                        <form method="get" action="order.php">
                            <input type="hidden" name="orderId" value="<?= htmlspecialchars($order['id']) ?>">
                            <input type="submit" value="<?= translateLabels('See products') ?>">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <h1><?= translateLabels('No orders') ?></h1>
    <?php endif; ?>

    <a href="index.php"><?= translateLabels('Go to main page') ?></a>
</body>
</html>
