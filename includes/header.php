<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['user'] ?? null;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/index.php">FilmVerse</a>

        <ul class="navbar-nav mx-auto">
            <li class="nav-item"><a class="nav-link" href="/index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="/customer/movies.php">Movies</a></li>
        </ul>

        <?php if ($user): ?>
            <span class="text-white">Hi, <?= htmlspecialchars($user['username']) ?></span>
        <?php else: ?>
            <a href="/customer/loginUI.php" class="btn btn-outline-light">Login</a>
        <?php endif; ?>
    </div>
</nav>
