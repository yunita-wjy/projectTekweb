<?php
    
    require "../config/connection.php";
    require "../includes/admin_auth.php";
    
    // if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    //     header("Location: ../auth/login.php");
    //     exit();
    // }

    // SIMULASI LOGIN ADMIN (sementara, tanpa login page)
    // if (!isset($_SESSION['user'])) {
    //     $q = $conn->query("SELECT user_id, username, full_name, email, role 
    //                     FROM users 
    //                     WHERE role = 'admin' 
    //                     LIMIT 1");
    //     $admin = $q->fetch_assoc();

    //     if ($admin) {
    //         $_SESSION['user'] = $admin;
    //     }
    // }

    $result = mysqli_query($conn, "SELECT * FROM studios");
    $studios = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // DELETE STUDIO 
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $id = (int) $_POST['delete_id'];

        $stmt = $conn->prepare("DELETE FROM studios WHERE studio_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: manage_studios.php");
        exit;
    }


    // ADD / UPDATE STUDIO
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $studio_name = trim($_POST['studio_name']);
        $status = $_POST['status'];
        $capacity = 100;

        $editing = !empty($_POST['studio_id']);
        $studio_id = $editing ? (int)$_POST['studio_id'] : null;

        // CEK DUPLIKASI NAMA STUDIO
        if ($editing) {
            $stmt = $conn->prepare("
                SELECT studio_id FROM studios
                WHERE studio_name = ? AND studio_id != ?
            ");
            $stmt->bind_param("si", $studio_name, $studio_id);
        } else {
            $stmt = $conn->prepare("
                SELECT studio_id FROM studios
                WHERE studio_name = ?
            ");
            $stmt->bind_param("s", $studio_name);
        }

        $stmt->execute();
        $check = $stmt->get_result();

        if ($check->num_rows > 0) {
            $_SESSION['error'] = "Nama studio sudah terdaftar.";
            header("Location: manage_studios.php");
            exit;
        }

        // SIMPAN DATA
        if ($editing) {
            $stmt = $conn->prepare("
                UPDATE studios
                SET studio_name = ?, status = ?
                WHERE studio_id = ?
            ");
            $stmt->bind_param("ssi", $studio_name, $status, $studio_id);
        } else {
            $stmt = $conn->prepare("
                INSERT INTO studios (studio_name, capacity, status)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("sis", $studio_name, $capacity, $status);
        }

        $stmt->execute();
        header("Location: manage_studios.php");
        exit;
    }




    // EDIT STUDIO
    $editStudio = null;

    if (isset($_GET['edit'])) {
        $id = (int) $_GET['edit'];
        $res = mysqli_query($conn, "SELECT * FROM studios WHERE studio_id = $id");
        $editStudio = mysqli_fetch_assoc($res);
    }




?>


<!DOCTYPE html>
<html>
    <head>
        <title>Admin Manage Showtimes</title>
        <!-- Boostrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- jQuery library -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
                    <a class="nav-link mb-1 active" href="manage_studios.php">Manage Studios</a>
                    <a class="nav-link mb-1" href="manage_prices.php">Manage Prices</a>
                    <a class="nav-link mb-1" href="manage_users.php">Manage Users</a>
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
                        <a class="nav-link text-white fw-bold" href="manage_studios.php">Manage Studios</a>
                        <a class="nav-link text-white" href="manage_prices.php">Manage Prices</a>
                        <a class="nav-link text-white" href="manage_users.php">Manage Users</a>
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
                            
                            <span class="navbar-brand ms-2 fw-bold">Admin - Manage Studios</span>
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

                <div class="container-fluid p-4">
                    <!-- LIST OF STUDIOS -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">LIST OF STUDIOS</h5>
                            <form class="d-flex">
                                <input id="searchTitle" class="form-control form-control-sm me-2" placeholder="Search Studio..." style="width: 200px;">
                                <button id="btnSearch" class="btn btn-outline-primary btn-sm" type="button" >Search</button>
                            </form>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Studio Name</th>
                                            <th>Capacity</th>
                                            <th>Status</th> <!--active/inactive-->
                                            <th>Action</th> <!--edit/delete-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($studios) > 0): ?>
                                            <?php foreach($studios as $index => $studio): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= htmlspecialchars($studio['studio_name']) ?></td>
                                                    <td><?= $studio['capacity'] ?></td>
                                                    <td>
                                                        <?php if($studio['status'] === 'active'): ?>
                                                            <span class="badge bg-success">Active</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="manage_studios.php?edit=<?= $studio['studio_id'] ?>"
                                                        class="btn btn-warning btn-sm">
                                                        Edit
                                                        </a>
                                                        <form method="POST" style="display:inline;">
                                                            <input type="hidden" name="delete_id" value="<?= $studio['studio_id'] ?>">
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Hapus studio ini?')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">Belum ada studio</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>

                                </table>

                            </div>
                        </div>

                    </div>

                    <!-- FORM ADD STUDIO -->
                    <div class="card mt-4 mb-4 shadow-sm" style="width: 100%; ">
                        <!-- card header -->
                        <div class="card-header bg-primary text-white fw-bold">
                            <?= $editStudio ? 'Edit Studio' : 'Add Studio' ?>
                        </div>

                        <!-- card body -->
                        <div class="card-body px-4">

                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger">
                                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST">
                                <input type="hidden" name="studio_id"
                                    value="<?= $editStudio['studio_id'] ?? '' ?>">

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Studio's Name</label>
                                    <div class="col-sm-9">
                                        <input class="form-control"
                                            name="studio_name"
                                            value="<?= $editStudio['studio_name'] ?? '' ?>"
                                            required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Capacity</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" value="100" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Status</label>
                                    <div class="col-sm-9">
                                        <select name="status" class="form-select" required>
                                            <option value="active"
                                                <?= ($editStudio['status'] ?? '') === 'active' ? 'selected' : '' ?>>
                                                Active
                                            </option>
                                            <option value="inactive"
                                                <?= ($editStudio['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <?php if ($editStudio): ?>
                                        <a href="manage_studios.php" class="btn btn-secondary ms-2">Cancel</a>
                                        <button class="btn btn-warning">Save Changes</button>
                                    <?php else: ?>
                                        <button class="btn btn-success">Save</button>
                                    <?php endif; ?>
                                </div>
                            </form>

                        
                        </div>

                    </div>
                </div>
            </div>











        </div>



        

        

    </body>
</html>