<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require('../../config/connection.php');
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$movie_id = (int) $_GET['movie_id'];
$sql = "SELECT DISTINCT show_date, show_time FROM showtimes WHERE movie_id = :movie_id ORDER BY show_date, show_time";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':movie_id', $movie_id);
$stmt->execute();
$showtimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$dates = array_unique(array_column($showtimes, 'show_date'));
$times = array_unique(array_column($showtimes, 'show_time'));

$data = [
  'dates' => $dates,
  'showtimes' => $times
];
echo json_encode($data);
?>
