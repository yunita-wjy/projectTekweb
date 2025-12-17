<?php
session_start();
require_once "../config/connection.php";
require_once "../classes/movie.php";

$movieObj = new movie($conn);
$nowShowing = $movieObj->getNowShowing();
?>

<!DOCTYPE html>
<html lang="id">
<?php include "includes/header.php"; ?>

<body>

    <?php include "includes/navbar.php"; ?>

    <main class="container my-4">

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
            
            <?php while($film = mysqli_fetch_assoc($nowShowing)) { ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>