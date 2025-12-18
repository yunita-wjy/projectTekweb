<?php
session_start();
require("../config/connection.php");

$user = $_SESSION['user'] ?? null;

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM movies WHERE movie_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $movie ? htmlspecialchars($movie['title']) : 'Movie Detail' ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../style.css">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
    </style>
</head>

<body>


<?php include("../includes/header.php"); ?>

<main class="container my-5">
<?php if (!$movie): ?>
    <p>Movie not found</p>
<?php else: ?>
    <div class="row">
        <div class="col-md-4">
            <img src="../<?= htmlspecialchars($movie['poster_path']) ?>"
                 class="img-fluid rounded shadow">
        </div>
        <div class="col-md-8">
            <h1><?= htmlspecialchars($movie['title']) ?></h1>
            <p><?= nl2br(htmlspecialchars($movie['synopsis'])) ?></p>

            <a href="seats.php?movie_id=<?= $movie['movie_id'] ?>"
               class="btn btn-dark mt-3">
                Continue to Payment
            </a>
        </div>
    </div>
<?php endif; ?>
</main>


<?php include("../includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
