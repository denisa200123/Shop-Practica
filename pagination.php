<?php

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not permitted');
}

require_once 'common.php';

?>

<?php if ($maxPages > 0): ?>
    <?php if ($page > 1): ?>
        <?= createPageLink($page - 1, translateLabels('Previous')) ?>
    <?php endif; ?>

    <?php if ($page > 3): ?>
        <?= createPageLink(1) ?>
        ...
    <?php endif; ?>

    <?php if ($page - 2 > 0): ?>
        <?= createPageLink($page - 2) ?>
    <?php endif; ?>

    <?php if ($page - 1 > 0): ?>
        <?= createPageLink($page - 1) ?>
    <?php endif; ?>

    <?= $page ?> <!-- current page-->

    <?php if ($page + 1 <= $maxPages): ?>
        <?= createPageLink($page + 1) ?>
    <?php endif; ?>

    <?php if ($page + 2 <= $maxPages): ?>
        <?= createPageLink($page + 2) ?>
    <?php endif; ?>

    <?php if ($page < $maxPages - 2): ?>
        ...
        <?= createPageLink($maxPages) ?>
    <?php endif; ?>

    <?php if ($page < $maxPages): ?>
        <?= createPageLink($page + 1, translateLabels('Next')) ?>
    <?php endif; ?>
<?php endif; ?>
