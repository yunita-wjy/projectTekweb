<?php
    session_start();

    $BASE_PATH = '/proyek/projectTekweb/';

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
    } else {
        $user = null;
    }

    require "../config/connection.php";

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
<html>
    <head>
        <title>Movies</title>
        <link rel="stylesheet" href="assets/customer.css">
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- CSS -->
        <link rel="stylesheet" href="../style.css">
        <style>
        .movie-toggle {
            display: flex;
            gap: 12px;
        }

        .toggle-btn {
            padding: 10px 26px;
            border-radius: 999px;
            border: 1px solid #ddd;
            background: transparent;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .toggle-btn:hover {
            background: #f1f1f1;
        }

        .toggle-btn.active {
            background: #4ecdc4;
            color: white;
            border-color: #4ecdc4;
        }

        .movie-section {
            display: none;
        }

        .movie-section.active {
            display: block;
        }

        .movie-card {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            
        }

        .movie-card img {
            width: 100%;
            
            aspect-ratio: 2 / 3;   
            object-fit: cover;    
            display: block;
        }
        
        .search-box .input-group-text {
            border-right: 0;
        }

        .search-box .form-control {
            border-left: 0;
        }

        .search-box .form-control:focus {
            box-shadow: none;
        }


        </style>

    </head>
    <body>
        <?php include("../includes/header.php"); ?>
        <main class="container my-1" style="padding-top: 70px; padding-bottom:70px;">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1 class="fw-bold mb-0" style="font-size: 40px;">Movies</h1>
                <div class="search-box">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fa-solid fa-magnifying-glass text-muted"></i>
                        </span>
                        <input 
                            type="text"
                            id="movieSearch"
                            class="form-control"
                            placeholder="Search movie..."
                        >
                    </div>
                </div>
            </div>
            <div class="movie-toggle mb-4">
                <button id="btn-now" class="toggle-btn active">
                    Now Showing
                </button>
                <button id="btn-soon" class="toggle-btn">
                    Coming Soon
                </button>
            </div>

            <div class="tab-content">
                <!-- NOW SHOWING TAB -->
                <div id="now-showing" class="movie-section active">
                    <div class="row row-cols-2 row-cols-md-4 g-4">

                        <?php if (mysqli_num_rows($nowShowing) > 0): ?>
                        <?php while ($movie = mysqli_fetch_assoc($nowShowing)): ?>
                            <div class="col movie-item" data-title="<?= strtolower(htmlspecialchars($movie['title'])) ?>">
                            <div class="movie-card">
                                <img src="../<?= htmlspecialchars($movie['poster_path']) ?>"
                                    alt="<?= htmlspecialchars($movie['title']) ?>">
                                <div class="movie-overlay">
                                <a href="movies_detail.php?id=<?= $movie['movie_id'] ?>" class="btn btn-warning">
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
                        <p class="text-muted text-center">Belum ada film yang sedang tayang.</p>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- COMING SOON TAB -->
                <div id="coming-soon" class="movie-section">
                    <div class="row row-cols-2 row-cols-md-4 g-4">

                        <?php if (mysqli_num_rows($comingSoon) > 0): ?>
                        <?php while ($movie = mysqli_fetch_assoc($comingSoon)): ?>
                            <div class="col movie-item" data-title="<?= strtolower(htmlspecialchars($movie['title'])) ?>">
                            <div class="movie-card">
                                <img src="../<?= htmlspecialchars($movie['poster_path']) ?>"
                                    alt="<?= htmlspecialchars($movie['title']) ?>">
                                <div class="movie-overlay">
                                        <a href="movies_detail.php?id=<?= $movie['movie_id'] ?>" 
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
                        <p class="text-muted text-center">Belum ada film coming soon.</p>
                        <?php endif; ?>

                    </div>
                </div>


            </div>


        </main>

        <?php include("../includes/footer.php"); ?>


        <script>
        // Search Movie
        const searchInput = document.getElementById("movieSearch");

        searchInput.addEventListener("input", function () {
            const keyword = this.value.toLowerCase().trim();

            // cari section yang lagi aktif
            const activeSection = document.querySelector(".movie-section.active");
            const movies = activeSection.querySelectorAll(".movie-item");

            movies.forEach(movie => {
                const title = movie.dataset.title;

                if (title.includes(keyword)) {
                    movie.style.display = "";
                } else {
                    movie.style.display = "none";
                }
            });
        });

        // Toggle Now Showing and Coming Soon
        const btnNow = document.getElementById("btn-now");
        const btnSoon = document.getElementById("btn-soon");

        const nowSection = document.getElementById("now-showing");
        const soonSection = document.getElementById("coming-soon");

        btnNow.addEventListener("click", () => {
            btnNow.classList.add("active");
            btnSoon.classList.remove("active");

            nowSection.classList.add("active");
            soonSection.classList.remove("active");
        });

        btnSoon.addEventListener("click", () => {
            btnSoon.classList.add("active");
            btnNow.classList.remove("active");

            soonSection.classList.add("active");
            nowSection.classList.remove("active");
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




