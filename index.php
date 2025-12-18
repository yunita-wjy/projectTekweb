<?php
session_start();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    $user = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Film Verse</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- favicon -->
    <link href="assets/filmVerse-light.png" rel="icon" media="(prefers-color-scheme: light)" />
    <link href="assets/filmVerse-dark.png" rel="icon" media="(prefers-color-scheme: dark)" />
    <!-- Bootstrap & style -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=2" />
    <!-- sweet alert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="script.js"></script>
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
            <h3 id="most-viewed">Most Viewed</h3>
            <div class="card-container">
                <div class="card">
                    <img src="assets/film1.jpg" />
                    <p>title 1</p>
                </div>
                <div class="card">
                    <img src="assets/film2.jpg" />
                    <p>title 2</p>
                </div>
                <div class="card">
                    <img src="assets/film3.jpg" />
                    <p>title 3</p>
                </div>
                <div class="card">
                    <img src="assets/film4.jpg" />
                    <p>title 4</p>
                </div>
            </div>
            <h3 id="most-popular">Most Popular</h3>
            <div class="card-container">
                <div class="card">
                    <img src="assets/film2.jpg" />
                    <p>title 1</p>
                </div>
                <div class="card">
                    <img src="assets/film4.jpg" />
                    <p>title 2</p>
                </div>
                <div class="card">
                    <img src="assets/film3.jpg" />
                    <p>title 3</p>
                </div>
                <div class="card">
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
    <footer>
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
    </footer>
    <script src="script.js"></script>
</body>

</html>