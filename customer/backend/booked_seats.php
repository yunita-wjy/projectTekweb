<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require('../../config/connection.php');
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Misal studio_id kamu dapat dari request atau set manual dulu
$studio_id = 1;

$showtime_id = 5; // contoh

$sql = "SELECT s.seat_row, s.seat_col
        FROM transaction_seats ts
        JOIN transactions t ON ts.transaction_id = t.transaction_id
        JOIN seats s ON ts.seat_id = s.seat_id
        WHERE ts.showtime_id = :showtime_id
          AND t.status = 'paid'";

$stmt = $conn->prepare($sql);
$stmt->execute(['showtime_id' => $showtime_id]);
$bookedSeatsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);

$bookedSeats = [];
foreach ($bookedSeatsRaw as $seat) {
    $bookedSeats[] = $seat['seat_row'] . $seat['seat_col']; // Contoh "A5"
}


// Query ambil kursi untuk studio tersebut
$result = $conn->query("SELECT seat_row, seat_col FROM seats WHERE studio_id = $studio_id");
$seats = []; // array kosong
while ($row = $result->fetch()) {
    $seats[] = ['row' => $row['seat_row'], 'col' => $row['seat_col']];
}
// encode ke JSON
echo json_encode([
    'seats' => $seats,
    'bookedSeats' => $bookedSeats
]);
file_put_contents('debug_log.txt', print_r(json_encode($bookedSeats), true));  // Simpan ke file untuk cek
?>