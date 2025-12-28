<?php
session_start();
require "../config/connection.php";

$BASE_PATH = '/proyek/projectTekweb/';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit();
}

$userId = $_SESSION['user']['user_id'];

/* ===============================
   UPDATE PROFILE (POST)
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $username  = trim($_POST['username']);
    $phone     = trim($_POST['phone']);

    if ($full_name && $username && $phone) {
        $stmt = $conn->prepare("
            UPDATE users 
            SET full_name = ?, username = ?, phone = ?
            WHERE user_id = ?
        ");
        $stmt->bind_param("sssi", $full_name, $username, $phone, $userId);
        $stmt->execute();

        // update session biar header langsung berubah
        $_SESSION['user']['full_name'] = $full_name;
        $_SESSION['user']['username']  = $username;

        header("Location: profile.php?success=1");
        exit();
    }
}

/* ===============================
   FETCH USER DATA
================================ */
$stmt = $conn->prepare("
    SELECT username, email, full_name, phone
    FROM users
    WHERE user_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$record = $stmt->get_result()->fetch_assoc();

if (!$record) {
    die("User tidak ditemukan");
}

/* Avatar Initial */
$words = explode(" ", trim($record['full_name']));
$initial = strtoupper(substr($words[0], 0, 1));
if (count($words) >= 2) {
    $initial .= strtoupper(substr($words[1], 0, 1));
}

/* ===============================
   FETCH TICKET HISTORY
================================ */
$query = "
SELECT 
    t.transaction_id,
    t.booking_code,
    m.title,
    s.show_date,
    s.start_time,
    st.studio_name,
    GROUP_CONCAT(CONCAT(se.seat_row, se.seat_column) ORDER BY se.seat_row) AS seats
FROM transactions t
JOIN showtimes s ON t.showtime_id = s.showtime_id
JOIN movies m ON s.movie_id = m.movie_id
JOIN studios st ON s.studio_id = st.studio_id
JOIN transaction_seats ts ON t.transaction_id = ts.transaction_id
JOIN seats se ON ts.seat_id = se.seat_id
WHERE t.user_id = ?
  AND t.status = 'paid'
GROUP BY t.transaction_id
ORDER BY t.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$histories = $stmt->get_result();




?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Customer Profile - Film Verse</title>
    <!-- Bootstrap & style -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" /> -->
    <!-- <link rel="stylesheet" href="../style.css?v=2" /> -->
    <!-- BOOTSTRAP FIX -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $BASE_PATH ?>style.css?v=2"/>
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

    /* ORDER HISTORY */
    .history-card {
        border-radius: 12px;
        border: 1px solid #e6e6e6;
        padding: 15px 20px;
        margin-bottom: 12px;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        cursor: pointer;
        background: #fff;
    }

    .history-card:hover {
        
        transform: scale(1.01);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
    }


</style>
</head>

<body>
    <?php include("../includes/header.php"); ?>
    <main>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    Profile berhasil diperbarui
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>
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

                        <!-- BUTTON -->
                        <button class="btn btn-primary-custom mt-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            Edit Profile
                        </button>
                      </div>
                    </div>

                    <!-- Purchase History -->
                    <div class="profile-card">
                        <div class="section-title mb-3">Ticket Purchase History</div>

                        <?php if ($histories->num_rows > 0): ?>
                            <?php while ($row = $histories->fetch_assoc()): ?>
                                <a href="ticket.php?booking_code=<?= $row['booking_code'] ?>" 
                                class="history-card d-block text-decoration-none text-dark">

                                    <div class="movie-title"><?= htmlspecialchars($row['title']) ?></div>
                                    <div class="text-muted">
                                        <?= date('d M Y', strtotime($row['show_date'])) ?> • 
                                        <?= substr($row['start_time'],0,5) ?> • 
                                        <?= $row['studio_name'] ?> • 
                                        Seat <?= $row['seats'] ?>
                                    </div>
                                </a>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                Belum ada history pemesanan tiket.
                            </div>
                        <?php endif; ?>


                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="../index.php" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-arrow-left me-1"></i> Back to Home
                            </a>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <!-- EDIT PROFILE MODAL -->
        <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control"
                        value="<?= htmlspecialchars($record['full_name']) ?>" required>
                </div>

                <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control"
                        value="<?= htmlspecialchars($record['username']) ?>" required>
                </div>

                <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control"
                        value="<?= htmlspecialchars($record['phone']) ?>" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                
                <button type="submit" name="update_profile" class="btn btn-primary">
                    Save Changes
                </button>
            </div>
            </form>
        </div>
        </div>

        
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>