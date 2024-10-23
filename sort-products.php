<?php

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not permitted');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sort']) && in_array($_POST['sort'], $sortOptions)) {
    switch ($_POST['sort']) {
        case 'title':
            $_SESSION['sort'] = 'title';
            break;
        case 'price':
            $_SESSION['sort'] = 'price';
            break;
        case 'description':
            $_SESSION['sort'] = 'description';
            break;
        case 'none':
            $_SESSION['sort'] = 'none';
            break;
    }
    header('Location: products.php');
    die();
}
?>


<form method="post">
    <input type="hidden" name="sortProducts" value="sort products">

    <label for="sort"><?= translateLabels('Sort by:') ?></label>
    <select name="sort" id="sort">
        <option value="none"><?= translateLabels('Nothing') ?></option>

        <option value="title"<?= ($_SESSION['sort'] === 'title') ? "selected" : "" ?>>
            <?= translateLabels("Name") ?>
        </option>

        <option value="price"<?= ($_SESSION['sort'] === 'price') ? "selected" : "" ?>>
            <?= translateLabels("Price") ?>
        </option>

        <option value="description"<?= ($_SESSION['sort'] === 'description') ? "selected" : "" ?>>
            <?= translateLabels("Description") ?>
        </option>
    </select>

    <input type="submit" value="<?= translateLabels('Sort')?>">
</form>
