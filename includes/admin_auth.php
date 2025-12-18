<?php
    session_start();

    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header("Location: ../auth/dummy_admin_login.php");
        exit();
    }

?>