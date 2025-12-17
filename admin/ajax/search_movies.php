<?php
require "../../config/connection.php";

$q = $_GET['q'] ?? '';

$sql = "
SELECT m.movie_id, m.title, m.duration, m.start_date, m.end_date, 
       m.poster_path, m.status,
       GROUP_CONCAT(g.genre_name SEPARATOR ', ') AS genres
FROM movies m
LEFT JOIN movie_genre mg ON m.movie_id = mg.movie_id
LEFT JOIN genres g ON mg.genre_id = g.genre_id
WHERE m.title LIKE ?
GROUP BY m.movie_id
ORDER BY m.movie_id DESC
";

$stmt = $conn->prepare($sql);
$like = "%$q%";
$stmt->bind_param("s", $like);
$stmt->execute();
$res = $stmt->get_result();

$no = 1;
while ($m = $res->fetch_assoc()):
?>
<tr>
    <td><?= $no++ ?></td>
    <td>
        <?php if($m['poster_path']): ?>
            <img src="../<?= htmlspecialchars($m['poster_path']) ?>" class="poster-thumb">
        <?php else: ?>
            <div class="border rounded poster-thumb" style="background:#e9ecef;"></div>
        <?php endif; ?>
    </td>
    <td><?= htmlspecialchars($m['title']) ?></td>
    <td><?= htmlspecialchars($m['genres'] ?? '-') ?></td>
    <td><?= $m['duration'] ?></td>
    <td><?= $m['start_date'] ?></td>
    <td><?= $m['end_date'] ?></td>
    <td>
        <?php if($m['status'] === 'active'): ?>
            <span class="badge bg-success">Active</span>
        <?php elseif($m['status'] === 'coming_soon'): ?>
            <span class="badge bg-info text-dark">Coming Soon</span>
        <?php else: ?>
            <span class="badge bg-secondary">Inactive</span>
        <?php endif; ?>
    </td>
    <td class="text-muted">Reload page to edit</td>
</tr>
<?php endwhile; ?>
