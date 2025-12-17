<?php
require "../../config/connection.php";
require "../../includes/admin_auth.php";

$q = trim($_GET['q'] ?? '');

$sql = "
SELECT 
    t.created_at,
    u.username,
    m.title AS movie_title,
    s.show_time,
    st.name AS studio_name,
    t.total_price,
    t.status
FROM transactions t
JOIN users u ON t.user_id = u.user_id
JOIN showtimes s ON t.showtime_id = s.showtime_id
JOIN movies m ON s.movie_id = m.movie_id
JOIN studios st ON s.studio_id = st.studio_id
WHERE u.username LIKE ?
   OR m.title LIKE ?
ORDER BY t.created_at DESC
";

$stmt = $conn->prepare($sql);
$like = "%$q%";
$stmt->bind_param("ss", $like, $like);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows === 0){
    echo "<tr><td colspan='10' class='text-center'>Data tidak ditemukan</td></tr>";
    exit;
}

$no = 1;
while($row = $result->fetch_assoc()):
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
    <td><?= date('H:i', strtotime($row['created_at'])) ?></td>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['movie_title']) ?></td>
    <td><?= date('H:i', strtotime($row['show_time'])) ?></td>
    <td><?= htmlspecialchars($row['studio_name']) ?></td>
    <td>-</td>
    <td>Rp <?= number_format($row['total_price']) ?></td>
    <td>
        <span class="badge <?= $row['status'] === 'paid' ? 'bg-success' : 'bg-warning' ?>">
            <?= ucfirst($row['status']) ?>
        </span>
    </td>
</tr>
<?php endwhile; ?>
