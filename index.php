<?php
// ================= SESSION =================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FilmVerse</title>

    <!-- Favicon -->
    <link href="assets/filmVerse-light.png" rel="icon" media="(prefers-color-scheme: light)">
    <link href="assets/filmVerse-dark.png" rel="icon" media="(prefers-color-scheme: dark)">

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

<!-- ================= HEADER / NAVBAR ================= -->
<header id="main-header">
    <nav class="container d-flex justify-content-between align-items-center py-3">
        <div class="logo d-flex align-items-center">
            <img src="assets/filmVerse-light.png" height="40" class="me-2">
            <strong>FilmVerse</strong>
        </div>

        <ul class="nav">
            <li class="nav-item"><a class="nav-link" href="#hero">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="customer/movies.php">Movies</a></li>
        </ul>

        <div class="akun">
            <?php if ($user): ?>
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle text-dark" data-bs-toggle="dropdown">
                        <i class="fa-regular fa-user"></i>
                        Hi, <strong><?= htmlspecialchars($user['username']) ?></strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="customer/profile.php">Profile</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="auth/logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="customer/loginUI.php" class="btn btn-outline-dark">Login</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<!-- ================= HERO ================= -->
<section id="hero" class="container my-5 d-flex align-items-center">
    <div class="me-5">
        <h1>Discover Your Next Favorite Movie</h1>
        <p>
            Explore the latest movies, book tickets easily, and enjoy cinema like never before.
        </p>
        <a href="customer/movies.php" class="btn btn-dark mt-3">Watch Now</a>
    </div>
    <img src="assets/film1.jpg" width="300" class="rounded shadow">
</section>

<!-- ================= NOW SHOWING ================= -->
<section class="container my-5">
    <h3 class="mb-4">Now Showing</h3>

    <div class="row row-cols-2 row-cols-md-4 g-4">
        <div class="col">
            <div class="card h-100">
                <img src="assets/film1.jpg" class="card-img-top">
                <div class="card-body text-center">
                    <h6>Film 1</h6>
                    <a href="customer/movies_detail.php?id=1" class="btn btn-sm btn-warning">Detail</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100">
                <img src="assets/film2.jpg" class="card-img-top">
                <div class="card-body text-center">
                    <h6>Film 2</h6>
                    <a href="customer/movies_detail.php?id=2" class="btn btn-sm btn-warning">Detail</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100">
                <img src="assets/film3.jpg" class="card-img-top">
                <div class="card-body text-center">
                    <h6>Film 3</h6>
                    <a href="customer/movies_detail.php?id=3" class="btn btn-sm btn-warning">Detail</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100">
                <img src="assets/film4.jpg" class="card-img-top">
                <div class="card-body text-center">
                    <h6>Film 4</h6>
                    <a href="customer/movies_detail.php?id=4" class="btn btn-sm btn-warning">Detail</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= FOOTER ================= -->
<?php include "customer/includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
