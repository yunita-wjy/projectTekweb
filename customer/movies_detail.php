<?php
session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

// --- DATA DUMMY (Manual tanpa Database) ---
$id = isset($_GET['id']) ? $_GET['id'] : 1;

// List Data Film 
$movies = [
    1 => [
        'title' => 'Spiderman: No Way Home', 
        'img' => '../assets/film1.jpg', 
        'genre' => 'Action, Adventure',
        'year' => '2021',
        'duration' => '148 Min',
        'desc' => 'Identitas Spider-Man terungkap. Peter meminta bantuan Doctor Strange agar semua orang melupakan identitasnya. Namun, mantra itu kacau dan mendatangkan musuh-musuh Spider-Man dari semesta lain.'
    ],
    2 => [
        'title' => 'Avatar: The Way of Water', 
        'img' => '../assets/film2.jpg', 
        'genre' => 'Sci-Fi, Adventure',
        'year' => '2022',
        'duration' => '192 Min',
        'desc' => 'Jake Sully tinggal bersama keluarga barunya di planet Pandora. Setelah ancaman lama kembali untuk menyelesaikan apa yang telah dimulai, Jake harus bekerja sama dengan Neytiri dan tentara ras Na\'vi untuk melindungi planet mereka.'
    ],
    3 => [
        'title' => 'The Batman', 
        'img' => '../assets/film3.jpg', 
        'genre' => 'Crime, Drama',
        'year' => '2022',
        'duration' => '176 Min',
        'desc' => 'Batman menjelajah ke dunia bawah tanah Kota Gotham ketika seorang pembunuh sadis meninggalkan jejak petunjuk samar. Batman harus menjalin hubungan baru dan membuka kedok pelakunya.'
    ],
    4 => [
        'title' => 'Interstellar', 
        'img' => '../assets/film4.jpg', 
        'genre' => 'Sci-Fi, Drama',
        'year' => '2014',
        'duration' => '169 Min',
        'desc' => 'Tim penjelajah melintasi lubang cacing di luar angkasa dalam upaya memastikan kelangsungan hidup umat manusia ketika bumi mulai tidak layak huni.'
    ]
];

// Ambil data film berdasarkan ID, kalau tidak ada, default ke film 1
$m = isset($movies[$id]) ? $movies[$id] : $movies[1];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Detail: <?= $m['title'] ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Reset & Dark Mode Base */
        body { 
            background-color: #141414; 
            color: white; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* --- NAVBAR STYLING --- */
        #main-header {
            background-color: rgba(0,0,0,0.9);
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
            padding: 10px 0;
        }
        .navbar-brand img { height: 40px; margin-right: 10px; }
        .navbar-brand span { font-weight: bold; font-size: 1.5rem; color: #fff; }
        .nav-link { color: #ccc !important; font-weight: 500; margin-right: 15px; }
        .nav-link:hover { color: #E50914 !important; }
        
        .btn-login {
            background-color: #E50914;
            color: white !important;
            padding: 8px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-login:hover { background-color: #b20710; }

        /* --- DETAIL SECTION --- */
        .detail-container {
            padding: 50px 0;
            /* Background Blur effect dari poster */
            background: linear-gradient(to right, #141414 20%, rgba(20,20,20,0.85) 60%, rgba(20,20,20,0.85) 100%), 
                        url('<?= $m['img'] ?>');
            background-size: cover;
            background-position: center;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }

        .poster-hd {
            width: 100%;
            max-width: 300px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            border: 1px solid #333;
        }

        .info-hd h1 { font-size: 3.5rem; font-weight: 800; margin-bottom: 10px; line-height: 1.1; }
        .meta-tags span { margin-right: 15px; font-size: 1.1rem; color: #ccc; }
        .synopsis { font-size: 1.1rem; line-height: 1.6; color: #ddd; margin-top: 20px; max-width: 700px; }

        .btn-ticket {
            background-color: #E50914; 
            color: white; 
            padding: 15px 40px; 
            border: none; 
            border-radius: 5px; 
            font-size: 1.2rem; 
            font-weight: bold;
            margin-top: 30px;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
        }
        .btn-ticket:hover { background-color: #b20710; color: white; transform: scale(1.05); }

        /* --- FAKE TRAILER --- */
        .trailer-box {
            margin-top: 30px;
            width: 200px;
            height: 120px;
            background-color: #000;
            border-radius: 10px;
            position: relative;
            cursor: pointer;
            border: 2px solid #333;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }
        .trailer-box:hover { border-color: #E50914; }
        .trailer-bg {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover; opacity: 0.5;
        }
        .play-icon {
            z-index: 2; color: white; font-size: 40px;
            filter: drop-shadow(0 0 10px black);
        }
        .trailer-label {
            position: absolute; bottom: 5px; left: 10px; z-index: 2;
            font-size: 12px; font-weight: bold;
        }

        /* --- FOOTER --- */
        footer { background: #000; padding: 40px 0; margin-top: 50px; border-top: 1px solid #333; text-align: center; color: #777; }
        footer ul { list-style: none; padding: 0; }
        footer ul li { display: inline; margin: 0 10px; }
        footer a { color: #777; text-decoration: none; }
        footer a:hover { color: white; }

        @media (max-width: 768px) {
            .detail-container { flex-direction: column; text-align: center; background: #141414; }
            .poster-hd { margin-bottom: 30px; }
            .info-hd h1 { font-size: 2.5rem; }
        }
    </style>
</head>

<body>
    
    <nav id="main-header" class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="../index.php">
                <img src="../assets/filmVerse-light.png" alt="logo" />
                <span>FilmVerse</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php#films">Movies</a>
                    </li>
                    <li class="nav-item ms-3">
                        <?php if ($user): ?>
                            <a href="profile.php" class="nav-link text-white">
                                <i class="fa-regular fa-user me-2"></i> <?= htmlspecialchars($user['username']) ?>
                            </a>
                        <?php else: ?>
                            <a href="loginUI.php" class="btn-login">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="detail-container">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 text-center">
                    <img src="<?= $m['img'] ?>" class="poster-hd" alt="Poster Film">
                </div>

                <div class="col-md-8 info-hd">
                    <h1><?= $m['title'] ?></h1>
                    
                    <div class="meta-tags">
                        <span><i class="fa-regular fa-clock text-danger"></i> <?= $m['duration'] ?></span>
                        <span><i class="fa-solid fa-calendar-days text-danger"></i> <?= $m['year'] ?></span>
                        <span class="badge bg-secondary"><?= $m['genre'] ?></span>
                    </div>

                    <p class="synopsis"><?= $m['desc'] ?></p>
                    
                    <div class="d-flex align-items-center gap-4">
                        <a href="ticket.php?title=<?= urlencode($m['title']) ?>&img=<?= urlencode($m['img']) ?>" class="btn-ticket">
                            <i class="fa-solid fa-ticket me-2"></i> Get Ticket
                        </a>

                        <div class="trailer-box" onclick="alert('Memutar Trailer...')">
                            <img src="<?= $m['img'] ?>" class="trailer-bg">
                            <div class="play-icon"><i class="fa-solid fa-circle-play"></i></div>
                            <span class="trailer-label">Watch Trailer</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Movies</h5>
                    <ul>
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Drama</a></li>
                        <li><a href="#">Comedy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Support</h5>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Help Center</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Social</h5>
                    <ul>
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">Twitter</a></li>
                    </ul>
                </div>
            </div>
            <p class="mt-4 small">&copy; 2025 FilmVerse. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>