<?php

session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    die();
}

require_once 'common.php';

$sortOptions = ['none', 'title', 'price', 'description'];

$sort = isset($_GET['sort']) && in_array($_GET['sort'], $sortOptions) ? $_GET['sort'] : '';
$search = isset($_GET['search']) ? '%' . strtolower(strip_tags($_GET['search'])) . '%' : '%';

$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int) $_GET['page'] : 1; //get page from url, default 1

$productsPerPage = 2; //how many products to display per page
$currentPage = ($page - 1) * $productsPerPage;
$productsPerPage =  intval(trim($productsPerPage));
$currentPage = intval(trim($currentPage));

$orderBy = in_array($sort, ['title', 'price', 'description']) ? $sort : null;

$query = "SELECT * FROM products";

if ($search !== '%') {
    $query .= " WHERE lower(title) LIKE :search";
}

if ($orderBy) {
    $query .= " ORDER BY $orderBy";
}

$query .= " LIMIT :currentPage, :productsPerPage";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':currentPage', $currentPage, PDO::PARAM_INT);
$stmt->bindParam(':productsPerPage', $productsPerPage, PDO::PARAM_INT);
if ($search !== '%') {
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
}

$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$getNumberQuery = "SELECT COUNT(*) FROM products WHERE lower(title) LIKE :search";
$getNumberStmt = $pdo->prepare($getNumberQuery);
$getNumberStmt->bindParam(':search', $search, PDO::PARAM_STR);
$getNumberStmt->execute();
$nrOfProducts = $getNumberStmt->fetchColumn();

$maxPages = ceil($nrOfProducts / $productsPerPage);

$stmt = null;
$pdo = null;

function createPageLink($pageNum, $text = null)
{
    $text = $text ?? $pageNum;
    $search = $_GET['search'] ?? '';
    $sort = $_GET['sort'] ?? 'none';

    return "<a href='products.php?page=$pageNum&search=$search&sort=$sort'>$text</a>";
}

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
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
        <input type="text" name="search" id="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <input type="submit" value="<?= translateLabels('Search') ?>">
    </form>

    <br><br>

    <!-- sort by property -->
    <form method="get">
        <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <label for="sort"><?= translateLabels('Sort by:') ?></label>

        <select name="sort" id="sort">
            <option value="none" <?= ($sort === 'none') ? "selected" : '' ?>>
                <?= translateLabels('Nothing') ?>
            </option>

            <option value="title"<?= ($sort === 'title') ? "selected" : '' ?>>
                <?= translateLabels("Name") ?>
            </option>

            <option value="price"<?= ($sort === 'price') ? "selected" : '' ?>>
                <?= translateLabels("Price") ?>
            </option>

            <option value="description"<?= ($sort === 'description') ? "selected" : '' ?>>
                <?= translateLabels("Description") ?>
            </option>
        </select>

        <input type="submit" value="<?= translateLabels('Sort') ?>">
    </form>

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
