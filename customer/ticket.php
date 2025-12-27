<?php
session_start();
require "../config/connection.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}


$booking_code = $_GET['booking_code'] ?? '';
if (!$booking_code) die("Invalid ticket");

/* Ambil data ticket */
$query = "
SELECT 
    t.booking_code,
    m.title,
    m.poster_path,
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
WHERE t.booking_code = ?
GROUP BY t.transaction_id
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $booking_code);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) die("Ticket not found");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Ticket</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.ticket-card {
    max-width: 600px;
    margin: 60px auto;
    border-radius: 16px;
    box-shadow: 0 15px 30px rgba(0,0,0,.15);
    overflow: hidden;
}
.ticket-header {
    background: #4ecdc4;
    padding: 20px;
    text-align: center;
    font-weight: bold;
}
.ticket-body {
    padding: 20px;
}
.ticket-body img {
    max-width: 130px;
    border-radius: 10px;
}
.booking-code {
    font-size: 28px;
    letter-spacing: 4px;
    font-weight: bold;
}
</style>
</head>

<body>
<div class="ticket-card bg-white">
    <div class="ticket-header fw-bold text-white" style="font-size: 24px;">
        MOVIE TICKET
    </div>

    <div class="ticket-body">
        <div class="row mb-3">
            <div class="col-4">
                <img src="../<?= $data['poster_path'] ?>">
            </div>
            <div class="col-8">
                <h4 class="fw-bold"><?= $data['title'] ?></h4>
                <p class="mb-1">Date      : <?= date('d M Y', strtotime($data['show_date'])) ?></p>
                <p class="mb-1">Time      : <?= substr($data['start_time'],0,5) ?></p>
                <p class="mb-1">Studio : <?= $data['studio_name'] ?></p>
                <p class="mb-1">Seat      : <?= $data['seats'] ?></p>
            </div>
        </div>

        <hr>

        <div class="text-center">
            <div class="text-muted">BOOKING CODE</div>
            <div class="booking-code"><?= $data['booking_code'] ?></div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <a href="../index.php" class="btn btn-outline-secondary w-50">Home</a>
            <a href="profile.php" class="btn btn-warning w-50">My Tickets</a>
        </div>
    </div>
</div>
</body>
</html>
