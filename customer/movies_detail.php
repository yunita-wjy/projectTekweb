<?php
include "includes/header.php";
include "../config/dbconnect.php";

$id = $_GET['id'] ?? 0;
$stmt = mysqli_prepare($conn, "SELECT * FROM movies WHERE movie_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$movie = mysqli_fetch_assoc($result);

if (!$movie) {
    echo "<div class='container my-5'>Movie not found</div>";
    include "includes/footer.php";
    exit;
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <img src="../assets/movieposter/<?= $movie['poster'] ?>" class="img-fluid rounded">
        </div>
        <div class="col-md-8">
            <h1><?= $movie['title'] ?></h1>
            <p><?= $movie['description'] ?></p>
            <a href="seats.php?movie_id=<?= $movie['movie_id'] ?>" class="btn btn-dark">
                Continue to Payment
            </a>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
