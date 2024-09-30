<?php if ($maxPages > 0): ?>
    <?php if ($page > 1): ?>
        <a href="products.php?page=<?php echo $page - 1 ?>">Prev</a>
    <?php endif; ?>

    <?php if ($page > 3): ?>
        <a href="products.php?page=1">1</a>
        ...
    <?php endif; ?>

    <?php if ($page - 2 > 0): ?>
        <a href="products.php?page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
    <?php endif; ?>

    <?php if ($page - 1 > 0): ?>
        <a href="products.php?page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
    <?php endif; ?>

    <a href="products.php?page=<?php echo $page ?>"><?php echo $page ?></a>

    <?php if ($page + 1 < $maxPages + 1): ?>
        <a href="products.php?page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
    <?php endif; ?>

    <?php if ($page + 2 < $maxPages + 1): ?>
        <a href="products.php?page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
    <?php endif; ?>

    <?php if ($page < $maxPages - 2): ?>
        ...
        <a href="products.php?page=<?php echo $maxPages ?>"><?php echo $maxPages ?></a>
    <?php endif; ?>

    <?php if ($page < $maxPages): ?>
        <a href="products.php?page=<?php echo $page + 1 ?>">Next</a>
    <?php endif; ?>
<?php endif; ?>
