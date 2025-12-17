<?php
session_start();
require("../config/connection.php");


    $email    = $_POST['email'];
    $password = $_POST['password'];

    $user = $database->attemp($email, $password);

    if ($user) {
        $_SESSION['user'] = $user;

        // cek role user
        if ($user['role'] == 'admin') {
            header("Location: ../admin/dashboard.php"); // ke dashboard admin
        } else {
            header("Location: ../index.php"); // ke homepage customer
        }
        exit();
    } else {
        header("Location: ../customer/loginUI.php?msg=failed"); // failed 
        exit();
    }

?>