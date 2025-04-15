<?php

session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    die();    
}

require_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['productToSearch'])) {
    $productName = strip_tags($_GET['productToSearch']);
    $productName = filter_var($productName, FILTER_SANITIZE_STRING);
    $productName = htmlspecialchars_decode($productName);
    $productName = '%' . $productName . '%';
    $productName = strtolower($productName);

    $query = "SELECT * FROM products WHERE lower(title) LIKE :name";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $productName);
    $stmt->execute();
    //get number of products from db that match the product name
    $nrOfProducts = $stmt->fetchColumn();

    $productsPerPage = 1; //how many products to display per page
    $maxPages = ceil($nrOfProducts/$productsPerPage); // maximum number of pages

    //get page from url, default 1
    $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page']>0 && $_GET['page']<=$maxPages ? $_GET['page'] : 1;

    $currentPage = ($page - 1) * $productsPerPage;

    $sortOptions = ['none', 'title', 'price', 'description'];
    $sort = isset($_SESSION['sort']) && in_array($_SESSION['sort'], $sortOptions) ? $_SESSION['sort'] : 'none';

    if ($sort !== 'none') {
        $query .= " ORDER BY $sort";
    }

    $query .= " LIMIT :start, :stop";

    $stmt = $pdo->prepare($query);

    $start = intval(trim($currentPage));
    $stop =  intval(trim($productsPerPage));
    $stmt->bindParam(':name', $productName);
    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $stmt->bindParam(':stop', $stop, PDO::PARAM_INT);
    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $_SESSION['foundProducts'] = $products;
    $stmt = null;
    $pdo = null;

    header("Location: products.php?productToSearch=$productToSearch");
} else {
    header('Location: products.php');
}
die();
