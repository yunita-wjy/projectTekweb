<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require('../../config/connection.php');
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Ambil showtime_id dari GET 
// if (!isset($_GET['showtime_id'])) {
//     http_response_code(400);
//     echo json_encode(['success' => false, 'message' => 'showtime_id is required']);
//     exit();
// }
// $showtime_id = (int)$_GET['showtime_id'];

// $sqlStudio = "SELECT studio_id FROM showtimes WHERE showtime_id = :showtime_id LIMIT 1";
// $stmt = $conn->prepare($sqlStudio);
// $stmt->execute(['showtime_id' => $showtime_id]);
// $studio = $stmt->fetch(PDO::FETCH_ASSOC);
// if (!$studio) {
//     http_response_code(404);
//     echo json_encode(['success' => false, 'message' => 'Showtime not found']);
//     exit();
// }
// $studio_id = $studio['studio_id'];

// Data Dummy
$studio_id = 1;
$showtime_id = 5; // contoh

$sqlBooked = "SELECT s.seat_row, s.seat_col
        FROM transaction_seats ts
        JOIN transactions t ON ts.transaction_id = t.transaction_id
        JOIN seats s ON ts.seat_id = s.seat_id
        WHERE ts.showtime_id = :showtime_id
          AND t.status = 'paid'";

$stmt = $conn->prepare($sqlBooked);
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