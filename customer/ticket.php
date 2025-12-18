<?php
session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$title = isset($_GET['title']) ? $_GET['title'] : 'Film Pilihan';
$img = isset($_GET['img']) ? $_GET['img'] : '../assets/film1.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking: <?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css?v=2">
    <style>
        body { background-color: #f5f5f5; color: #333; }
        #main-header { background-color: #1c1c1c; padding: 15px 0; margin-bottom: 30px; }
        .logo-white { filter: invert(1); height: 40px; margin-right: 10px; }
        .booking-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .movie-info img { width: 100px; border-radius: 5px; margin-right: 20px; }
    </style>
</head>
<body>

    <nav id="main-header" class="navbar navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="../index.php">
                <img src="../assets/filmVerse-dark.png" class="logo-white">
                <span>FilmVerse</span>
            </a>
            <a href="javascript:history.back()" class="btn btn-outline-light btn-sm">Kembali</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="booking-card">
                    <h3 class="mb-4">Atur Jadwal Nonton</h3>
                    
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded movie-info">
                        <img src="<?= $img ?>">
                        <div>
                            <h5 class="fw-bold mb-1"><?= $title ?></h5>
                            <small class="text-muted">Bioskop XXI Surabaya</small>
                        </div>
                    </div>

                    <form action="seats.php" method="GET">
                        
                        <input type="hidden" name="movie_title" value="<?= $title ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Pilih Tanggal</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Pilih Jam</label>
                                <select name="time" class="form-select">
                                    <option value="12:00">12:00 WIB</option>
                                    <option value="15:30">15:30 WIB</option>
                                    <option value="19:00">19:00 WIB</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark btn-lg">
                                Lanjut Pilih Kursi <i class="fa-solid fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</body>
</html>