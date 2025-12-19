<?php
include "includes/header.php";
include "../config/dbconnect.php";
?>
<link rel="stylesheet" href="assets/customer.css">

<div class="container my-5">
    <h2 class="mb-4">Movies</h2>

    <div class="row row-cols-2 row-cols-md-4 g-4">

    <?php
    $q = mysqli_query($conn, "SELECT * FROM movies");
    while ($movie = mysqli_fetch_assoc($q)):
    ?>
        <div class="col">
            <div class="movie-card">
                <img src="../assets/movieposter/<?= $movie['poster'] ?>">
                <div class="movie-overlay">
                    <a href="movies_detail.php?id=<?= $movie['movie_id'] ?>" class="btn btn-warning">
                        Beli Tiket
                    </a>
                </div>
            </div>
            <p class="text-center mt-2"><?= $movie['title'] ?></p>
        </div>
    <?php endwhile; ?>

    </div>
</div>

<?php include "includes/footer.php"; 
?>
