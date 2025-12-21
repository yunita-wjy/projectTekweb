<?php
    session_start();

    $BASE_PATH = '/proyek/projectTekweb/';

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
    } else {
        $user = null;
    }

    require "config/connection.php";

    // Ambil film now showing di db
    $nowShowing = mysqli_query($conn, "
        SELECT DISTINCT
            m.movie_id,
            m.title,
            m.poster_path,
            m.duration
        FROM showtimes s
        JOIN movies m ON s.movie_id = m.movie_id
        WHERE 
            m.status = 'active'
            AND CURDATE() BETWEEN m.start_date AND m.end_date

    ");

    // Ambil satu film random yang sedang tayang
    $heroMovieQuery = mysqli_query($conn, "
        SELECT *
        FROM movies
        WHERE status = 'active'
        AND CURDATE() BETWEEN start_date AND end_date
        ORDER BY RAND()
        LIMIT 1
    ");
    $heroMovie = mysqli_fetch_assoc($heroMovieQuery);

    // Ambil movie coming soon di db
    $comingSoon = mysqli_query($conn, "
        SELECT 
            movie_id,
            title,
            poster_path,
            duration
        FROM movies
        WHERE status = 'coming_soon'
        ORDER BY start_date ASC
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
    <link rel="stylesheet" href="<?= $BASE_PATH ?>style.css?v=2"/>

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

<?php include("includes/header.php"); ?>



<main class="container my-1" style="padding-top: 70px;">
    <!-- HERO -->
    <div class="hero-container mb-5">
        <?php if ($heroMovie): ?>

        <div id="hero" 
            style="--hero-bg: url('<?= htmlspecialchars($heroMovie['poster_path']) ?>');">

            <!-- overlay gelap -->
            <div class="hero-overlay"></div>

            <!-- konten -->
            <div class="row align-items-center hero-content p-5 text-white">
                <!-- hero title & synopsis -->
                <div class="col-md-8 ps-4">
                    <h1 class="fw-bold" style="font-size: 28px;"><?= htmlspecialchars($heroMovie['title']) ?></h1>
                    <p class="lead" style="font-size: 16px;">
                        <?= nl2br(htmlspecialchars($heroMovie['synopsis'])) ?>
                    </p>
                    <a href="customer/movies_detail.php?id=<?= $heroMovie['movie_id'] ?>" class="btn btn-danger btn-lg mt-5">
                        Watch Now
                    </a>
                </div>
                <!-- hero poster -->
                <div class="col-md-4 hero-image">
                    <img src="<?= htmlspecialchars($heroMovie['poster_path'] ?? 'assets/movie_poster/default.jpg') ?>" 
                        class="img-fluid rounded shadow">
                </div>
            </div>

        </div>
        <?php else: ?>
            <p class="text-center text-muted">Tidak ada film untuk ditampilkan di hero.</p>
        <?php endif; ?>
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
                    <p class="mt-2 text-center fw-bold mb-0">
                        <?= htmlspecialchars($movie['title']) ?>
                    </p>
                    <p 
                    class="text-center text-muted small movie-duration" 
                    data-minutes="<?= (int)$movie['duration'] ?>">
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

    <!-- COMING SOON -->
    <h3 class="mb-3 mt-5">Coming Soon</h3>

    <div class="row row-cols-2 row-cols-md-4 g-4">

    <?php if (mysqli_num_rows($comingSoon) > 0): ?>
        <?php while ($movie = mysqli_fetch_assoc($comingSoon)): ?>
            <div class="col">
                <div class="movie-card">
                    <img 
                        src="<?= htmlspecialchars($movie['poster_path'] ?? 'assets/movie_poster/default.jpg') ?>"
                        alt="<?= htmlspecialchars($movie['title']) ?>">

                    <!-- Overlay Coming Soon -->
                    <div class="movie-overlay">
                            <a href="customer/movies_detail.php?id=<?= $movie['movie_id'] ?>" 
                            class="btn btn-secondary">
                                Coming Soon
                            </a>
                    </div>
                </div>

                <p class="mt-2 text-center fw-bold mb-0">
                    <?= htmlspecialchars($movie['title']) ?>
                </p>
                <p 
                class="text-center text-muted small movie-duration" 
                data-minutes="<?= (int)$movie['duration'] ?>">
                </p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col-12">
            <p class="text-center text-muted">
                Belum ada film coming soon.
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

<script>
window.addEventListener("scroll", function () {
    const header = document.getElementById("main-header");

    if (window.scrollY > 10) {
        header.classList.add("scrolled");
    } else {
        header.classList.remove("scrolled");
    }
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".movie-duration").forEach(el => {
        const minutes = parseInt(el.dataset.minutes);

        if (isNaN(minutes)) return;

        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;

        let result = "";
        if (hours > 0) result += hours + "h ";
        if (mins > 0) result += mins + "m";

        el.textContent = result.trim();
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
