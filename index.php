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
        FROM movies m
        JOIN showtimes s ON s.movie_id = m.movie_id
        WHERE
            m.status = 'active'
            AND CURDATE() BETWEEN m.start_date AND m.end_date
            AND s.show_date >= CURDATE()
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

        /* .movie-item p {}
            min-height: 48px;
        } */

        .movie-card {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            width: 100%;
            
        }

        .movie-card img {
            width: 100%;     
            aspect-ratio: 2 / 3;   
            object-fit: cover;    
            display: block;
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

        .poster-wrapper {
            width: 280px;          
            aspect-ratio: 2 / 3;   
        }

        .poster-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;    
            display: block;
        }

        .movie-card:hover .movie-overlay {
            opacity: 1;
        }

        .movie-scroll {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 10px 0;
        }

        .movie-scroll::-webkit-scrollbar {
            display: none;
        }

        .movie-item {
            width: 280px;
            flex-shrink: 0;
        }

        /* tombol panah */
        .scroll-btn {
            position: absolute;
            top: 40%;
            transform: translateY(-50%);
            background: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0,0,0,.2);
            z-index: 10;
        }

        .scroll-btn.left { left: -15px; }
        .scroll-btn.right { right: -15px; }

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
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="mb-0">Now Showing</h3>
        <a href="customer/movies.php" class="btn btn-sm btn-outline-secondary">
            Show More →
        </a>
    </div>
    
    <div class="position-relative">
        <!-- tombol kiri -->
        <button class="scroll-btn left" onclick="scrollRow('nowShowingRow', -300)">
            <i class="fas fa-chevron-left"></i>
        </button>


        <!-- list movies -->
        <div class="movie-scroll" id="nowShowingRow">
            <?php while ($movie = mysqli_fetch_assoc($nowShowing)): ?>
            <div class="movie-item">
                <div class="movie-card poster-wrapper">
                    <img src="<?= htmlspecialchars($movie['poster_path']) ?>">
                    <div class="movie-overlay">
                        <a href="customer/movies_detail.php?id=<?= $movie['movie_id'] ?>" 
                        class="btn btn-warning">
                            Beli Tiket
                        </a>
                    </div>
                </div>

                <p class="mt-3 text-center fw-bold mb-0">
                    <?= htmlspecialchars($movie['title']) ?>
                </p>
                <p 
                class="text-center text-muted small movie-duration" 
                data-minutes="<?= (int)$movie['duration'] ?>">
                </p>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- tombol kanan -->
        <button class="scroll-btn right" onclick="scrollRow('nowShowingRow', 300)">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>


    <!-- COMING SOON -->
    <div class="d-flex justify-content-between align-items-center mb-2 mt-5">
        <h3 class="mb-0">Coming Soon</h3>
        <a href="customer/movies.php" class="btn btn-sm btn-outline-secondary">
            Show More →
        </a>
    </div>

    <div class="position-relative">
        <!-- tombol kiri -->
        <button class="scroll-btn left" onclick="scrollRow('comingSoonRow', -300)">
            <i class="fas fa-chevron-left"></i>
        </button>

        <!-- list movies -->
        <div class="movie-scroll" id="comingSoonRow">
            <?php while ($movie = mysqli_fetch_assoc($comingSoon)): ?>
                <div class="movie-item">
                    <div class="movie-card poster-wrapper">
                        <img src="<?= htmlspecialchars($movie['poster_path']) ?>">
                        <div class="movie-overlay">
                            <a href="customer/movies_detail.php?id=<?= $movie['movie_id'] ?>" 
                            class="btn btn-secondary">
                                Coming Soon
                            </a>
                        </div>
                    </div>

                    <p class="mt-3 text-center fw-bold mb-0">
                        <?= htmlspecialchars($movie['title']) ?>
                    </p>
                    <p 
                    class="text-center text-muted small movie-duration" 
                    data-minutes="<?= (int)$movie['duration'] ?>">
                    </p>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- tombol kanan -->
        <button class="scroll-btn right" onclick="scrollRow('comingSoonRow', 300)">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>





</main>

<?php include("includes/footer.php"); ?>

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


function scrollRow(id, value) {
    document.getElementById(id).scrollLeft += value;
}


</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
