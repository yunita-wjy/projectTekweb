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
        SELECT COUNT(*) AS tickets_today
        FROM transaction_seats ts
        JOIN transactions t ON ts.transaction_id = t.transaction_id
        WHERE t.status = 'paid'
        AND DATE(t.created_at) = CURDATE();
    ");
    $ticketsSoldToday = $q->fetch_assoc()['tickets_today'] ?? 0;

    // RECENT TRANSACTIONS (5 terakhir)
    $recentTransactions = [];
    $result = $conn->query("
        SELECT 
            t.transaction_id,
            t.created_at,
            u.username,
            m.title AS movie_title,
            s.start_time
        FROM transactions t
        JOIN users u ON t.user_id = u.user_id
        JOIN showtimes s ON t.showtime_id = s.showtime_id
        JOIN movies m ON s.movie_id = m.movie_id
        WHERE t.status = 'paid'
        ORDER BY t.created_at DESC
        LIMIT 5
    ");
    while($row = $result->fetch_assoc()){
        $recentTransactions[] = $row;
    }

    // DAILY TICKET SALES (7 hari terakhir)
    $dailySales = [];
    $q = $conn->query("
        SELECT 
            DATE(t.created_at) AS sale_date,
            COUNT(*) AS total
        FROM transactions t
        JOIN transaction_seats ts 
            ON t.transaction_id = ts.transaction_id
        WHERE t.status = 'paid'
        GROUP BY DATE(t.created_at)
        ORDER BY sale_date
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
            .card-title.bg-primary { background: #4ecdc4 !important; } /* keep bootstrap */
            .poster-thumb { width: 60px; height: 80px; object-fit:cover; border-radius:4px; }
            .required { color: #dc3545; }
            .ct-point {
                stroke-width: 6px;
            }



            .chart-card {
                background: #ffffff;
                border-radius: 18px;
                padding: 20px 22px;
                box-shadow: 0 12px 30px rgba(0,0,0,0.08);
                height: 250px;   /* 👈 tinggi card */
            }

            .chart-card .ct-chart {
                height: 100%;   /* 👈 chart isi penuh card */
            }


            .chart-title {
                font-weight: 600;
                margin-bottom: 15px;
                color: #0f172a;
            }

            /* Chartist Custom */
            .ct-series-a .ct-line {
                stroke: #38b2ac;
                stroke-width: 4px;
            }

            .ct-series-a .ct-point {
                stroke: #38b2ac;
                stroke-width: 8px;
            }

            .ct-area {
                fill: rgba(56, 178, 172, 0.25);
            }

            .ct-label {
                font-size: 12px;
                color: #6b7280;
            }



            .stat-card {
                display: flex;
                align-items: center;
                padding: 28px;
                border-radius: 18px;
                color: #ffffff;
                height: 140px;
                box-shadow: 0 12px 28px rgba(0,0,0,0.15);
                transition: all 0.25s ease;
                background: linear-gradient(
                    135deg,
                    #38b2ac,
                    #2c9a94
                );
            }

            .stat-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            }

            .stat-icon {
                font-size: 48px;
                margin-right: 20px;
                opacity: 0.95;
            }

            .stat-info h2 {
                font-size: 42px;
                font-weight: 800;
                margin: 0;
                line-height: 1;
            }

            .stat-info p {
                margin-top: 6px;
                font-size: 14px;
                letter-spacing: 0.6px;
                opacity: 0.9;
                text-transform: uppercase;
            }

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

                <div class="row g-4 mb-5 mx-2">

                    <div class="col-12 col-md-4">
                        <div class="stat-card theme-accent">
                            <div class="stat-icon">🎬</div>
                            <div class="stat-info">
                                <h2><?= $moviesNowShowing ?></h2>
                                <p>Movies Now Showing</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="stat-card theme-accent">
                            <div class="stat-icon">⏰</div>
                            <div class="stat-info">
                                <h2><?= $showtimesToday ?></h2>
                                <p>Showtimes Today</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="stat-card theme-accent">
                            <div class="stat-icon">🎟️</div>
                            <div class="stat-info">
                                <h2><?= $ticketsSoldToday ?></h2>
                                <p>Tickets Sold Today</p>
                            </div>
                        </div>
                    </div>

                </div>


                
                    
                
                    
                <div class="row px-4 mt-5">
                    <!-- recent transactions -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-8 mb-4">
                        <h3 class="mb-3">Recent Transactions</h3>
                        <table class="table table-hover align-middle custom-table">
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
                                            <td><?= date('d M Y', strtotime($t['created_at'])) ?></td>
                                            <td><?= date('H:i', strtotime($t['start_time'])) ?></td>
                                            <td><?= htmlspecialchars($t['username']) ?></td>
                                            <td><?= htmlspecialchars($t['movie_title']) ?></td>
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


                        <a href="transactions.php" class="btn btn-primary rounded-2">Show More</a>
                    </div>

                    <!-- daily ticket sales -->
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4 mb-4" > 
                        <h3 class="mb-3">Daily Ticket Sales</h3>
                        <!-- Chart container tanpa card -->
                        <div class="chart-card py-3">
                            <div id="ticket-sales-chart" class="ct-chart"></div>
                        </div>
                        <!-- <div style="
                            background: white;
                            border-radius: 8px;
                            padding: 20px;
                            border: 1px solid #ddd;
                            min-height: 300px;
                        ">
                            <div id="ticket-sales-chart" class="ct-chart" style="height:250px;"></div> -->



                    </div>
                </div>
            </div>



            



            
                
        
        </div>



    </div>






    <script>
        const salesData = <?= json_encode($dailySales) ?>;

        if (salesData.length > 0) {
            const labels = salesData.map(d => d.sale_date);
            const series = [ salesData.map(d => Number(d.total)) ];

            new Chartist.Line('#ticket-sales-chart', {
                labels: labels,
                series: series
            }, {
                low: 0,
                showArea: true,
                showPoint: true,
                fullWidth: true,
                chartPadding: { right: 30 },
                axisY: { onlyInteger: true }
            });
        }
        
    </script>

</body>


</html>


