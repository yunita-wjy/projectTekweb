<?php
   session_start();

    // hapus semua data session
    $_SESSION = [];
    session_unset();
    session_destroy();

    // redirect ke homepage
    header("Location: ../index.php?logout=success");
    exit();
?>