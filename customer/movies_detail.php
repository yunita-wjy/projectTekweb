<?php
session_start();
require_once "../config/connection.php";
require_once "../classes/movie.php";

$id = $_GET['id'];
$movieObj = new movie($conn);
$detail = $movieObj->getMovieById($id);
?>

<!DOCTYPE html>
<html lang="id">
<?php include "includes/header.php"; ?>

<body>
    <?php include "includes/navbar.php"; ?>

    <main class="container my-4">
        
        <a href="movies.php" class="btn btn-link mb-3 ps-0 text-dark text-decoration-none fw-bold">← Back to Movies</a>
        
        <div class="ratio ratio-21x9 bg-dark mb-4 rounded shadow">
            <div class="d-flex align-items-center justify-content-center h-100 text-white">
                <div class="text-center">
                    <h1><i class="fa fa-play-circle display-1 text-warning"></i></h1>
                    <p>Trailer Preview</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <img src="../<?= $detail['poster_path'] ?>" class="img-fluid rounded shadow w-100">
            </div>

            <div class="col-md-9">
                <h1 class="fw-bold"><?= $detail['title'] ?></h1>
                <p class="text-muted">Genre: Action, Adventure | Duration: <?= $detail['duration'] ?> min</p>
                
                <h5 class="mt-4">Synopsis</h5>
                <p class="text-secondary"><?= $detail['synopsis'] ?></p>
                <hr>
                
                <div class="card bg-light border-0 p-4 mt-4 shadow-sm">
                    <h5 class="fw-bold mb-3">Book Tickets</h5>
                    
                    <form action="seats.php" method="GET">
                        <input type="hidden" name="movie_id" value="<?= $detail['movie_id'] ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Select Date</label>
                                <select class="form-select">
                                    <option>28 Nov 2025</option>
                                    <option>29 Nov 2025</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Showtime</label><br>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="time" id="t1" checked>
                                    <label class="btn btn-outline-dark" for="t1">12:00</label>
                                    
                                    <input type="radio" class="btn-check" name="time" id="t2">
                                    <label class="btn btn-outline-dark" for="t2">15:30</label>
                                    
                                    <input type="radio" class="btn-check" name="time" id="t3">
                                    <label class="btn btn-outline-dark" for="t3">19:00</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 fw-bold py-2 mt-2">
                            CONTINUE TO SEATS
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include "includes/footer.php"; ?> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>