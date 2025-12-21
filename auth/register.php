<?php
    session_start();
    require("../config/connection.php");

    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];

    // cek username atau email sudah ada
    $check = $conn->prepare("
        SELECT user_id 
        FROM users 
        WHERE username = ? OR email = ?
    ");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // simpan flash message
        $_SESSION['flash_error'] = "Username atau Email sudah terdaftar";
        header("Location: ../customer/signupUI.php");
        exit();
    }

    // hash password
    $hash_password = password_hash($password, PASSWORD_DEFAULT);

    // insert user baru
    $insert = $conn->prepare("
        INSERT INTO users (username, full_name, email, phone, password)
        VALUES (?, ?, ?, ?, ?)
    ");
    $insert->bind_param(
        "sssss",
        $username,
        $fullname,
        $email,
        $phone,
        $hash_password
    );

    $insert->execute();

    // flash success
    $_SESSION['flash_success'] = "Akun berhasil dibuat, silakan login";
    header("Location: ../customer/loginUI.php");
    exit();
?>