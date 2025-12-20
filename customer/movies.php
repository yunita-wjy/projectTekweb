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
$basePath = '/ProjectTekweb/';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Movies - Film Verse</title>
    <?php include '../includes/head.php'; ?>
</head>

<body>
    <?php include "../includes/header.php"; ?>
    <main class="container my-4 mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4" id="now-showing">
            <h2 class="fw-bold section-title">MOVIES</h2>
            <div class="input-group w-25 d-none d-md-flex">
                <input type="text" class="form-control" placeholder="Search film...">
                <button class="btn btn-dark"><i class="fa fa-search"></i></button>
            </div>
        </div>

        <div class="mb-4 text-center">
            <div class="btn-group" role="group">
                <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                <label class="btn filmverse-toggle px-4 rounded-pill me-2" for="btnradio1">Now Showing</label>

                <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                <label class="btn filmverse-toggle px-4 rounded-pill me-2" for="btnradio2">Coming Soon</label>
            </div>
        </div>

        <div class="row row-cols-2 row-cols-md-4 g-3">

            <?php foreach ($nowShowing as $film) { ?>
                <div class="col">
                    <div class="movie-wrapper">
                        <div class="movie-card" onclick="window.location.href='movies_detail.php?id=<?= $film['movie_id'] ?>'">
                            <img src="../<?= $film['poster_path'] ?>" class="w-100" style="height: 380px; object-fit: cover;">

                            <div class="movie-overlay">
                                <div class="overlay-bg"></div>
                                <button class="btn filmverse-btn rounded-pill fw-bold px-4">Beli Tiket</button>
                            </div>
                        </div>

                        <div class="movie-info mt-3">
                            <span class="badge filmverse-badge"><?= $film['duration'] ?>m</span>
                            <h6 class="movie-title"><?= $film['title'] ?></h6>
                        </div>
                    </div>

                </div>
            <?php } ?>

        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/javascript/script.js"></script>
</body>

</html>