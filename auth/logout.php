<?php
    // proses logout
    session_start();
    // hapus semua session
    unset($_SESSION['user']);
    session_destroy();
    // redirect ke login.php
    header('Location: ../customer/loginUI.php?logout=success');
?>