<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "cinemadb";

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


?>