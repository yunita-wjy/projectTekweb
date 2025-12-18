<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FILM VERSE</title>

    <!-- BOOTSTRAP FIX -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f8f9fa;
        }
        footer {
            margin-top: auto;
        }

        .movie-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            cursor: pointer;
        }

        .movie-card img {
            width: 100%;
            transition: 0.3s;
        }

        .movie-card:hover img {
            transform: scale(1.05);
        }

        .movie-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: 0.3s;
        }

        .movie-card:hover .movie-overlay {
            opacity: 1;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">LOGO</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link active">Home</a></li>
            <li class="nav-item"><a class="nav-link">Movies</a></li>
            <li class="nav-item"><a class="nav-link">Login</a></li>
        </ul>
    </div>
</nav>

<main class="container my-4">

    <!-- HERO -->
    <div class="row align-items-center mb-5 bg-dark text-white p-4 rounded shadow">
        <div class="col-md-8">
            <h1 class="display-4 fw-bold">Avengers: Secret Wars</h1>
            <p class="lead">
                Earth's mightiest heroes must band together once again.
            </p>
            <button class="btn btn-danger btn-lg">Watch Now</button>
        </div>
        <div class="col-md-4 text-center">
            <img src="https://via.placeholder.com/300x450" class="img-fluid rounded shadow">
        </div>
    </div>

    <!-- NOW SHOWING -->
    <h3 class="mb-3">Now Showing</h3>

    <div class="row row-cols-2 row-cols-md-4 g-4">
        <div class="col">
            <div class="movie-card">
                <img src="https://via.placeholder.com/300x450">
                <div class="movie-overlay">
                    <button class="btn btn-warning">Beli Tiket</button>
                </div>
            </div>
            <p class="mt-2 text-center">Film 1</p>
        </div>

        <div class="col">
            <div class="movie-card">
                <img src="https://via.placeholder.com/300x450">
                <div class="movie-overlay">
                    <button class="btn btn-warning">Beli Tiket</button>
                </div>
            </div>
            <p class="mt-2 text-center">Film 2</p>
        </div>

        <div class="col">
            <div class="movie-card">
                <img src="https://via.placeholder.com/300x450">
                <div class="movie-overlay">
                    <button class="btn btn-warning">Beli Tiket</button>
                </div>
            </div>
            <p class="mt-2 text-center">Film 3</p>
        </div>

        <div class="col">
            <div class="movie-card">
                <img src="https://via.placeholder.com/300x450">
                <div class="movie-overlay">
                    <button class="btn btn-warning">Beli Tiket</button>
                </div>
            </div>
            <p class="mt-2 text-center">Film 4</p>
        </div>
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
