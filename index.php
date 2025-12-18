<?php
session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Film Verse</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="assets/filmVerse-light.png" rel="icon" media="(prefers-color-scheme: light)" />
    <link href="assets/filmVerse-dark.png" rel="icon" media="(prefers-color-scheme: dark)" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=2" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <header id="main-header">
        <nav>
            <div class="logo">
                <img src="assets/filmVerse-light.png" alt="logo" />
                <span>FilmVerse</span>
            </div>
            <ul class="menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="#films">Movies</a></li>
            </ul>
            <div class="akun">
                <?php if ($user): ?>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle profile-toggle" data-bs-toggle="dropdown">
                             <i class="fa-regular fa-user me-2"></i> Hi, <strong><?= htmlspecialchars($user['username']) ?></strong>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="customer/profile.php">Profile</a></li>
                            <li><a class="dropdown-item text-danger" href="auth/logout.php">Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="customer/loginUI.php" class="login">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <section id="hero">
        <div class="container">
            <h1>Discover Your Next Favorite Movie</h1>
            <p>Temukan film terbaru dan rasakan pengalaman sinema terbaik hanya di FilmVerse.</p> <br>
            <a href="#films"><button id="watch">Watch now</button></a>
        </div>
        <div class="hero-image">
            <img src="assets/film1.jpg" alt="poster" />
        </div>
    </section>

    <section id="films">
        <h2>Now Showing</h2>
        <div class="category">
            <h3 id="most-viewed">Most Viewed</h3>
            <div class="card-container">
                <a href="customer/movies_detail.php?id=1" style="text-decoration: none;">
                    <div class="card">
                        <img src="assets/film1.jpg" />
                        <p>Spiderman: No Way Home</p>
                    </div>
                </a>
                
                <a href="customer/movies_detail.php?id=2" style="text-decoration: none;">
                    <div class="card">
                        <img src="assets/film2.jpg" />
                        <p>Avatar 2</p>
                    </div>
                </a>

                <a href="customer/movies_detail.php?id=3" style="text-decoration: none;">
                    <div class="card">
                        <img src="assets/film3.jpg" />
                        <p>The Batman</p>
                    </div>
                </a>

                <a href="customer/movies_detail.php?id=4" style="text-decoration: none;">
                    <div class="card">
                        <img src="assets/film4.jpg" />
                        <p>Interstellar</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-section">
            <h4>Movies</h4>
            <ul><li><a href="#">Action</a></li><li><a href="#">Drama</a></li></ul>
        </div>
        <div class="footer-section">
            <h4>Support</h4>
            <ul><li><a href="#">FAQ</a></li></ul>
        </div>
        <div class="footer-section">
            <h4>Contact</h4>
            <ul><li><a href="#">Email</a></li><li><a href="#">Instagram</a></li></ul>
        </div>
    </footer>
</body>
</html>