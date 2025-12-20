<?php
session_start();
require "config/connection.php";
require "classes/movie.php";

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    $user = null;
}

$movies = new movie($conn);
$nowShowing = $movies->getNowShowing();
$comingSoon = $movies->getComingSoon();
$heroMovie = $movies->getHeroMovie();

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
        <?php if ($heroMovie): ?>
            <div class="hero-container">
                <h1><?= htmlspecialchars($heroMovie['title']) ?></h1>
                <p>
                    <?= nl2br(htmlspecialchars($heroMovie['synopsis'])) ?>
                </p> <br>
                <a href="customer/movies_detail.php?id=<?= $heroMovie['movie_id'] ?>">
                    <button id="watch">Watch now</button>
                </a>
            </div>
            <div class="hero-image">
                <img
                    src="<?= htmlspecialchars($heroMovie['poster_path']) ?>"
                    alt="poster <?= htmlspecialchars($heroMovie['title']) ?>" />
            </div>
        <?php else: ?>
            <div class="container">
                <h1>No Movie available</h1>
                <p>Currently no active movie.</p>
            </div>
        <?php endif; ?>
    </section>

    <section id="films">
        <h2>Films</h2>
        <div class="category">
            <h3 id="most-popular">Most Popular</h3>
            <div class="card-container" >
                <?php if (!empty($nowShowing)): ?>
                    <?php foreach ($nowShowing as $movie): ?>
                        <div class="card1" onclick="window.location.href='customer/movies_detail.php?id=<?= $movie['movie_id'] ?>'">
                            <img
                                src="<?= htmlspecialchars($movie['poster_path']) ?>"
                                alt="<?= htmlspecialchars($movie['title']) ?>" />
                            <p><?= htmlspecialchars($movie['title']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted" style="padding: 8px;">No currently movies showing</p>
                <?php endif; ?>
            </div>

            <h3 id="coming-soon">Coming Soon</h3>
            <div class="card-container">
                <?php if (!empty($comingSoon)): ?>
                    <?php foreach ($comingSoon as $movie): ?>
                        <div class="card2">
                            <img
                                src="<?= htmlspecialchars($movie['poster_path']) ?>"
                                alt="<?= htmlspecialchars($movie['title']) ?>" />
                            <p><?= htmlspecialchars($movie['title']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted" style="padding: 8px;">No coming soon movies</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/javascript/script.js"></script>
</body>

</html>