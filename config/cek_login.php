<?php
    session_start();
    // apakah tidak ada login?
    if(!isset($_SESSION['username'])) {
        header('Location: ../customer/loginUI.php'); //kalau tidak ada session, kembali ke login
    }
?>