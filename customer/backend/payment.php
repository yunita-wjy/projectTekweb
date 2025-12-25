<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require('../../config/connection.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

function generateBookingCode($conn) {
    do {
        $code = strtoupper(substr(md5(uniqid()), 0, 5));
        $stmt = $conn->prepare("SELECT transaction_id FROM transactions WHERE booking_code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
    } while ($result->num_rows > 0);

    return $code;
}

// Ambil data JSON
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit();
}

$user_id     = $_SESSION['user']['user_id'];
$showtime_id = $data['showtime_id'];
$seats       = $data['seats'];
$total_price = $data['total_price'];
$tickets_qty = count($seats);
$status      = 'paid';

$booking_code = generateBookingCode($conn);

// Ambil Studio
$studioStmt = $conn->prepare("
    SELECT studio_id 
    FROM showtimes 
    WHERE showtime_id = ?
");
$studioStmt->bind_param("i", $showtime_id);
$studioStmt->execute();
$studio = $studioStmt->get_result()->fetch_assoc();

if (!$studio) {
    echo json_encode(['success' => false, 'message' => 'Studio not found']);
    exit();
}

$studio_id = $studio['studio_id'];


// 1️) INSERT TRANSACTION
$stmt = $conn->prepare("
    INSERT INTO transactions 
    (user_id, total_price, showtime_id, tickets_qty, status, booking_code)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "iiiiss",
    $user_id,
    $total_price,
    $showtime_id,
    $tickets_qty,
    $status,
    $booking_code
);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Transaction failed']);
    exit();
}

$transaction_id = $conn->insert_id;

// 2️) INSERT SEATS
foreach ($seats as $seat) {
    $row = substr($seat, 0, 1);
    $col = (int) substr($seat, 1);

    $seatStmt = $conn->prepare("
        SELECT seat_id 
        FROM seats 
        WHERE seat_row = ? 
            AND seat_column = ?
            AND studio_id = ?
    ");

    $seatStmt->bind_param("sii", $row, $col, $studio_id);
    $seatStmt->execute();
    $seatResult = $seatStmt->get_result()->fetch_assoc();


    if ($seatResult) {
        $seat_id = $seatResult['seat_id'];

        $insertSeat = $conn->prepare("
            INSERT INTO transaction_seats (transaction_id, seat_id, showtime_id)
            VALUES (?, ?, ?)
        ");
        $insertSeat->bind_param("iii", $transaction_id, $seat_id, $showtime_id);
        $insertSeat->execute();
    }
}

// 3️) RESPONSE
echo json_encode([
    'success' => true,
    'booking_code' => $booking_code,
    'transaction_id' => $transaction_id
]);
exit();
?>