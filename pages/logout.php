<?php
    session_start();

    $redirectPage = $_GET['redirect'] ?? 'login.php';

    session_unset();
    session_destroy();
    session_reset();

    header("Location: $redirectPage");
    exit();
?>
