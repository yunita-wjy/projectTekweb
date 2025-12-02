<?php
    session_start();
    require "../config/connection.php";
    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer'){
        header("Location: /cinema/auth/login.php");
        exit();
    }
?>