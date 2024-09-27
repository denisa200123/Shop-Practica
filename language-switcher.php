<?php 

if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["languageForm"]) && isset($_POST["language"])
    && $_POST["languageForm"] === "language form" && in_array($_POST["language"], ['RO', 'ENG'])) {
    $_SESSION["language"] = $_POST["language"];
}
?>

<form method="POST">
    <input type="hidden" name="languageForm" value="language form">
    <label for="language"><?= translateLabels("Choose a language:") ?></label>
    <select name="language" id="language">
        <option value="ENG"> <?= translateLabels(label: "english") ?></option>
        <option value="RO"><?= translateLabels("romanian") ?></option>
    </select>
    <input type="submit" value=" <?= translateLabels("Change language")?>">
</form>
<br>
