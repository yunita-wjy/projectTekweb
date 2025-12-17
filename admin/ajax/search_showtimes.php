<?php
require "../../config/connection.php";

$keyword = $_GET['q'] ?? '';

$sql = "
    SELECT s.showtime_id,
           m.title,
           st.studio_name,
           s.show_date,
           s.start_time,
           s.end_time,
           CASE 
                WHEN DAYOFWEEK(s.show_date) IN (1,7)
                THEN p.weekend_price
                ELSE p.weekday_price
           END AS price
    FROM showtimes s
    JOIN movies m ON s.movie_id = m.movie_id
    JOIN studios st ON s.studio_id = st.studio_id
    JOIN prices p
    WHERE m.title LIKE ?
    ORDER BY s.show_date DESC
";

$stmt = $conn->prepare($sql);
$like = "%$keyword%";
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();

$no = 1;
while ($row = $result->fetch_assoc()):
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($row['title']) ?></td>
    <td><?= htmlspecialchars($row['studio_name']) ?></td>
    <td><?= $row['show_date'] ?></td>
    <td><?= substr($row['start_time'],0,5) ?></td>
    <td><?= substr($row['end_time'],0,5) ?></td>
    <td><?= number_format($row['price']) ?></td>
    <td>
        <a href="manage_showtimes.php?edit_id=<?= $row['showtime_id'] ?>"
           class="btn btn-warning btn-sm">Edit</a>
    </td>
</tr>
<?php endwhile; ?>
