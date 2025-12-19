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

    // cek domain email, kalau @filmverse.ac.id set role jadi admin
    $role = $user['role'];
    if (substr($email, -16) === "@filmverse.ac.id") {
        $role = 'admin';
    }

    $_SESSION['user'] = [
        'user_id'  => $user['user_id'],
        'username' => $user['username'],
        'full_name' => $user['full_name'],
        'email'    => $user['email'],
        'role'     => $role
    ];

    // redirect berdasarkan role
    if ($role === 'admin') {
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
