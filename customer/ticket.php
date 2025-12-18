<?php
    session_start();    
    require "../config/connection.php";

    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer'){
        header("Location: ../auth/login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ticket | FilmVerse</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2>Ticket Summary</h2>
    <p>Movie ID: <?= htmlspecialchars($movieId) ?></p>
    <p>Halaman ringkasan tiket sebelum pembayaran.</p>

    <a href="../index.php" class="btn btn-secondary">Back to Home</a>
</div>

</body>
</html>