<?php

session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    die();    
}

require_once 'common.php';

if (!isset($_SESSION['sort'])) {
    $_SESSION['sort'] = '';
}

$sort = isset($_SESSION['sort']) && in_array($_SESSION['sort'], $sortOptions) ? $_SESSION['sort'] : 'none';

$productsPerPage = 1; //how many products to display per page

$searchProduct = isset($_GET['productToSearch']) ? '%' . strtolower(strip_tags($_GET['productToSearch'])) . '%' : '%';

//get page from url, default 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int) $_GET['page'] : 1;
$currentPage = ($page - 1) * $productsPerPage;

$query = "SELECT * FROM products WHERE lower(title) LIKE :searchProduct";

if ($sort !== 'none') {
    $query .= " ORDER BY $sort";
}

$currentPage = intval(trim($currentPage));
$productsPerPage =  intval(trim($productsPerPage));

$query .= " LIMIT :currentPage, :productsPerPage";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':searchProduct', $searchProduct, PDO::PARAM_STR);
$stmt->bindParam(':currentPage', $currentPage, PDO::PARAM_INT);
$stmt->bindParam(':productsPerPage', $productsPerPage, PDO::PARAM_INT);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$getNumberQuery = "SELECT COUNT(*) FROM products WHERE lower(title) LIKE :searchProduct";
$getNumberStmt = $pdo->prepare($getNumberQuery);
$getNumberStmt->bindParam(':searchProduct', $searchProduct, PDO::PARAM_STR);
$getNumberStmt->execute();
$nrOfProducts = $getNumberStmt->fetchColumn();

$maxPages = ceil($nrOfProducts / $productsPerPage);

$stmt = null;
$pdo = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translateLabels('Products') ?></title>
    <style>
        img {
            width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <?php require_once 'language-switcher.php'; ?>

    <span><?= translateLabels('Admin logged in') ?></span>
    <br>
    <span><?= translateLabels('Want to logout?') ?></span>
    <a href="logout.php"><?= translateLabels('Logout') ?></a>
    <br><br>

    <!-- add product -->
    <a href="product.php"><?= translateLabels('Add a product') ?></a>

    <!-- search product -->
    <br><br>
    <span><?= translateLabels('Looking for a product?') ?></span>
    <form method="get">
        <input type="text" name="productToSearch" id="productToSearch">
        <input type="submit" value="<?= translateLabels('Search') ?>">
    </form>
    <br><br>

    <!-- sort by property -->
    <?php require_once 'sort-products.php'; ?>

    <!-- display the products -->
    <table border="1" cellpadding="10">
        <tr>
            <th><?= translateLabels('Name') ?></th>
            <th><?= translateLabels('Price') ?></th>
            <th><?= translateLabels('Description') ?></th>
            <th><?= translateLabels('Image') ?></th>
            <th><?= translateLabels('Edit') ?></th>
            <th><?= translateLabels('Remove') ?></th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['title']) ?></td>
                <td><?= htmlspecialchars($product['price']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td><img src="img/<?= htmlspecialchars($product['image']) ?>"></td>
                <td>
                    <form method="get" action="edit-product.php">
                        <input type="hidden" name="productId" value="<?= htmlspecialchars($product['id']) ?>">
                        <input type="submit" value="<?= translateLabels('Edit') ?>">
                    </form>
                </td>
                <td>
                    <form method="post" action="delete-product.php">
                        <input type="hidden" name="productId" value="<?= htmlspecialchars($product['id']) ?>">
                        <input type="submit" value="<?= translateLabels('Remove') ?>">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <!-- pagination -->
    <?php require_once 'pagination.php'; ?>

    <br><br>
    <br>
    <a href="index.php"><?= translateLabels('Go to main page') ?></a>
</body>
</html>