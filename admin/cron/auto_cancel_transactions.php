<?php
require "../config/connection.php";

// ambil transaksi unpaid > 1 jam
$sql = "
    SELECT transaction_id
    FROM transactions
    WHERE status = 'unpaid'
      AND created_at < NOW() - INTERVAL 1 HOUR
";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $transaction_id = $row['transaction_id'];

    // 1. ubah status jadi cancelled
    $stmt = $conn->prepare("
        UPDATE transactions
        SET status = 'cancelled'
        WHERE transaction_id = ?
    ");
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();

    // 2. RELEASE SEAT (INI DIA TEMPAT QUERY KAMU)
    $stmt = $conn->prepare("
        DELETE FROM transaction_seats
        WHERE transaction_id = ?
    ");
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
}
