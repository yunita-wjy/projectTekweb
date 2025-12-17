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

    // Ambil harga current dari prices database
    $result = mysqli_query($conn, "SELECT weekday_price, weekend_price FROM prices LIMIT 1");
    $price = mysqli_fetch_assoc($result);

    // Jika form submit, update harga
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ambil nilai input
        $new_weekday = (int) $_POST['weekday_price'];
        $new_weekend = (int) $_POST['weekend_price'];

        // Update ke database
        $stmt = $conn->prepare("UPDATE prices SET weekday_price = ?, weekend_price = ? LIMIT 1");
        $stmt->bind_param("ii", $new_weekday, $new_weekend);
        $stmt->execute();

        // Redirect supaya harga terbaru tampil
        header("Location: manage_prices.php");
        exit;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin Manage</title>
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
                    <a class="nav-link mb-1 " href="manage_movies.php">Manage Movies</a>
                    <a class="nav-link mb-1" href="manage_showtimes.php">Manage Showtimes</a>
                    <a class="nav-link mb-1" href="manage_studios.php">Manage Studios</a>
                    <a class="nav-link mb-1 active" href="manage_prices.php">Manage Prices</a>
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
                        <a class="nav-link text-white" href="manage_studios.php">Manage Studios</a>
                        <a class="nav-link text-white  fw-bold" href="manage_prices.php">Manage Prices</a>
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
                            
                            <span class="navbar-brand ms-2 fw-bold">Admin - Manage Prices</span>
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
                    <div class="container mt-5" style="max-width: 600px;">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white fw-bold">
                                PRICE SETTINGS
                            </div>

                            <!-- CARD BODY MULAI DI SINI -->
                            <div class="card-body">

                                <!-- CURRENT PRICES -->
                                <div class="mb-4 p-3 border rounded bg-light">
                                    <h5 class="fw-bold mb-3">Current Prices</h5>

                                    <ul class="ms-1">
                                        <li><strong>Weekday Price:</strong> <span class="text-primary fw-semibold">Rp <?= number_format($price['weekday_price'],0,',','.') ?></span></li>
                                        <li><strong>Weekend Price:</strong> <span class="text-primary fw-semibold">Rp <?= number_format($price['weekend_price'],0,',','.') ?></span></li>
                                    </ul>
                                </div>

                                <!-- UPDATE FORM -->
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">New Weekday Price</label>
                                        <input type="number" class="form-control" name="weekday_price" value="<?= $price['weekday_price'] ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">New Weekend Price</label>
                                        <input type="number" class="form-control" name="weekend_price" value="<?= $price['weekend_price'] ?>" required>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-warning">Save Changes</button>
                                    </div>
                                </form>

                            </div> <!-- card-body -->

                        </div> <!-- card -->
                    </div>
                </div>






            </div>
            </div>



        
    </body>


</html>