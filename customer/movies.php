<?php
include "includes/header.php";
include "../config/dbconnect.php";
?>

<link rel="stylesheet" href="assets/customer.css">

<div class="container my-5">
    <h2 class="mb-4">Now Showing</h2>

    <div class="row row-cols-2 row-cols-md-4 g-4">

        <?php
        $query = "
            SELECT DISTINCT 
                m.movie_id,
                m.title,
                m.poster
            FROM showtimes s
            JOIN movies m ON s.movie_id = m.movie_id
            WHERE s.status = 'active'
        ";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0):
            while ($movie = mysqli_fetch_assoc($result)):
        ?>
                <div class="col">
                    <div class="movie-card">
                        <img 
                            src="assets/movieposter/<?= htmlspecialchars($movie['poster']) ?>" 
                            alt="<?= htmlspecialchars($movie['title']) ?>"
                        >
                        <div class="movie-overlay">
                            <a 
                                href="movies_detail.php?id=<?= $movie['movie_id'] ?>" 
                                class="btn btn-warning">
                                Beli Tiket
                            </a>
                        </div>
                    </div>
                    <p class="text-center mt-2">
                        <?= htmlspecialchars($movie['title']) ?>
                    </p>
                </div>
        <?php
            endwhile;
        else:
        ?>
            <div class="col-12">
                <p class="text-center text-muted">
                    Belum ada film yang sedang tayang.
                </p>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php include "includes/footer.php"; ?>
