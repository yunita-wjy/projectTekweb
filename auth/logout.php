<?php
    // proses logout
    session_start();
    // hapus semua session
    unset($_SESSION['user']);
    session_destroy();
    // redirect ke login.php
    header('Location: ../index.php?logout=success');
?>