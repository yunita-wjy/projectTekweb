<?php
session_start();
header('Content-Type: application/json');
require "../../config/connection.php";

$showtime_id = $_GET['showtime_id'] ?? 0;

if (!$showtime_id) {
    echo json_encode(['bookedSeats' => []]);
    exit;
}

$stmt = $conn->prepare("
    SELECT s.seat_row, s.seat_column
    FROM transaction_seats ts
    JOIN seats s ON ts.seat_id = s.seat_id
    WHERE ts.showtime_id = ?
");
$stmt->bind_param("i", $showtime_id);
$stmt->execute();
$result = $stmt->get_result();

$bookedSeats = [];
while ($row = $result->fetch_assoc()) {
    $bookedSeats[] = $row['seat_row'] . $row['seat_column'];
}

echo json_encode([
    'bookedSeats' => $bookedSeats
]);
?>