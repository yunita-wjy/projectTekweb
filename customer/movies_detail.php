<?php
session_start();
$movieId = $_GET['id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Movie Detail | FilmVerse</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-4">

    <a href="movies.php" class="btn btn-link mb-3">← Back to Movies</a>

    <div class="row">
        <div class="col-md-4">
            <img src="../assets/film<?= $movieId ?: 1 ?>.jpg" class="img-fluid rounded">
        </div>

        <div class="col-md-8">
            <h1>Film <?= htmlspecialchars($movieId) ?></h1>
            <p><strong>Genre:</strong> Action, Adventure</p>
            <p>Halaman detail film. Data nantinya diambil dari database.</p>

            <hr>

            <h5>Book Ticket</h5>

            <div class="mb-3">
                <label>Tanggal</label>
                <select class="form-select w-50">
                    <option>28 Nov 2025</option>
                    <option>29 Nov 2025</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Jam Tayang</label><br>
                <button class="btn btn-outline-dark btn-sm me-2">13:00</button>
                <button class="btn btn-outline-dark btn-sm me-2">16:30</button>
                <button class="btn btn-outline-dark btn-sm">19:00</button>
            </div>

            <button class="btn btn-dark w-100"
                onclick="window.location.href='ticket.php?id=<?= $movieId ?>'">
                Continue to Payment
            </button>
        </div>
    </div>

</div>

</body>
</html>
