<?php
    require("../config/connection.php");

    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $phone  = $_POST['phone'];
    $password = $_POST['password'];

    $hash_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (`username`, `full_name`, `email`, `phone`, `password`) 
    VALUES ('$username', '$fullname', '$email', '$phone', '$hash_password')";
    $conn->query($query);

    header('Location: ../customer/signupUI.php?signup=success');
    exit();
?>