<?php
session_start();
require "config/connection.php";

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    $user = null;
}
$sqlShow = "
    SELECT DISTINCT
        m.movie_id,
        m.title,
        m.poster_path
    FROM showtimes s
    JOIN movies m ON s.movie_id = m.movie_id
    WHERE 
        m.status = 'active'
        AND CURDATE() BETWEEN m.start_date AND m.end_date
";

$stmt = $conn->prepare($sqlShow);
$stmt->execute();
$nowShowing = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Ambil satu film random yang sedang tayang
$sqlHero = "
    SELECT *
    FROM movies
    WHERE status = 'active'
      AND CURDATE() BETWEEN start_date AND end_date
    ORDER BY RAND()
    LIMIT 1
";

$stmt = $conn->prepare($sqlHero);
$stmt->execute();
$heroMovie = $stmt->fetch(PDO::FETCH_ASSOC);
$basePath = '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Film Verse</title>
    <?php include 'includes/head.php'; ?>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'includes/header.php'; ?>
    <section id="hero">
        <div class="container">
            <h1>Discover Your Next Favorite Movie</h1>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed convallis
                consectetur nunc, non pharetra nunc tempus a. Nulla facilisi. Donec
                faucibus, turpis in ullamcorper consectetur, est velit fermentum
                lectus, at consectetur risus mi vel est. Donec tristique, ipsum a
                hendrerit consectetur, felis nunc tincidunt ex, a congue ligula felis
                a turpis.
            </p> <br>
            <button id="watch">Watch now</button>
        </div>
        <div class="hero-image">
            <img src="assets/film1.jpg" alt="poster" />
        </div>
    </section>
    <section id="films">
        <h2>Films</h2>
        <div class="category">
            <h3 id="most-popular">Most Popular</h3>
            <div class="card-container">
                <div class="card1">
                    <img src="assets/film1.jpg" />
                    <p>title 1</p>
                </div>
                <div class="card1">
                    <img src="assets/film2.jpg" />
                    <p>title 2</p>
                </div>
                <div class="card1">
                    <img src="assets/film3.jpg" />
                    <p>title 3</p>
                </div>
                <div class="card1">
                    <img src="assets/film4.jpg" />
                    <p>title 4</p>
                </div>
            </div>
            <h3 id="coming-soon">Coming Soon</h3>
            <div class="card-container">
                <div class="card2">
                    <img src="assets/film2.jpg" />
                    <p>title 1</p>
                </div>
                <div class="card2">
                    <img src="assets/film4.jpg" />
                    <p>title 2</p>
                </div>
                <div class="card2">
                    <img src="assets/film3.jpg" />
                    <p>title 3</p>
                </div>
                <div class="card2">
                    <img src="assets/film1.jpg" />
                    <p>title 4</p>
                </div>
            </div>
        </div>
    </section>
    <section id="serials">

    </section>
    <section id="detail">

    </section>
    <!-- <footer>
        <div class="footer-section">
            <h4>Movies</h4>
            <ul>
                <li><a href="#">Action</a></li>
                <li><a href="#">Drama</a></li>
                <li><a href="#">Comedy</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Serials</h4>
            <ul>
                <li><a href="#">Netflix</a></li>
                <li><a href="#">Disney+</a></li>
                <li><a href="#">HBO</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Support</h4>
            <ul>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Help Center</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Contact</h4>
            <ul>
                <li><a href="#">Email</a></li>
                <li><a href="#">Instagram</a></li>
            </ul>
        </div>
    </footer> -->
    <?php include 'includes/footer.php'; ?>
    <script src="assets/javascript/script.js"></script>
</body>

</html>