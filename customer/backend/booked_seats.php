<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require('../../config/connection.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Ambil studio_id dan showtime_id dari GET (bisa diganti manual juga)
$studio_id = isset($_GET['studio_id']) ? intval($_GET['studio_id']) : 1;
$showtime_id = isset($_GET['showtime_id']) ? intval($_GET['showtime_id']) : 5;

// Ambil kursi yang sudah dibooking
$sql = "SELECT s.seat_row, s.seat_column
        FROM transaction_seats ts
        JOIN transactions t ON ts.transaction_id = t.transaction_id
        JOIN seats s ON ts.seat_id = s.seat_id
        WHERE ts.showtime_id = $showtime_id
          AND LOWER(t.status) = 'paid'";

$bookedSeatsRaw = $conn->query($sql);

$bookedSeats = [];
if ($bookedSeatsRaw) {
    while ($row = $bookedSeatsRaw->fetch_assoc()) {
        $bookedSeats[] = $row['seat_row'] . $row['seat_column']; // Contoh "A5"
    }
}

// Ambil semua kursi studio
$result = $conn->query("SELECT seat_row, seat_column FROM seats WHERE studio_id = $studio_id");
$seats = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $seats[] = ['row' => $row['seat_row'], 'column' => $row['seat_column']];
    }
}

// encode ke JSON
echo json_encode([
    'seats' => $seats,
    'bookedSeats' => $bookedSeats
]);

// debug
file_put_contents('debug_log.txt', print_r([
    'seats' => $seats,
    'bookedSeats' => $bookedSeats
], true));
?>
