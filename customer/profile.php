<!DOCTYPE html>
<?php
session_start();
require "../config/connection.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit();
}
$userId = $_SESSION['user']['user_id'];
$stmt = $conn->query("SELECT * FROM users WHERE user_id = $userId");
$record = $stmt->fetch();

$ava = trim($record['full_name']);
$words = explode(" ", $record['full_name']);

if (count($words) >= 2) {
    $initial = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
} else {
    $initial = strtoupper(substr($words[0], 0, 1));
}
?>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Customer Profile - Film Verse</title>
    <!-- Bootstrap & style -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="../style.css?v=2" /> -->
     <script src="../script.js"></script>
      <!-- sweet alert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
</head>

<style>
    body {
        background-color: #f8f9fa;
        color: #0a0a0a;
        font-family: 'Segoe UI', sans-serif;
    }

    .profile-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .profile-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background-color: #e6e6e6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: bold;
        margin: 0 auto 15px;
    }

    .username {
        color: #6c757d;
        font-size: 14px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .info-box {
        background-color: #e6e6e6;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 15px;
    }

    .btn-primary-custom {
        background-color: #4ecdc4;
        border: none;
        color: #0a0a0a;
        border-radius: 10px;
        padding: 10px 18px;
    }

    .btn-primary-custom:hover {
        background-color: #38b2ac;
        color: #ffffff;
    }

    .history-card {
        border-radius: 12px;
        border: 1px solid #e6e6e6;
        padding: 15px 20px;
        margin-bottom: 12px;
    }

    .movie-title {
        font-weight: 600;
    }

    .error-text {
        color: red;
        font-size: 13px;
    }
</style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">

                <!-- Profile Card -->
                <div class="profile-card mb-4">
                    <div class="profile-header">
                        <div class="avatar"><?php echo $initial; ?></div>
                        <h3><?php echo $record['full_name']; ?></h3>
                        <div class="username"><?= $record['username'] ?></div>
                    </div>

                    <!-- Personal Info -->
                    <div class="mb-4">
                        <div class="section-title">Account Details</div>

                        <div class="info-box">
                            <strong>Email</strong><br>
                            <?= $record['email'] ?>
                        </div>

                        <div class="info-box">
                            <strong>Phone Number</strong><br>
                            <?= $record['phone'] ?>
                        </div>

                        <button class="btn btn-primary-custom mt-2" onclick="editProfile()">Edit Profile</button>
                    </div>
                </div>

                <!-- Purchase History -->
                <div class="profile-card">
                    <div class="section-title mb-3">Ticket Purchase History</div>

                    <div class="history-card">
                        <div class="movie-title">Avengers: Endgame</div>
                        <div class="text-muted">Cinema XXI • 12 Oct 2025 • Seat C7</div>
                    </div>

                    <div class="history-card">
                        <div class="movie-title">Interstellar</div>
                        <div class="text-muted">CGV • 05 Sep 2025 • Seat A10</div>
                    </div>

                    <div class="history-card">
                        <div class="movie-title">Dune: Part Two</div>
                        <div class="text-muted">Cinepolis • 21 Aug 2025 • Seat F3</div>
                    </div>

                    <!-- Example error message -->
                    <div class="error-text mt-2">Failed to load more history</div>
                    <div class="text-end mt-3">
                        <a href="../index.php" class="btn btn-primary-custom">
                            ← Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>