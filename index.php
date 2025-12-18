<?php include "customer/includes/header.php"; ?>
<link rel="stylesheet" href="customer/assets/customer.css">

<div class="container my-5">

    <div class="row hero-box align-items-center mb-5">
        <div class="col-md-8">
            <h1 class="fw-bold">Avengers: Secret Wars</h1>
            <p>Earth's mightiest heroes must band together once again.</p>
            <a href="customer/movies.php" class="btn btn-danger btn-lg">
                Watch Now
            </a>
        </div>
        <div class="col-md-4 text-center">
            <img src="assets/film1.jpg" class="img-fluid rounded shadow">
        </div>
    </div>

    <h3 class="mb-3">Now Showing</h3>

    <div class="row row-cols-2 row-cols-md-4 g-4">
        <?php for ($i=1; $i<=4; $i++): ?>
        <div class="col">
            <div class="movie-card">
                <img src="assets/film<?= $i ?>.jpg">
                <div class="movie-overlay">
                    <a href="customer/movies_detail.php?id=<?= $i ?>" class="btn btn-warning">
                        Beli Tiket
                    </a>
                </div>
            </div>
        </div>
        <?php endfor; ?>
    </div>
</div>

<?php include "customer/includes/footer.php"; ?>
