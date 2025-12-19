


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FILM VERSE</title>

    <!-- BOOTSTRAP FIX -->
   <meta charset="UTF-8" />
    <title>Movies - Film Verse</title>
    <!-- favicon -->
    <link href="../assets/filmVerse-light.png" rel="icon" media="(prefers-color-scheme: light)" />
    <link href="../assets/filmVerse-dark.png" rel="icon" media="(prefers-color-scheme: dark)" />
    <!-- icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- sweet alert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="style.css?v=3">
    <script src="../script.js"></script>

</head>

<body>

<?php include("includes/header.php"); ?>



<main class="container my-4" style="padding-top: 70px;">
    <!-- HERO -->
    <div class="hero-container">
        <?php if ($heroMovie): ?>
        <div class="row align-items-center mb-5 bg-dark text-white p-4 rounded shadow" style="font-size:24px;">
            <div class="col-md-8">
                <h1 class="fw-bold"><?= htmlspecialchars($heroMovie['title']) ?></h1>
                <p class="lead" style="font-size: 14px;">
                    <?= nl2br(htmlspecialchars($heroMovie['synopsis'])) ?>
                </p>
                <a href="customer/movies_detail.php?id=<?= $heroMovie['movie_id'] ?>" class="btn btn-danger btn-lg mt-5">
                    Watch Now
                </a>
            </div>
            <div class="col-md-4 text-center">
                <img src="<?= htmlspecialchars($heroMovie['poster_path'] ?? 'assets/movie_poster/default.jpg') ?>" 
                    class="img-fluid rounded shadow">
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