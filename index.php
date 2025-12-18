<?php
session_start();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    $user = null;
}

require "config/connection.php";

$nowShowing = mysqli_query($conn, "
    SELECT DISTINCT
        m.movie_id,
        m.title,
        m.poster_path
    FROM showtimes s
    JOIN movies m ON s.movie_id = m.movie_id
    WHERE 
        m.status = 'active'
        AND CURDATE() BETWEEN m.start_date AND m.end_date

");

?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FILM VERSE</title>

    <!-- BOOTSTRAP FIX -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f8f9fa;
        }
        footer {
            margin-top: auto;
        }

        .movie-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            cursor: pointer;
        }

        .movie-card img {
            width: 100%;
            transition: 0.3s;
        }

        .movie-card:hover img {
            transform: scale(1.05);
        }

        .movie-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: 0.3s;
        }

        .movie-card:hover .movie-overlay {
            opacity: 1;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="assets/filmVerse-dark.png" width="40" class="me-2">
            <span class="fw-bold text-white">FilmVerse</span>
        </a>

        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item">
                <a class="nav-link active" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Movies</a>
            </li>

            <?php if ($user): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="fa-regular fa-user me-1"></i>
                        <?= htmlspecialchars($user['username'] ?? 'User') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="customer/profile.php">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="auth/logout.php">Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="customer/loginUI.php">Login</a>
                </li>
            <?php endif; ?>
        </ul>

    </div>
</nav>



<main class="container my-4">

    <!-- HERO -->
    <div class="hero-container">
        <div class="row align-items-center mb-5 bg-dark text-white p-4 rounded shadow">
            <div class="col-md-8">
                <h1 class="display-4 fw-bold">Avengers: Secret Wars</h1>
                <p class="lead">
                    Earth's mightiest heroes must band together once again.
                </p>
                <button class="btn btn-danger btn-lg">Watch Now</button>
            </div>
            <div class="col-md-4 text-center">
                <img src="https://via.placeholder.com/300x450"
                     class="img-fluid rounded shadow">
            </div>
        </div>      
    </div>


    <!-- NOW SHOWING -->
    <h3 class="mb-3">Now Showing</h3>

        <div class="row row-cols-2 row-cols-md-4 g-4">

        <?php if (mysqli_num_rows($nowShowing) > 0): ?>
            <?php while ($movie = mysqli_fetch_assoc($nowShowing)): ?>
                <div class="col">
                    <div class="movie-card">
                        <img 
                            src="<?= htmlspecialchars($movie['poster_path'] ?? 'assets/movie_poster/default.jpg') ?>"
                            alt="<?= htmlspecialchars($movie['title']) ?>">
                        <div class="movie-overlay">
                            <a href="customer/movies_detail.php?id=<?= $movie['movie_id'] ?>" 
                            class="btn btn-warning">
                                Beli Tiket
                            </a>
                        </div>
                    </div>
                    <p class="mt-2 text-center fw-bold">
                        <?= htmlspecialchars($movie['title']) ?>
                    </p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center text-muted">
                    Belum ada film yang sedang tayang.
                </p>
            </div>
        <?php endif; ?>

        </div>


</main>

<footer class="bg-light text-center py-4 border-top">
    <p class="text-muted small">
        © 2025 Kelompok 8
    </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
