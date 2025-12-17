<?php
    
    require "../config/connection.php";
    require "../includes/admin_auth.php";
    
    // if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    //     header("Location: ../auth/login.php");
    //     exit();
    // }

    // VALIDASI UNTUK TAMBAH USER
    // if ($role === 'admin') {
    //     if (!str_ends_with($email, '@filmverse.ac.id')) {
    //         flash('danger', 'Admin harus menggunakan email @filmverse.ac.id');
    //         header("Location: user_admin.php");
    //         exit;
    //     }
    // }


    // Akses database Users dari database
    $result = mysqli_query($conn, "SELECT * FROM users ORDER BY full_name ASC");
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // CEK ACTION (EDIT/DELETE)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $action = $_POST['action'];
        $id = (int) $_POST['user_id'];

        if($action === 'update'){
            // ambil data dari form update
            $username = trim($_POST['username']);
            $full_name = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $role = $_POST['role'];

            // validasi unik dsb...
            $stmt = $conn->prepare("UPDATE users SET username=?, full_name=?, email=?, phone=?, role=? WHERE user_id=?");
            $stmt->bind_param("sssssi", $username, $full_name, $email, $phone, $role, $id);
            $stmt->execute();

        } elseif($action === 'delete'){
            // cek role sebelum delete
            $res = mysqli_query($conn, "SELECT role FROM users WHERE user_id = $id");
            $u = mysqli_fetch_assoc($res);

            if($u['role'] !== 'admin'){
                $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
            }
        }

        header("Location: manage_users.php");
        exit;
    }


    // DELETE USER
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
        $id = (int) $_POST['user_id'];

        // Cek dulu role user sebelum delete
        $res = mysqli_query($conn, "SELECT role FROM users WHERE user_id = $id");
        $u = mysqli_fetch_assoc($res);

        if($u['role'] !== 'admin'){
            $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
        }

        header("Location: manage_users.php");
        exit;
    }




?>

<!DOCTYPE html>
<html>
        <head> 
        <title>Admin Manage Users</title>
        <!-- Boostrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- jQuery library -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Chartist -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
        <style>
            :root{ --accent: #4ecdc4; }
            body { background: #f8f9fb; }
            .sidebar { width: 220px; min-height:100vh; background: #111827; color:#fff; }
            .sidebar a { color: #fff; text-decoration:none; }
            .sidebar .nav-link.active { background: rgba(255,255,255,0.06); font-weight:700; color:var(--accent); }
            .sidebar .nav-link:not(.active):hover { color: #ff4c29; }
            .card-title.bg-primary { background:#0d6efd !important; } /* keep bootstrap */
            .poster-thumb { width: 60px; height: 80px; object-fit:cover; border-radius:4px; }
            .required { color: #dc3545; }
        </style>
    </head>

    <body>
        <div class="d-flex">
            <!-- SIDEBAR (dekstop)-->
            <div class="sidebar p-3 d-none d-md-block">
                <div class="d-flex align-items-center mb-4">
                    <img src="../assets/filmVerse-dark.png" width="44" class="me-2">
                    <div><strong>FilmVerse</strong>
                    <div style="font-size:12px; color:#9CA3AF">Admin Panel</div></div>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link mb-1" href="dashboard.php">Dashboard</a>
                    <a class="nav-link mb-1" href="manage_movies.php">Manage Movies</a>
                    <a class="nav-link mb-1" href="manage_showtimes.php">Manage Showtimes</a>
                    <a class="nav-link mb-1" href="manage_studios.php">Manage Studios</a>
                    <a class="nav-link mb-1" href="manage_prices.php">Manage Prices</a>
                    <a class="nav-link mb-1 active" href="manage_users.php">Manage Users</a>
                    <a class="nav-link mb-1" href="transactions.php">Transactions</a>
                </nav>
            </div>

            <!-- SIDEBAR OFFCANVAS (mobile) -->
            <div class="offcanvas offcanvas-start sidebar p-3 text-bg-dark d-md-none" tabindex="-1" id="sideMenu">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title">FilmVerse</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body p-3">
                    <nav class="nav flex-column">
                        <a class="nav-link text-white" href="dashboard.php">Dashboard</a>
                        <a class="nav-link text-white" href="manage_movies.php">Manage Movies</a>
                        <a class="nav-link text-white" href="manage_showtimes.php">Manage Showtimes</a>
                        <a class="nav-link text-white" href="manage_studios.php">Manage Studios</a>
                        <a class="nav-link text-white" href="manage_prices.php">Manage Prices</a>
                        <a class="nav-link text-white fw-bold" href="manage_users.php">Manage Users</a>
                        <a class="nav-link text-white" href="transactions.php">Transactions</a>
                    </nav>
                </div>
            </div>

            <!-- NAVBAR & CONTENT -->
            <div class="flex-grow-1">
                <!-- NAVBAR -->
                <nav class="navbar navbar-dark bg-dark px-4">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center">
                            <button class="navbar-toggler d-md-none"
                                    type="button"
                                    data-bs-toggle="offcanvas"
                                    data-bs-target="#sideMenu">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            
                            <span class="navbar-brand ms-2 fw-bold">Admin - Manage Users</span>
                        </div>

                        <div class="d-flex align-items-center">
                            <span class="text-white me-4 fw-bold">ADMIN</span>
                            <div class="dropdown me-2">
                                <button class="btn btn-outline-light btn-sm dropdown-toggle text-uppercase"
                                        data-bs-toggle="dropdown">
                                    <?= htmlspecialchars($_SESSION['user']['full_name']) ?>
                                </button>                        
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item text-danger" href="../auth/logout.php">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- CONTENT -->
                <div class="container-fluid p-4">
                    <!-- LIST OF USERS -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">LIST OF USERS</h5>
                            <form class="d-flex">
                                <input id="searchTitle" class="form-control form-control-sm me-2" placeholder="Search Username / Name..." style="width: 200px;">
                            </form>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Username</th>
                                            <th>Name</th> 
                                            <th>Email</th> 
                                            <th>Phone Number</th> 
                                            <th>Role</th> <!--customer, admin-->
                                            <th>Action</th> <!--edit, delete-->
                                        </tr>
                                    </thead>
                                    <tbody id="userTableBody">
                                    <?php if(count($users) > 0): ?>
                                        <?php foreach($users as $index => $user): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= htmlspecialchars($user['username']) ?></td>
                                                <td><?= htmlspecialchars($user['full_name']) ?></td>
                                                <td><?= htmlspecialchars($user['email']) ?></td>
                                                <td><?= htmlspecialchars($user['phone']) ?></td>
                                                <td>
                                                    <?php if($user['role'] === 'admin'): ?>
                                                        <span class="badge bg-warning text-dark">Admin</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Customer</span>
                                                    <?php endif; ?>
                                                </td>
                                                <!-- Action buttons -->
                                                <td>
                                                    <?php if($user['role'] !== 'admin'): ?>
                                                        <button 
                                                            class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editUserModal"
                                                            data-id="<?= $user['user_id'] ?>"
                                                            data-username="<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>"
                                                            data-fullname="<?= htmlspecialchars($user['full_name'], ENT_QUOTES) ?>"
                                                            data-email="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>"
                                                            data-phone="<?= htmlspecialchars($user['phone'], ENT_QUOTES) ?>"
                                                            data-role="<?= $user['role'] ?>"
                                                        >
                                                            Edit
                                                        </button>
                                                        <form method="POST" action="" style="display:inline;">
                                                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                                            <button type="submit" name="action" class="btn btn-danger btn-sm" value="delete"
                                                                onclick="return confirm('Hapus user ini?')">Delete</button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Belum ada user</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>

                                </table>

                            </div>
                        </div>
                    </div>


            </div>







        </div>

        <!-- EDIT USER MODAL -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <form method="POST" id="editUserForm">
                <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <input type="hidden" name="user_id" id="modal_user_id">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" id="modal_username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" id="modal_fullname" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="modal_email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="modal_phone" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" id="modal_role" class="form-select" required>
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                    </select>
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-warning" name="action" value="update">Save Changes</button>
                </div>
            </form>
            </div>
        </div>
        </div>


        
        <script>
            // Search User
            const searchInput = document.getElementById("searchTitle");
            const tbody = document.getElementById("userTableBody");

            searchInput.addEventListener('keyup', function () {
                const keyword = this.value;

                fetch(`ajax/search_users.php?q=${encodeURIComponent(keyword)}`)
                    .then(res => res.text())
                    .then(html => {
                        tbody.innerHTML = html;
                    });
            });


            var editUserModal = document.getElementById('editUserModal')
            editUserModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget
            var id = button.getAttribute('data-id')
            var username = button.getAttribute('data-username')
            var fullname = button.getAttribute('data-fullname')
            var email = button.getAttribute('data-email')
            var phone = button.getAttribute('data-phone')
            var role = button.getAttribute('data-role')

            // Set value ke modal
            document.getElementById('modal_user_id').value = id
            document.getElementById('modal_username').value = username
            document.getElementById('modal_fullname').value = fullname
            document.getElementById('modal_email').value = email
            document.getElementById('modal_phone').value = phone
            document.getElementById('modal_role').value = role
            })
        </script>

        

    </body>
</html>