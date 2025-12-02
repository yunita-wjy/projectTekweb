<?php
    session_start();
    require("../config/connection.php");

    if(isset($_POST['login'])){
        $email    = $_POST['email'];
        $password = $_POST['password'];

        $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        $user   = mysqli_fetch_assoc($result);

        if($user && password_verify($password, $user['password'])){
            $_SESSION['user'] = $user;

            // cek role user
            if($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php"); // ke dashboard admin
            } else {
                header("Location: ../index.php"); // ke homepage customer
            }
            exit();

        } else {
            echo "Email atau password salah!";
        }
    }
?>