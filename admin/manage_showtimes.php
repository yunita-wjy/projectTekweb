<?php
    session_start();
    require "../config/connection.php";
    // require "../includes/admin_auth.php";
    
    // if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    //     header("Location: ../auth/login.php");
    //     exit();
    // }

    // // helpers: flash messages
    // function flash($type, $msg) {
    //     $_SESSION['flash'] = ['type'=>$type, 'msg'=>$msg];
    // }
    // function get_flash() {
    //     if(isset($_SESSION['flash'])) { $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f; }
    //     return null;
    // }

    /*==== TABLE LIST SHOWTIMES ====*/
    $showtimes = $conn->query("
        SELECT s.showtime_id,
            m.title,
            st.studio_name,
            s.show_date,
            s.start_time,
            ADDTIME(s.start_time, SEC_TO_TIME(m.duration*60)) AS end_time,
            CASE 
                WHEN DAYOFWEEK(s.show_date) IN (1,7) 
                THEN p.weekend_price 
                ELSE p.weekday_price 
            END AS price
        FROM showtimes s
        JOIN movies m ON s.movie_id = m.movie_id
        JOIN studios st ON s.studio_id = st.studio_id
        JOIN prices p
        ORDER BY s.show_date DESC
    ");

    // DELETE SHOWTIME
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $id = (int) $_POST['delete_id'];

        $stmt = $conn->prepare("DELETE FROM showtimes WHERE showtime_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: manage_showtimes.php");
        exit;
    }


    // EDIT SHOWTIME
    $editMode = false;
    $editData = null;

    if (isset($_GET['edit_id'])) {
        $editMode = true;
        $id = (int) $_GET['edit_id'];

        $stmt = $conn->prepare("
            SELECT s.*, m.duration 
            FROM showtimes s
            JOIN movies m ON s.movie_id = m.movie_id
            WHERE s.showtime_id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $editData = $stmt->get_result()->fetch_assoc();
    }


    /*==== FORM ADD SHOWTIMES ====*/
    // AMBIL DATA MOVIES (DROPDOWN): Movies aktif & coming soon
    $movies = $conn->query("
        SELECT movie_id, title, duration, start_date, status 
        FROM movies 
        WHERE status IN ('ACTIVE','COMING_SOON')
    ")->fetch_all(MYSQLI_ASSOC);

    // AMBIL DATA STUDIOS
    $studios = $conn->query("
        SELECT studio_id, studio_name 
        FROM studios
        WHERE status = 'active';
    ")->fetch_all(MYSQLI_ASSOC);

    // AMBIL DATA PRICES: price untuk weekday dan weekend
    $studios = $conn->query("
        SELECT studio_id, studio_name 
        FROM studios
    ")->fetch_all(MYSQLI_ASSOC);

    // ADD SHOWTIME
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $movie_id   = $_POST['movie_id'];
        $studio_id  = $_POST['studio_id'];
        $show_date  = $_POST['showing_date'];
        $start_time = $_POST['start_time'];

        // ambil durasi
        $movie = $conn->query("SELECT duration FROM movies WHERE movie_id = $movie_id")->fetch_assoc();
        $duration = $movie['duration'];

        // hitung end_time
        $startDT = new DateTime("$show_date $start_time");
        $endDT = clone $startDT;
        $endDT->modify("+$duration minutes");
        $end_time = $endDT->format("H:i:s");

        // MODE UPDATE
        if (isset($_POST['update'])) {
            $id = $_POST['showtime_id'];

            $stmt = $conn->prepare("
                UPDATE showtimes 
                SET movie_id=?, studio_id=?, show_date=?, start_time=?, end_time=?
                WHERE showtime_id=?
            ");
            $stmt->bind_param("iisssi",
                $movie_id, $studio_id, $show_date, $start_time, $end_time, $id
            );
            $stmt->execute();

            header("Location: manage_showtimes.php");
            exit;
        }

        // MODE ADD
        if (isset($_POST['add'])) {
            $stmt = $conn->prepare("
                INSERT INTO showtimes (movie_id, studio_id, show_date, start_time, end_time)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("iisss",
                $movie_id, $studio_id, $show_date, $start_time, $end_time
            );
            $stmt->execute();

            header("Location: manage_showtimes.php");
            exit;
        }
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

            <!-- SIDEBAR (dekstop) -->
            <div class="sidebar p-3 d-none d-md-block">
                <div class="d-flex align-items-center mb-4">
                    <img src="../assets/filmVerse-dark.png" width="44" class="me-2">
                    <div><strong>FilmVerse</strong>
                    <div style="font-size:12px; color:#9CA3AF">Admin Panel</div></div>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link mb-1" href="dashboard.php">Dashboard</a>
                    <a class="nav-link mb-1" href="manage_movies.php">Manage Movies</a>
                    <a class="nav-link mb-1 active" href="manage_showtimes.php">Manage Showtimes</a>
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
                        <a class="nav-link text-white" href="dashboard.php">Dashboard</a>
                        <a class="nav-link text-white" href="manage_movies.php">Manage Movies</a>
                        <a class="nav-link text-white fw-bold" href="manage_showtimes.php">Manage Showtimes</a>
                        <a class="nav-link text-white" href="manage_studios.php">Manage Studios</a>
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
                            
                            <span class="navbar-brand ms-2 fw-bold">Admin - Manage Showtimes</span>
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

                    <!-- LIST OF SHOWTIMES -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">LIST OF SHOWTIMES</h5>
                            <form class="d-flex">
                                <input id="searchTitle" class="form-control form-control-sm me-2" placeholder="Search Showtimes Movie..." style="width: 200px;">
                                <button id="btnSearch" class="btn btn-outline-primary btn-sm" type="button" >Search</button>
                            </form>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Movie</th>
                                            <th>Studio</th>
                                            <th>Show Date</th> 
                                            <th>Start</th> <!--timetamp--> 
                                            <th>End</th> <!--timetamp-->
                                            <th>Price</th> <!--weekday = 40.000, weekend = 45.000-->
                                            <th>Action</th> <!--edit/delete-->
                                        </tr>
                                    </thead>
                                        <tbody>
                                        <?php $no=1; while($row = $showtimes->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row['title']) ?></td>
                                            <td><?= htmlspecialchars($row['studio_name']) ?></td>
                                            <td><?= $row['show_date'] ?></td>
                                            <td><?= substr($row['start_time'],0,5) ?></td>
                                            <td><?= substr($row['end_time'],0,5) ?></td>
                                            <td><?= number_format($row['price']) ?></td>
                                            <td>
                                                <a href="manage_showtimes.php?id=<?= $row['showtime_id'] ?>" 
                                                   class="btn btn-warning btn-sm">
                                                    Edit
                                                </a>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="delete_id" value="<?= $row['showtime_id'] ?>">
                                                    <button type="submit"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Hapus studio ini?')">
                                                            Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                        </tbody>
                                </table>

                            </div>
                        </div>



                    </div>




                    <!-- FORM ADD SHOWTIMES -->
                    <div class="card mt-4 mb-4 shadow-sm" style="width: 100%; ">
                        <!-- card header -->
                        <div class="card-header bg-primary text-white fw-bold">
                            Add Showtimes
                        </div>
                        <!-- card body -->
                        <div class="card-body px-4">
                            <form method="POST" id="addShowtimeForm">

                                <?php if ($editMode): ?>
                                    <input type="hidden" name="showtime_id" value="<?= $editData['showtime_id'] ?>">
                                <?php endif; ?>

                                <div class="row align-items-center mb-3 mt-1">
                                    <label for="movie" class="col-sm-3 col-form-label">MOVIE</label>
                                    <div class="col-sm-9">
                                        <select id="movieSelect" name="movie_id" class="form-select">
                                            <option value="" disabled selected>Pilih Movie</option>
                                            <?php foreach($movies as $m): ?>
                                            <option value="<?= $m['movie_id'] ?>"
                                                <?= ($editMode && $editData['movie_id'] == $m['movie_id']) ? 'selected' : '' ?>
                                                data-duration="<?= $m['duration'] ?>"
                                                data-start="<?= $m['start_date'] ?>">
                                                <?= htmlspecialchars($m['title']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3 mt-1">
                                    <label for="showing-date" class="col-sm-3 col-form-label">Date</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" name="showing_date" value="<?= $editMode ? $editData['show_date'] : '' ?>">
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3 mt-3">
                                    <label for="showtime-time" class="col-sm-3 col-form-label">Showtime</label>
                                    <div class="col-sm-9">
                                        <input type="time" class="form-control" name="start_time" value="<?= $editMode ? substr($editData['start_time'],0,5) : '' ?>">
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3 mt-3">
                                    <label for="end-time" class="col-sm-3 col-form-label">End Time</label>
                                    <div class="col-sm-9">
                                        <input id="end-time" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3 mt-3">
                                    <label for="genre" class="col-sm-3 col-form-label">Studio</label>
                                    <div class="col-sm-9">
                                        <select class="form-select genre-select" name="studio_id">
                                            <option disabled selected>Pilih Studio</option>
                                            <?php foreach($studios as $s): ?>
                                            <option value="<?= $s['studio_id'] ?>"
                                                <?= ($editMode && $editData['studio_id'] == $s['studio_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($s['studio_name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3 mt-3">
                                    <label for="duration" class="col-sm-3 col-form-label">Duration</label>
                                    <div class="col-sm-9">
                                        <input id="duration" class="form-control" value="<?= $editMode ? $editData['duration'].' minutes' : '' ?>" readonly>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3 mt-3">
                                    <label for="price" class="col-sm-3 col-form-label">Price</label>
                                    <div class="col-sm-9">
                                        <input id="price" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                                <!-- card footer -->
                                <div class="text-end">
                                <?php if ($editMode): ?>
                                    <button name="update" class="btn btn-warning fw-bold">Save Changes</button>
                                    <a href="manage_showtimes.php" class="btn btn-secondary">Cancel</a>
                                <?php else: ?>
                                    <button name="add" class="btn btn-success fw-bold">Save</button>
                                <?php endif; ?>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>







             </div>




        </div>

        <script>

            function updateEndTime() {
                const startTimeInput = document.getElementById("showtime-time").value;
                const durationText = document.getElementById("duration").value; // "120 minutes"
                if (!startTimeInput || !durationText) return;

                const duration = parseInt(durationText); // ambil angka
                const [h, m] = startTimeInput.split(":").map(Number);

                const date = new Date();
                date.setHours(h);
                date.setMinutes(m);
                date.setSeconds(0);
                date.setMilliseconds(0);

                date.setMinutes(date.getMinutes() + duration);

                const endTime = date.getHours().toString().padStart(2,'0') + ':' +
                                date.getMinutes().toString().padStart(2,'0');

                document.getElementById("end-time").value = endTime;
            }

            // panggil saat user pilih waktu showtime
            document.getElementById("showtime-time").addEventListener("change", updateEndTime);

            // panggil juga saat pilih movie supaya kalau jam sudah diisi, end time langsung muncul
            document.getElementById("movieSelect").addEventListener("change", updateEndTime);

            // Set duration saat pilih movie
            document.getElementById("movieSelect").addEventListener("change", function() {
                let durasi = this.options[this.selectedIndex].dataset.duration;
                document.getElementById("duration").value = durasi + " minutes";
            });

            // Validasi tanggal >= start date movie
            document.getElementById("movieSelect").addEventListener("change", function(){
                const startDate = this.options[this.selectedIndex].dataset.start;
                document.getElementById("showing-date").setAttribute("min", startDate);
            });

            // Set price saat pilih tanggal
            document.getElementById("showing-date").addEventListener("change", function() {
                const day = new Date(this.value).getDay(); // 0 Minggu
                let price = (day === 5 || day === 6 || day === 0) ? 45000 : 40000;
                document.getElementById("price").value = price.toLocaleString();
            });

            // Hitung End Time otomatis berdasarkan start time + duration
            document.getElementById("showtime-time").addEventListener("change", function() {
                const startTime = this.value; // format HH:MM
                const durationText = document.getElementById("duration").value; // "120 minutes"
                if (!durationText) return;

                const duration = parseInt(durationText); // ambil angka
                const [h, m] = startTime.split(":").map(Number);

                const date = new Date();
                date.setHours(h);
                date.setMinutes(m);
                date.setSeconds(0);
                date.setMilliseconds(0);

                // tambahkan durasi (menit)
                date.setMinutes(date.getMinutes() + duration);

                // format kembali ke HH:MM
                const endTime = date.getHours().toString().padStart(2,'0') + ':' + date.getMinutes().toString().padStart(2,'0');

                document.getElementById("end-time").value = endTime;
            });


            document.getElementById("showtime-time").addEventListener("change", function() {
                const startTime = this.value;
                const durationText = document.getElementById("duration").value;
                if (!durationText) return;

                const duration = parseInt(durationText); // ambil angka dari "120 minutes"
                const [h, m] = startTime.split(":").map(Number);

                const date = new Date();
                date.setHours(h);
                date.setMinutes(m);
                date.setSeconds(0);

                date.setMinutes(date.getMinutes() + duration);

                const endTime = date.getHours().toString().padStart(2,'0') + ':' + date.getMinutes().toString().padStart(2,'0');
                document.getElementById("end-time").value = endTime;
            });


            // Function untuk price 
            document.getElementById("showing-date").addEventListener("change", function() {
                const day = new Date(this.value).getDay(); 
                // 0 Minggu, 1 Senin, 2 Selasa, dst
                
                // weekend (Jumat, Sabtu, Minggu) = 45.000
                // weekday (Senin, Selasa, Rabu, Kamis) = 4
                let price = (day === 5 || day === 6 || day === 0) ? 45000 : 40000;

                document.getElementById("price").value = price.toLocaleString();
            });


        </script>

   


    </body>
</html>