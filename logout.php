<?php

session_start();

if (isset($_SESSION['admin_logged'])) {
    unset($_SESSION['admin_logged']);
    unset($_SESSION['products']);
}

header('Location: index.php');
die();
