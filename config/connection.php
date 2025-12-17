<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "cinemadb";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
} catch (PDOException $e) {
    // attempt to retry the connection after some timeout for example
    echo 'Koneksi database gagal';
    exit();
}

// echo 'koneksi sukses';

require('dbConnect.php');
$database = new dbConnection('cinemadb');

?>