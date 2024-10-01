<?php

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not permitted');
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["sortProducts"]) && isset($_POST["sort"])
&& $_POST["sortProducts"] === "sort products" && in_array($_POST["sort"], ['none', 'name', 'price', 'description'])) {
    switch ($_POST['sort']) {
        case "name":
            $_SESSION["sort"] = "title";
            break;
        case "price":
            $_SESSION["sort"] = "price";
            break;
        case "description":
            $_SESSION["sort"] = "description";
            break;
        case "none":
            $_SESSION["sort"] = "none";
            break;
    }
    header("Location: products.php");
    die();
}
?>

<form method="post">
    <input type="hidden" name="sortProducts" value="sort products">

    <label for="sort"><?= translateLabels("Sort by:") ?></label>
    <select name="sort" id="sort">
        <option value="none"> <?= translateLabels(label: "Nothing") ?></option>

        <?php if ($_SESSION["sort"] === "title"): ?>
            <option value="name" selected> <?= translateLabels(label: "Name") ?></option>
        <?php else: ?>
            <option value="name"> <?= translateLabels(label: "Name") ?></option>
        <?php endif; ?>

        <?php if ($_SESSION["sort"] === "price"): ?>
            <option value="price" selected><?= translateLabels("Price") ?></option>
        <?php else: ?>
            <option value="price"><?= translateLabels("Price") ?></option>
        <?php endif; ?>

        <?php if ($_SESSION["sort"] === "description"): ?>
            <option value="description" selected><?= translateLabels("Description") ?></option>
        <?php else: ?>
            <option value="description"><?= translateLabels("Description") ?></option>
        <?php endif; ?>
    </select>

    <input type="submit" value=" <?= translateLabels("Sort")?>">
</form>
