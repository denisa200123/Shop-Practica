<?php 

if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'en';
}

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not permitted');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['language'])
    && in_array($_POST['language'], array_keys($locales))) {
    $_SESSION['language'] = $_POST['language'];
}
?>

<form method="POST"> 
    <label for="language"><?= translateLabels('Choose a language:') ?></label>
    <select name="language" id="language">
        <?php foreach($locales as $code => $locale): ?>
            <option value="<?= htmlspecialchars($code) ?>"

            <?php if ($_SESSION['language'] === $code): ?>
                selected
            <?php endif; ?>
            
            ><?= translateLabels(htmlspecialchars($locale)) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="<?= translateLabels('Change language') ?>">
</form>
<br>
