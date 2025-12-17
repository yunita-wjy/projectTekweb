<?php
    session_start();

    /* DUMMY LOGIN – DEVELOPMENT ONLY */
    $_SESSION['user'] = [
        'user_id'   => 1,
        'username'  => 'admin',
        'full_name' => 'Sarah',
        'role'      => 'admin'
    ];

    header("Location: ../admin/dashboard.php");
    exit();

?>