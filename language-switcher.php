<?php 

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not permitted');
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["languageForm"]) && isset($_POST["language"])
    && $_POST["languageForm"] === "language form" && in_array($_POST["language"], ['RO', 'ENG'])) {
    $_SESSION["language"] = $_POST["language"];
}
?>

<form method="POST">
    <input type="hidden" name="languageForm" value="language form">
    <label for="language"><?= translateLabels("Choose a language:") ?></label>
    <select name="language" id="language">

        <?php if ($_SESSION["language"] === "ENG"): ?>
            <option value="ENG" selected> <?= translateLabels(label: "english") ?></option>
        <?php else: ?>
            <option value="ENG"> <?= translateLabels(label: "english") ?></option>
        <?php endif; ?>

        <?php if ($_SESSION["language"] === "RO"): ?>
            <option value="RO" selected><?= translateLabels("romanian") ?></option>
        <?php else: ?>
            <option value="RO"><?= translateLabels("romanian") ?></option>
        <?php endif; ?>
        
    </select>
    <input type="submit" value=" <?= translateLabels("Change language")?>">
</form>
<br>
