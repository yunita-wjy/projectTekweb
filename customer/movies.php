<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Movies | FilmVerse</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-4">

    <a href="../index.php" class="btn btn-link mb-3">← Back to Home</a>
    <h1 class="mb-4">Movies</h1>

    <div class="row g-4">

        <div class="col-md-3">
            <div class="card shadow-sm">
                <img src="../assets/film1.jpg" class="card-img-top">
                <div class="card-body text-center">
                    <h6>Film 1</h6>
                    <a href="movies_detail.php?id=1" class="btn btn-dark btn-sm">View Detail</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <img src="../assets/film2.jpg" class="card-img-top">
                <div class="card-body text-center">
                    <h6>Film 2</h6>
                    <a href="movies_detail.php?id=2" class="btn btn-dark btn-sm">View Detail</a>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
