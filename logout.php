<?php

session_start();

if (isset($_SESSION['admin_logged_in'])) {
    unset($_SESSION['admin_logged_in']);
}

header('Location: index.php');
die();
