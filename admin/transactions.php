<?php
require "../config/connection.php";
require "../includes/admin_auth.php";

$limit = 15;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$q = $_GET['q'] ?? '';
$date = $_GET['date'] ?? '';

$where = [];
$params = [];
$types = "";

if ($q) {
    $where[] = "u.username LIKE ?";
    $params[] = "%$q%";
    $types .= "s";
}
if ($date) {
    $where[] = "DATE(t.created_at) = ?";
    $params[] = $date;
    $types .= "s";
}

$sql = "
SELECT 
    t.transaction_id,
    t.created_at,
    u.username,
    m.title AS movie_title,
    st.studio_name,
    s.start_time,
    t.tickets_qty,
    t.total_price,
    t.status
FROM transactions t
JOIN users u ON t.user_id = u.user_id
JOIN showtimes s ON t.showtime_id = s.showtime_id
JOIN movies m ON s.movie_id = m.movie_id
JOIN studios st ON s.studio_id = st.studio_id
";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

// HITUNG TOTAL DATA UNTUK PAGINATION
$countSql = "SELECT COUNT(*) AS total 
FROM transactions t
JOIN users u ON t.user_id = u.user_id
JOIN showtimes s ON t.showtime_id = s.showtime_id
JOIN movies m ON s.movie_id = m.movie_id
JOIN studios st ON s.studio_id = st.studio_id";

if ($where) {
    $countSql .= " WHERE " . implode(" AND ", $where);
}

$totalStmt = $conn->prepare($countSql);
if ($params) {
    $totalStmt->bind_param($types, ...$params);
}

$totalStmt->execute();
$totalStmt->bind_result($totalCount);
$totalStmt->fetch();
$totalStmt->close();

$totalPages = ceil($totalCount / $limit);



$sql .= " ORDER BY t.created_at DESC LIMIT ?, ?";
$params[] = $offset;
$params[] = $limit;
$types .= "ii";

$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$transactions = $stmt->get_result();

?>


<!DOCTYPE html>
<html>
    <head> 
        <title>Transactions</title>
        <!-- Boostrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
                    <a class="nav-link mb-1" href="manage_users.php">Manage Users</a>
                    <a class="nav-link mb-1 active" href="transactions.php">Transactions</a>
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
                        <a class="nav-link text-white " href="manage_movies.php">Manage Movies</a>
                        <a class="nav-link text-white" href="manage_showtimes.php">Manage Showtimes</a>
                        <a class="nav-link text-white" href="manage_studios.php">Manage Studios</a>
                        <a class="nav-link text-white" href="manage_prices.php">Manage Prices</a>
                        <a class="nav-link text-white" href="manage_users.php">Manage Users</a>
                        <a class="nav-link text-white fw-bold" href="transactions.php">Transactions</a>
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
                            
                            <span class="navbar-brand ms-2 fw-bold">Transactions</span>
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
                    <!-- TRANSACTIONS -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">RECENT TRANSACTIONS</h5>
                            <form class="d-flex gap-2">
                                <input type="date"
                                    id="filterDate"
                                    class="form-control form-control-sm"
                                    value="<?= $_GET['date'] ?? '' ?>">

                                <input id="searchTitle"
                                    class="form-control form-control-sm"
                                    placeholder="Search User..."
                                    style="width: 200px;">
                            </form>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Date</th>
                                            <th>Time</th> <!--order time-->
                                            <th>User</th> <!--username-->
                                            <th>Movie</th> 
                                            <th>Showtime</th> <!--time showtime-->
                                            <th>Studio</th> 
                                            <th>Tickets Qty</th> 
                                            <th>Total Price</th> 
                                            <th>Status</th> <!--paid/unpaid (default paid)-->
                                        </tr>
                                    </thead>
                                    <tbody id="transactionTableBody">
                                    <?php $no = $offset+1; while($row = $transactions->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                                        <td><?= date('H:i', strtotime($row['created_at'])) ?></td>
                                        <td><?= htmlspecialchars($row['username']) ?></td>
                                        <td><?= htmlspecialchars($row['movie_title']) ?></td>
                                        <td><?= substr($row['start_time'],0,5) ?></td>
                                        <td><?= htmlspecialchars($row['studio_name']) ?></td>
                                        <td><?= $row['tickets_qty'] ?></td>
                                        <td><?= number_format($row['total_price']) ?></td>
                                        <td><span class="badge <?= $row['status']=='paid' ? 'bg-success' : ($row['status']=='cancelled' ? 'bg-danger':'bg-warning') ?>"><?= strtoupper($row['status']) ?></span></td>
                                    </tr>
                                    <?php endwhile; ?>
                                    </tbody>




                                </table>
                                    <!-- Pagination -->
                                    <nav class="mt-2 ms-3">
                                        <ul class="pagination justify-content-left">
                                            <?php if($page>1): ?>
                                            <li class="page-item"><a class="page-link" href="#" onclick="fetchTransactions(<?= $page-1 ?>)">Prev</a></li>
                                            <?php endif; ?>
                                            <?php for($p=1;$p<=$totalPages;$p++): ?>
                                            <li class="page-item <?= $p==$page ? 'active' : '' ?>"><a class="page-link" href="#" onclick="fetchTransactions(<?= $p ?>)"><?= $p ?></a></li>
                                            <?php endfor; ?>
                                            <?php if($page<$totalPages): ?>
                                            <li class="page-item"><a class="page-link" href="#" onclick="fetchTransactions(<?= $page+1 ?>)">Next</a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>

                            </div>
                        </div>

                    </div>
                </div>
            </div>





        </div>

        <script>
            const searchInput = document.getElementById("searchTitle");
            const dateInput = document.getElementById("filterDate");
            const tbody = document.getElementById("transactionTableBody");

            let timer = null;

            function fetchTransactions(page=1){
                const q = searchInput.value;
                const date = dateInput.value;

                const params = new URLSearchParams();
                if(q) params.append("q",q);
                if(date) params.append("date",date);
                params.append("page", page);

                fetch("transactions.php?" + params.toString(), {headers:{"X-Requested-With":"XMLHttpRequest"}})
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html,"text/html");
                    tbody.innerHTML = doc.getElementById("transactionTableBody").innerHTML;

                    // Update pagination
                    const pag = document.querySelector(".pagination");
                    pag.innerHTML = doc.querySelector(".pagination").innerHTML;
                });
            }

            searchInput.addEventListener("keyup",()=>{
                clearTimeout(timer);
                timer=setTimeout(()=>fetchTransactions(1),300);
            });
            dateInput.addEventListener("change",()=>fetchTransactions(1));
        </script>


    </body>
</html>