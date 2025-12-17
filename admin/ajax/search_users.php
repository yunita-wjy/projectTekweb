<?php
require "../../config/connection.php";
require "../../includes/admin_auth.php";

$keyword = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM users
        WHERE username LIKE ?
           OR full_name LIKE ?
        ORDER BY full_name ASC";

$stmt = $conn->prepare($sql);
$like = "%$keyword%";
$stmt->bind_param("ss", $like, $like);
$stmt->execute();

$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);

if(count($users) === 0){
    echo "<tr><td colspan='7' class='text-center'>User tidak ditemukan</td></tr>";
    exit;
}

foreach($users as $i => $user){
?>
<tr>
    <td><?= $i + 1 ?></td>
    <td><?= htmlspecialchars($user['username']) ?></td>
    <td><?= htmlspecialchars($user['full_name']) ?></td>
    <td><?= htmlspecialchars($user['email']) ?></td>
    <td><?= htmlspecialchars($user['phone']) ?></td>
    <td>
        <?= $user['role'] === 'admin'
            ? "<span class='badge bg-warning text-dark'>Admin</span>"
            : "<span class='badge bg-secondary'>Customer</span>" ?>
    </td>
    <td>
        <?php if($user['role'] !== 'admin'): ?>
            <button class="btn btn-warning btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#editUserModal"
                data-id="<?= $user['user_id'] ?>"
                data-username="<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>"
                data-fullname="<?= htmlspecialchars($user['full_name'], ENT_QUOTES) ?>"
                data-email="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>"
                data-phone="<?= htmlspecialchars($user['phone'], ENT_QUOTES) ?>"
                data-role="<?= $user['role'] ?>">
                Edit
            </button>
        <?php else: ?>
            <span class="text-muted">-</span>
        <?php endif; ?>
    </td>
</tr>
<?php } ?>
