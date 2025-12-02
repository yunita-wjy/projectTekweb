<?php
    session_start();
    require "../config/connection.php";
    require "../includes/admin_auth.php";
    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
        header("Location: ../auth/login.php");
        exit();
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Admin Dashboard</title>
    </head>
    <body>
        <h1>Welcome, Admin</h1>
    </body>

</html>
