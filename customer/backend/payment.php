<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require('../../config/connection.php');
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$raw_input = file_get_contents('php://input');
file_put_contents('debug_log.txt', $raw_input);  // Simpan ke file untuk cek
$data = json_decode($raw_input, true);
if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

$user_id = $_SESSION['user']['user_id'];
// $movie_id = $data['movie_id'];
// $studio_id = $data['studio_id'];
// $showtime_id = $data['showtime_id'];
$showtime_id = 5; // temporary
$seats = $data['seats'];
$total_price = $data['total_price'];
$ticketQty = $data['tickets_qty'];
$status = 'paid';

file_put_contents('debug_log.txt', print_r([
    'user_id' => $user_id,
    'total_price' => $total_price,
    'seats' => $seats,
    'showtime_id' => $showtime_id,
    'ticketQty' => $ticketQty,
    'status' => $status
], true));

$query = "INSERT INTO transactions (`user_id`, `total_price`, `showtime_id`, `tickets_qty`, `status`) 
    VALUES ('$user_id', '$total_price', '$showtime_id', '$ticketQty', '$status')";

// header('Location: movies_detail.php?payment=success');

if ($conn->query($query)) {
// Ambil transaction_id yang baru dibuat
$transaction_id = $conn->lastInsertId();
foreach ($seats as $seatData) {
    $row = substr($seatData, 0, 1);
    $col = (int) substr($seatData, 1);

    // Cari seat_id dari tabel seats
    $stmt = $conn->prepare("SELECT seat_id FROM seats WHERE seat_row = ? AND seat_col = ?");
    $stmt->execute([$row, $col]);
    $seat = $stmt->fetch();

    if ($seat) {
        $seat_id = $seat['seat_id'];
        $conn->query("INSERT INTO transaction_seats (transaction_id, seat_id, showtime_id) VALUES ('$transaction_id', '$seat_id', '$showtime_id')");
    }
}
    echo json_encode(['success' => true, 'message' => 'Payment successful']);
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
exit;
