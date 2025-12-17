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

    /* ================= DASHBOARD DATA ================= */

    // MOVIES NOW SHOWING (yang masih aktif & punya showtime hari ini)
    $q = $conn->query("
        SELECT COUNT(DISTINCT m.movie_id) total
        FROM movies m
        JOIN showtimes s ON m.movie_id = s.movie_id
        WHERE m.status = 'active'
        AND s.show_date = CURDATE()
    ");
    $moviesNowShowing = $q->fetch_assoc()['total'] ?? 0;

    // SHOWTIMES TODAY
    $q = $conn->query("
        SELECT COUNT(*) total
        FROM showtimes
        WHERE show_date = CURDATE()
    ");
    $showtimesToday = $q->fetch_assoc()['total'] ?? 0;

    // TICKETS SOLD TODAY (hanya PAID)
    $q = $conn->query("
        SELECT COUNT(t.ticket_id) total
        FROM tickets t
        JOIN transactions tr ON t.transaction_id = tr.transaction_id
        JOIN showtimes s ON t.showtime_id = s.showtime_id
        WHERE tr.payment_status = 'PAID'
        AND s.show_date = CURDATE()
    ");
    $ticketsSoldToday = $q->fetch_assoc()['total'] ?? 0;

    // RECENT TRANSACTIONS (5 terakhir)
    $recentTransactions = [];
    $q = $conn->query("
        SELECT 
            tr.created_at,
            u.username,
            m.title
        FROM transactions tr
        JOIN users u ON tr.user_id = u.user_id
        JOIN tickets t ON tr.transaction_id = t.transaction_id
        JOIN showtimes s ON t.showtime_id = s.showtime_id
        JOIN movies m ON s.movie_id = m.movie_id
        WHERE tr.payment_status = 'PAID'
        GROUP BY tr.transaction_id
        ORDER BY tr.created_at DESC
        LIMIT 5
    ");
    while($row = $q->fetch_assoc()){
        $recentTransactions[] = $row;
    }

    // DAILY TICKET SALES (7 hari terakhir)
    $dailySales = [];
    $q = $conn->query("
        SELECT 
            s.show_date,
            COUNT(t.ticket_id) total
        FROM tickets t
        JOIN transactions tr ON t.transaction_id = tr.transaction_id
        JOIN showtimes s ON t.showtime_id = s.showtime_id
        WHERE tr.payment_status = 'PAID'
        AND s.show_date >= CURDATE() - INTERVAL 6 DAY
        GROUP BY s.show_date
        ORDER BY s.show_date ASC
    ");
    while($row = $q->fetch_assoc()){
        $dailySales[] = $row;
    }


    
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Admin Dashboard</title>
        <!-- Boostrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- jQuery library -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Chartist -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
        <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>

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
                <a class="nav-link mb-1 active" href="dashboard.php">Dashboard</a>
                <a class="nav-link mb-1" href="manage_movies.php">Manage Movies</a>
                <a class="nav-link mb-1" href="manage_showtimes.php">Manage Showtimes</a>
                <a class="nav-link mb-1" href="manage_studios.php">Manage Studios</a>
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
                    <a class="nav-link text-white fw-bold" href="dashboard.php">Dashboard</a>
                    <a class="nav-link text-white" href="manage_movies.php">Manage Movies</a>
                    <a class="nav-link text-white" href="manage_showtimes.php">Manage Showtimes</a>
                    <a class="nav-link text-white" href="manage_studios.php">Manage Studios</a>
                    <a class="nav-link text-white" href="manage_prices.php">Manage Prices</a>
                    <a class="nav-link text-white" href="manage_users.php">Manage Users</a>
                    <a class="nav-link text-white" href="transactions.php">Transactions</a>
                </nav>
            </div>
        </div>

        
        <!-- NAVBAR & CONTENT-->
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
                        
                        <span class="navbar-brand ms-2 fw-bold">Dashboard</span>
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


                <h1 class="p-4">Welcome, Admin!</h1>

                <div class="d-flex justify-content-center gap-5 flex-wrap">
                    <div class="card shadow-sm text-center" style="width: 300px; height: 200px;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center bg-info text-white">
                            <h2 class="fw-bold mb-4" style="font-size: 40px">
                                <?= $moviesNowShowing ?>
                            </h2>
                            <p class="mb-0 text-white" style="font-size: 20px;">MOVIES NOW SHOWING</p>
                        </div>
                    </div>

                    <div class="card shadow-sm text-center" style="width: 300px; height: 200px;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center bg-info text-white">
                            <h2 class="fw-bold mb-4" style="font-size: 40px">
                                <?= $showtimesToday ?>
                            </h2>
                            <p class="mb-0 text-white"  style="font-size: 20px;">SHOWTIMES TODAY</p>
                        </div>
                    </div>

                    <div class="card shadow-sm text-center" style="width: 300px; height: 200px;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center bg-info text-white">
                            <h2 class="fw-bold mb-4" style="font-size: 40px">
                                <?= $ticketsSoldToday ?>
                            </h2>
                            <p class="mb-0  text-white"  style="font-size: 20px;" >TICKETS SOLD TODAY</p>
                        </div>
                    </div>
                </div>

                
                    
                
                    
                <div class="row px-4 mt-5">
                    <!-- recent transactions -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-8 mb-4">
                        <h3 class="mb-3">Recent Transactions</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>User</th>
                                    <th>Movie</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(count($recentTransactions) > 0): ?>
                                <?php foreach($recentTransactions as $i => $t): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= date('Y-m-d', strtotime($t['created_at'])) ?></td>
                                        <td><?= date('H:i', strtotime($t['created_at'])) ?></td>
                                        <td><?= htmlspecialchars($t['username']) ?></td>
                                        <td><?= htmlspecialchars($t['title']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        No transactions today
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>

                        <button class="btn btn-primary rounded-2">Show More</button>
                    </div>

                    <!-- daily ticket sales -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4 mb-4">
                        <h3 class="mb-3">Daily Ticket Sales</h3>
                        <!-- Chart container tanpa card -->
                        <div style="
                            background: white;
                            border-radius: 8px;
                            padding: 20px;
                            border: 1px solid #ddd;
                            min-height: 300px;
                        ">
                            <div id="ticket-sales-chart" style="height: 250px;"></div>

                        <!-- BUTUH AMBIL DATA LEWAT PHP -->
                            <!-- <div class="card">
                            <div class="card-body">
                                <div id="ticket-sales-chart"></div>
                            </div>
                        </div> -->

                    </div>
                </div>
            </div>


            



            
                
        
        </div>



    </div>






    <script>
        const salesData = <?= json_encode($dailySales) ?>;

        const labels = salesData.map(d => d.show_date);
        const series = [ salesData.map(d => d.total) ];

        new Chartist.Line('#ticket-sales-chart', {
            labels: labels,
            series: series
        }, {
            low: 0,
            showArea: true,
            fullWidth: true,
            chartPadding: {
                right: 30
            }
        });
    </script>

</body>


</html>


