<?php
session_start();
require_once "../config/connection.php";
require_once "../classes/movie.php";
// require "../config/cek_login.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit();
}
$user = $_SESSION['user'];
$movieObj = new movie($conn);
$nowShowing = $movieObj->getNowShowing();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Movies - Film Verse</title>
    <!-- favicon -->
    <link href="../assets/filmVerse-light.png" rel="icon" media="(prefers-color-scheme: light)" />
    <link href="../assets/filmVerse-dark.png" rel="icon" media="(prefers-color-scheme: dark)" />
    <!-- icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- sweet alert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="../style.css?v=2">
    <script src="../script.js"></script>
</head>
<body>
<?php include "../includes/header.php"; ?>
<div class="mt-5 pt-4">
    <main class="container my-4 mt-5">
        <div class="row align-items-center mb-5 hero-banner">
            <div class="col-md-8">
                <h1 class="display-4 fw-bold text-warning">Avengers: Secret Wars</h1>
                <p class="lead">Earth's mightiest heroes must band together once again to save the multiverse from total collapse.</p>
                <button class="btn btn-danger btn-lg rounded-pill px-4">Watch Trailer</button>
            </div>
            <div class="col-md-4 text-center">
                <img src="https://via.placeholder.com/300x450?text=HOT+MOVIE" class="img-fluid rounded shadow border border-warning">
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4" id="now-showing">
            <h2 class="fw-bold border-start border-4 border-warning ps-3">MOVIES</h2>
            <div class="input-group w-25 d-none d-md-flex">
                <input type="text" class="form-control" placeholder="Search film...">
                <button class="btn btn-dark"><i class="fa fa-search"></i></button>
            </div>
        </div>

        <div class="mb-4 text-center">
            <div class="btn-group" role="group">
                <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                <label class="btn btn-outline-dark px-4 rounded-pill me-2" for="btnradio1">Now Showing</label>

                <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                <label class="btn btn-outline-dark px-4 rounded-pill" for="btnradio2">Coming Soon</label>
            </div>
        </div>

        <div class="row row-cols-2 row-cols-md-4 g-4">

            <?php foreach($nowShowing as $film) { ?>
                <div class="col">
                    <div class="movie-card" onclick="window.location.href='movies_detail.php?id=<?= $film['movie_id'] ?>'">
                        <img src="../<?= $film['poster_path'] ?>" class="w-100" style="height: 380px; object-fit: cover;">

                        <div class="movie-overlay">
                            <button class="btn btn-warning rounded-pill fw-bold px-4">Beli Tiket</button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <strong class="text-truncate"><?= $film['title'] ?></strong>
                        <span class="badge bg-secondary"><?= $film['duration'] ?>m</span>
                    </div>
                </div>
            <?php } ?>

        </div>
    </main>
</div>

    <footer class="bg-light text-center py-4 border-top">
        <div class="container">
            <div class="mb-2">
                <a href="#" class="text-dark mx-2 text-decoration-none">About Us</a> |
                <a href="#" class="text-dark mx-2 text-decoration-none">Our Team</a> |
                <a href="#" class="text-dark mx-2 text-decoration-none">Contact</a>
            </div>
            <div class="mb-3">
                <i class="fab fa-instagram mx-2 fs-4"></i>
                <i class="fab fa-youtube mx-2 fs-4"></i>
                <i class="fab fa-facebook mx-2 fs-4"></i>
                <i class="fab fa-twitter mx-2 fs-4"></i>
            </div>
            <p class="text-muted small">Copyright © 2025 Kelompok 8. All Rights Reserved.</p>
        </div>
    </footer>
</body>

</html>