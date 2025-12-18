<?php
session_start();
require("../config/connection.php");

$email    = $_POST['email'];
$password = $_POST['password'];

// ambil user berdasarkan email
$query = $conn->prepare("SELECT * FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();

$result = $query->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
        'user_id'  => $user['user_id'],
        'username' => $user['username'],
        'email'    => $user['email'],
        'role'     => $user['role']
    ];
    // cek role
    if ($user['role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../index.php");
    }
    exit();
} else {
    header("Location: ../customer/loginUI.php?msg=failed");
    exit();
}


?>