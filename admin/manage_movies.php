<?php

require "../config/connection.php"; 
require "../includes/admin_auth.php";
// Simple auth guard (aktifkan sesuai project mu)
// if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
//     header("Location: ../auth/login.php");
//     exit();
//}

// // SIMULASI LOGIN ADMIN (SEMENTARA TANPA LOGIN PAGE)
// if (!isset($_SESSION['user'])) {
//     $q = $conn->query("SELECT user_id, username, full_name, email, role 
//                        FROM users 
//                        WHERE role = 'admin' 
//                        LIMIT 1");
//     $admin = $q->fetch_assoc();

//     if ($admin) {
//         $_SESSION['user'] = $admin;
//     }
// }

// helpers: flash messages
function flash($type, $msg) {
    $_SESSION['flash'] = ['type'=>$type, 'msg'=>$msg];
}
function get_flash() {
    if(isset($_SESSION['flash'])) { $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f; }
    return null;
}

// --- HANDLE POST ACTIONS: add_genre, edit_genre, delete_genre, add_movie, delete_movie ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ADD GENRE (inline)
    if (isset($_POST['action']) && $_POST['action'] === 'add_genre') {
        $g = trim($_POST['genre_name'] ?? '');
        if ($g === '') {
            flash('danger', 'Genre kosong.');
        } else {
            $stmt = $conn->prepare("INSERT INTO genres (genre_name) VALUES (?)");
            $stmt->bind_param("s", $g);
            if ($stmt->execute()) flash('success', "Genre '$g' berhasil ditambahkan.");
            else flash('danger', 'Gagal menambah genre (mungkin sudah ada).');
            $stmt->close();
        }
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    // UPDATE GENRE
    if(isset($_POST['action']) && $_POST['action'] === 'update_genre') {
        $id = intval($_POST['genre_id'] ?? 0);
        $name = trim($_POST['genre_name'] ?? '');
        if($id > 0 && $name !== '') {
            $stmt = $conn->prepare("UPDATE genres SET genre_name = ? WHERE genre_id = ?");
            $stmt->bind_param("si", $name, $id);
            $stmt->execute();
            $stmt->close();
            flash('success', "Genre '$name' berhasil diupdate.");
        }
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    // DELETE GENRE
    if(isset($_POST['action']) && $_POST['action'] === 'delete_genre') {
        $id = intval($_POST['genre_id'] ?? 0);
        if($id > 0) {
            $stmt = $conn->prepare("DELETE FROM genres WHERE genre_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            flash('success', "Genre berhasil dihapus.");
        }
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }


    // ADD MOVIE
    if (isset($_POST['action']) && $_POST['action'] === 'add_movie') {
        // collect & basic validate
        $title = trim($_POST['title'] ?? '');
        $duration = intval($_POST['duration'] ?? 0);
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        $synopsis = trim($_POST['synopsis'] ?? null);
        $trailer = trim($_POST['trailer_link'] ?? null);
        $genre_ids = $_POST['genres'] ?? []; // array of genre_id (multiple select)
        if ($title === '' || $duration <= 0 || $start_date === '' || $end_date === '') {
            flash('danger', 'Mohon isi semua field wajib (title, duration, start date, end date).');
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
        if (count($genre_ids) > 5) {
            flash('danger', 'Maksimal 5 genre.');
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }

        // poster upload handling
        $poster_path_db = null;
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {
            $f = $_FILES['poster'];
            if ($f['error'] !== UPLOAD_ERR_OK) {
                flash('danger', 'Error upload poster.');
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
            $allowed = ['image/jpeg','image/png','image/jpg','image/webp'];
            if (!in_array($f['type'], $allowed)) {
                flash('danger', 'Poster harus berformat JPG/PNG/WEBP.');
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
            // create unique filename
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $newName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
            $targetDir = __DIR__ . '/../assets/movie_poster/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
            $target = $targetDir . $newName;
            if (!move_uploaded_file($f['tmp_name'], $target)) {
                flash('danger', 'Gagal menyimpan poster.');
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
            // store relative path (or just filename)
            $poster_path_db = 'assets/movie_poster/' . $newName;
        }

        // determine status automatically:
        $today = date('Y-m-d');
        if ($today < $start_date) $status = 'coming_soon';
        else if ($today >= $start_date && $today <= $end_date) $status = 'active';
        else $status = 'inactive';

        // Insert into movies + pivot movie_genre
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO movies (title, duration, start_date, end_date, synopsis, poster_path, trailer_url, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissssss", $title, $duration, $start_date, $end_date, $synopsis, $poster_path_db, $trailer, $status);
            if (!$stmt->execute()) throw new Exception('Gagal insert movie: ' . $stmt->error);
            $movie_id = $stmt->insert_id;
            $stmt->close();

            // insert into movie_genre pivot
            if (!empty($genre_ids)) {
                $stmt2 = $conn->prepare("INSERT INTO movie_genre (movie_id, genre_id) VALUES (?, ?)");
                foreach ($genre_ids as $gid) {
                    $gid = intval($gid);
                    $stmt2->bind_param("ii", $movie_id, $gid);
                    if (!$stmt2->execute()) throw new Exception('Gagal insert pivot: ' . $stmt2->error);
                }
                $stmt2->close();
            }

            $conn->commit();
            flash('success', "Movie '$title' berhasil ditambahkan.");
        } catch (Exception $e) {
            $conn->rollback();
            // cleanup poster if uploaded
            if ($poster_path_db) {
                @unlink(__DIR__ . '/../' . $poster_path_db);
            }
            flash('danger', 'Gagal menambah movie: ' . $e->getMessage());
        }

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    // UPDATE MOVIE (lengkap)
    if (isset($_POST['action']) && $_POST['action'] === 'update_movie') {
        $movie_id = intval($_POST['movie_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $duration = intval($_POST['duration'] ?? 0);
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        $synopsis = trim($_POST['synopsis'] ?? null);
        $trailer = trim($_POST['trailer_link'] ?? null);
        $genre_ids = $_POST['genres'] ?? [];

        if ($movie_id <= 0 || $title === '' || $duration <= 0 || $start_date === '' || $end_date === '') {
            flash('danger', 'Mohon isi semua field wajib.');
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }

        if (count($genre_ids) > 5) {
            flash('danger', 'Maksimal 5 genre.');
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }

        // Tentukan status otomatis
        $today = date('Y-m-d');
        if ($today < $start_date) $status = 'coming_soon';
        else if ($today >= $start_date && $today <= $end_date) $status = 'active';
        else $status = 'inactive';

        $conn->begin_transaction();
        try {
            // Ambil poster lama
            $q = $conn->prepare("SELECT poster_path FROM movies WHERE movie_id=?");
            $q->bind_param("i", $movie_id);
            $q->execute();
            $res = $q->get_result()->fetch_assoc();
            $oldPoster = $res['poster_path'] ?? null;
            $q->close();

            // Handle poster baru
            $poster_path_db = $oldPoster;
            if (isset($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {
                $f = $_FILES['poster'];
                if ($f['error'] !== UPLOAD_ERR_OK) throw new Exception('Error upload poster.');
                $allowed = ['image/jpeg','image/png','image/jpg','image/webp'];
                if (!in_array($f['type'], $allowed)) throw new Exception('Poster harus JPG/PNG/WEBP.');
                
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $newName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
                $targetDir = __DIR__ . '/../assets/movie_poster/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                $target = $targetDir . $newName;
                if (!move_uploaded_file($f['tmp_name'], $target)) throw new Exception('Gagal menyimpan poster.');
                $poster_path_db = 'assets/movie_poster/' . $newName;

                // hapus poster lama
                if ($oldPoster) @unlink(__DIR__ . '/../' . $oldPoster);
            }

            // Update movie
            $stmt = $conn->prepare("UPDATE movies SET title=?, duration=?, start_date=?, end_date=?, synopsis=?, poster_path=?, trailer_url=?, status=? WHERE movie_id=?");
            $stmt->bind_param("sissssssi", $title, $duration, $start_date, $end_date, $synopsis, $poster_path_db, $trailer, $status, $movie_id);
            $stmt->execute();
            $stmt->close();

            // Update pivot table genre
            $d = $conn->prepare("DELETE FROM movie_genre WHERE movie_id=?");
            $d->bind_param("i", $movie_id);
            $d->execute();
            $d->close();

            if (!empty($genre_ids)) {
                $stmt2 = $conn->prepare("INSERT INTO movie_genre (movie_id, genre_id) VALUES (?, ?)");
                foreach ($genre_ids as $gid) {
                    $gid = intval($gid);
                    $stmt2->bind_param("ii", $movie_id, $gid);
                    $stmt2->execute();
                }
                $stmt2->close();
            }

            $conn->commit();
            flash('success', "Movie '$title' berhasil diupdate.");
        } catch (Exception $e) {
            $conn->rollback();
            flash('danger', 'Gagal update movie: ' . $e->getMessage());
        }

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }


    // DELETE MOVIE
    if (isset($_POST['action']) && $_POST['action'] === 'delete_movie') {
        $mid = intval($_POST['movie_id'] ?? 0);
        if ($mid <= 0) {
            flash('danger','Movie ID tidak valid.');
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
        // delete movie + pivot and poster
        $conn->begin_transaction();
        try {
            // get poster path
            $q = $conn->prepare("SELECT poster_path FROM movies WHERE movie_id = ?");
            $q->bind_param("i", $mid);
            $q->execute();
            $res = $q->get_result()->fetch_assoc();
            $poster = $res['poster_path'] ?? null;
            $q->close();

            // delete pivot
            $d1 = $conn->prepare("DELETE FROM movie_genre WHERE movie_id = ?");
            $d1->bind_param("i", $mid);
            $d1->execute(); $d1->close();

            // delete tickets or restrict? (not implemented here) - beware FK constraints
            // delete movie
            $d2 = $conn->prepare("DELETE FROM movies WHERE movie_id = ?");
            $d2->bind_param("i", $mid);
            $d2->execute();
            $d2->close();

            // commit
            $conn->commit();
            // unlink poster file
            if ($poster) @unlink(__DIR__ . '/../' . $poster);
            flash('success','Movie berhasil dihapus.');
        } catch (Exception $e) {
            $conn->rollback();
            flash('danger','Gagal menghapus movie: ' . $e->getMessage());
        }
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// --- FETCH DATA for display ---
$flash = get_flash();

// genres list
$genres = [];
$res = $conn->query("SELECT genre_id, genre_name FROM genres ORDER BY genre_name ASC");
while ($r = $res->fetch_assoc()) $genres[] = $r;
$res->free();

// movies with aggregated genres
$movies = [];
$sql = "SELECT m.movie_id, m.title, m.duration, m.start_date, m.end_date, m.synopsis, m.poster_path, m.trailer_url, m.status,
        GROUP_CONCAT(g.genre_name SEPARATOR ', ') AS genres_name,
        GROUP_CONCAT(mg.genre_id) AS genres_id
        FROM movies m
        LEFT JOIN movie_genre mg ON m.movie_id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.genre_id
        GROUP BY m.movie_id
        ORDER BY m.movie_id DESC";

$res2 = $conn->query($sql);
while ($r = $res2->fetch_assoc()) {
    $r['genres'] = explode(', ', $r['genres_name'] ?? '');
    $r['genre_ids'] = array_map('intval', explode(',', $r['genres_id'] ?? ''));
    $movies[] = $r;
}
$res2->free();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Manage Movies - Admin</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <a class="nav-link mb-1 active" href="manage_movies.php">Manage Movies</a>
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
                <a class="nav-link text-white" href="dashboard.php">Dashboard</a>
                <a class="nav-link text-white fw-bold" href="manage_movies.php">Manage Movies</a>
                <a class="nav-link text-white" href="manage_showtimes.php">Manage Showtimes</a>
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
                    
                    <span class="navbar-brand ms-2 fw-bold">Admin - Manage Movies</span>
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

            <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['msg']) ?></div>
            <?php endif; ?>

            <!-- LIST OF MOVIES -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">List of Movies</h5>
                    <form class="d-flex">
                        <input id="searchTitle" class="form-control form-control-sm me-2" placeholder="Search Title...">
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Poster</th>
                                    <th>Title</th>
                                    <th>Genre</th>
                                    <th>Duration (min)</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="movieTableBody">
                                <?php if(empty($movies)): ?>
                                    <tr><td colspan="9" class="text-center py-4">No movies yet.</td></tr>
                                <?php else: foreach($movies as $i => $m): ?>
                                    <tr>
                                        <td><?= $i+1 ?></td>
                                        <td>
                                            <?php if($m['poster_path']): ?>
                                                <img src="../<?= htmlspecialchars($m['poster_path']) ?>" class="poster-thumb">
                                            <?php else: ?>
                                                <div class="border rounded d-inline-block poster-thumb" style="background:#e9ecef;display:inline-block;"></div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($m['title']) ?></td>
                                        <td style="min-width:160px"><?= htmlspecialchars(is_array($m['genres']) ? implode(', ', $m['genres']) : ($m['genres'] ?? '-')) ?></td>
                                        <td><?= htmlspecialchars($m['duration']) ?></td>
                                        <td><?= htmlspecialchars($m['start_date']) ?></td>
                                        <td><?= htmlspecialchars($m['end_date']) ?></td>
                                        <td>
                                            <?php if($m['status'] === 'active'): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php elseif($m['status'] === 'coming_soon'): ?>
                                                <span class="badge bg-info text-dark">Coming Soon</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-2">
                                                <!-- Edit form -->
                                                <button type="button" 
                                                    class="btn btn-sm btn-warning btn-edit-movie"
                                                    data-movie='<?= htmlspecialchars(json_encode([
                                                        "id" => $m["movie_id"],
                                                        "title" => $m["title"],
                                                        "duration" => $m["duration"],
                                                        "start_date" => $m["start_date"],
                                                        "end_date" => $m["end_date"],
                                                        "synopsis" => $m["synopsis"] ?? "",
                                                        "trailer" => $m["trailer_url"] ?? "",
                                                        "genre_ids" => $m["genre_ids"]
                                                    ]), ENT_QUOTES) ?>'>
                                                    Edit
                                                </button>                                           

                                                <!-- Delete form -->
                                                <form method="POST" class="d-inline" onsubmit="return confirm('Hapus movie ini?');">
                                                    <input type="hidden" name="action" value="delete_movie">
                                                    <input type="hidden" name="movie_id" value="<?= $m['movie_id'] ?>">
                                                    <button class="btn btn-sm btn-danger w-100" type="submit">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ADD MOVIE & MANAGE GENRES -->
            <div class="row g-3">
                <div class="col-lg-7">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white fw-bold">
                            Add Movie
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data" id="AddMovieForm">
                                <input type="hidden" name="movie_id" value="">
                                <input type="hidden" name="action" value="add_movie">
                                <div class="mb-3 row align-items-center">
                                    <label class="col-sm-3 col-form-label">Movie Title <span class="required">*</span></label>
                                    <div class="col-sm-9"><input name="title" class="form-control" required></div>
                                </div>

                                <div class="mb-3 row align-items-center">
                                    <label class="col-sm-3 col-form-label">Genre<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <div id="genre-container">
                                            <!-- ROW PERTAMA -->
                                            <div class="genre-row d-flex gap-2">
                                                <select class="form-select genre-select" name="genres[]" required>
                                                    <option value="" disabled selected>Pilih genre</option>
                                                </select>

                                                <!-- tombol + hanya ada di baris terakhir -->
                                                <button type="button" class="btn btn-primary btn-add fw-bold" style="font-size: 16px;">+</button>
                                            </div>
                                        </div>
                                        <div class="form-text">Pilih sampai <strong>max 5</strong>.</div>
                                    </div>
                                </div>

                                <div class="mb-3 row align-items-center">
                                    <label class="col-sm-3 col-form-label">Duration (minutes) <span class="required">*</span></label>
                                    <div class="col-sm-9"><input name="duration" type="number" min="1" class="form-control" required></div>
                                </div>

                                <div class="mb-3 row align-items-center">
                                    <label class="col-sm-3 col-form-label">Start Showing Date <span class="required">*</span></label>
                                    <div class="col-sm-9"><input name="start_date" type="date" class="form-control" required></div>
                                </div>

                                <div class="mb-3 row align-items-center">
                                    <label class="col-sm-3 col-form-label">End Showing Date <span class="required">*</span></label>
                                    <div class="col-sm-9"><input name="end_date" type="date" class="form-control" required></div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">Synopsis</label>
                                    <div class="col-sm-9"><textarea name="synopsis" class="form-control" rows="4"></textarea></div>
                                </div>

                                <div class="mb-3 row align-items-center">
                                    <label class="col-sm-3 col-form-label">Poster (jpg/png)</label>
                                    <div class="col-sm-9">
                                        <input name="poster" type="file" accept="image/*" class="form-control">
                                        <div class="form-text">Optional. Max ~2MB recommended.</div>
                                    </div>
                                </div>

                                <div class="mb-3 row align-items-center">
                                    <label class="col-sm-3 col-form-label">Trailer Youtube Link</label>
                                    <div class="col-sm-9"><input name="trailer_link" class="form-control" placeholder="https://www.youtube.com/watch?v=..."></div>
                                </div>

                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary me-2" id="cancelEditMovie" style="display:none;">Cancel</button>
                                    <button type="submit" class="btn btn-success" id="submitMovieBtn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Manage genres side -->
                <div class="col-lg-5">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white fw-bold">
                            Manage Genres
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Genre</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($genres as $i => $g): ?>
                                        <tr data-genre-id="<?= $g['genre_id'] ?>" data-genre-name="<?= htmlspecialchars($g['genre_name'], ENT_QUOTES) ?>"> 
                                            <td><?= $i+1 ?></td>
                                            <td><?= htmlspecialchars($g['genre_name']) ?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm btn-edit-genre">Edit</button>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('Delete this genre?');">
                                                    <input type="hidden" name="action" value="delete_genre">
                                                    <input type="hidden" name="genre_id" value="<?= $g['genre_id'] ?>">
                                                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if(empty($genres)): ?>
                                        <tr>
                                            <td colspan="2">No genres yet.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addGenreModal">Add New Genre</button>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container-fluid -->
    </div>
</div>

<!-- Modal Add Genre -->
<div class="modal fade" id="addGenreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="action" value="add_genre">
            <div class="modal-header">
                <h5 class="modal-title">Add Genre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Genre name</label>
                <input name="genre_name" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Genre -->
<div class="modal fade" id="editGenreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="action" value="update_genre">
            <input type="hidden" name="genre_id" id="edit-genre-id">
            <div class="modal-header">
                <h5 class="modal-title">Edit Genre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Genre name</label>
                <input name="genre_name" id="edit-genre-name" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                <button class="btn btn-warning" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Search Movie 
    const searchInput = document.getElementById("searchTitle");
    const tbody = document.getElementById("movieTableBody");

    let timer = null;

    searchInput.addEventListener("keyup", function () {
        clearTimeout(timer);

        timer = setTimeout(() => {
            const q = this.value;

            fetch(`ajax/search_movies.php?q=${encodeURIComponent(q)}`)
                .then(res => res.text())
                .then(html => {
                    tbody.innerHTML = html || `
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Movie not found
                            </td>
                        </tr>`;
                });
        }, 300); // debounce
    });


    


    function createGenreSelect() {
        const select = document.createElement("select");
        select.className = "form-select genre-select";
        select.name = "genres[]";
        select.required = true;
        fillSelect(select);
        return select;
    }

    const form = document.getElementById("AddMovieForm");
    const submitBtn = document.getElementById("submitMovieBtn");
    const cancelBtn = document.getElementById("cancelEditMovie");

    // EDIT MOVIE (from list table)
    document.querySelectorAll(".btn-edit-movie").forEach(btn => {
        btn.addEventListener("click", () => {
            const movie = JSON.parse(btn.dataset.movie);

            // isi form text & number
            form.querySelector("[name=movie_id]").value = movie.id;
            form.querySelector("[name=title]").value = movie.title;
            form.querySelector("[name=duration]").value = movie.duration;
            form.querySelector("[name=start_date]").value = movie.start_date;
            form.querySelector("[name=end_date]").value = movie.end_date;
            form.querySelector("[name=synopsis]").value = movie.synopsis || "";
            form.querySelector("[name=trailer_link]").value = movie.trailer || "";

            // genres
            const container = form.querySelector("#genre-container");
            container.innerHTML = ""; // kosongkan dulu

            movie.genre_ids.forEach((gid, index) => {
                const row = document.createElement("div");
                row.className = "genre-row d-flex gap-2 mt-2";

                const select = createGenreSelect();
                select.value = gid; // prefill genre
                row.appendChild(select);

                // tombol +
                const addBtn = document.createElement("button");
                addBtn.type = "button";
                addBtn.className = "btn btn-primary btn-add fw-bold";
                addBtn.textContent = "+";
                row.appendChild(addBtn);

                // tombol -
                const removeBtn = document.createElement("button");
                removeBtn.type = "button";
                removeBtn.className = "btn btn-danger btn-remove fw-bold";
                removeBtn.textContent = "-";
                row.appendChild(removeBtn);

                container.appendChild(row);
            });

            refreshButtons();

            // ganti mode form
            submitBtn.textContent = "Update";
            submitBtn.className = "btn btn-warning";
            form.querySelector("[name=action]").value = "update_movie";
            cancelBtn.style.display = "inline-block";

            // scroll ke form
            form.scrollIntoView({behavior:"smooth"});
        });
    });

    // CANCEL EDIT MOVIE
    cancelBtn.addEventListener("click", () => {
        form.reset();
        form.querySelector("[name=movie_id]").value = "";
        form.querySelector("[name=action]").value = "add_movie";

        submitBtn.textContent = "Save";
        submitBtn.className = "btn btn-success";
        cancelBtn.style.display = "none";

        // reset genre dropdown
        const container = form.querySelector("#genre-container");
        container.innerHTML = `
            <div class="genre-row d-flex gap-2">
                <select class="form-select genre-select" name="genres[]" required>
                    <option value="" disabled selected>Pilih genre</option>
                </select>
                <button type="button" class="btn btn-primary btn-add fw-bold" style="font-size:16px;">+</button>
            </div>
        `;
        fillSelect(container.querySelector(".genre-select"));
        refreshButtons();
    });

    // CHOOSE GENRE (from add movie form)
    const GENRES = <?= json_encode($genres) ?>;
    const MAX_GENRE = 5;

    document.addEventListener("DOMContentLoaded", () => {
        const container = document.getElementById("genre-container");

        // isi dropdown yg SUDAH ADA di HTML
        fillSelect(container.querySelector(".genre-select"));
        refreshButtons();

        // EVENT CLICK (+ / -)
        container.addEventListener("click", e => {

            // ➕ TAMBAH DROPDOWN
            if (e.target.classList.contains("btn-add")) {
                const rows = container.querySelectorAll(".genre-row");
                if (rows.length >= MAX_GENRE) return;

                const row = document.createElement("div");
                row.className = "genre-row d-flex gap-2 mt-2";

                const select = document.createElement("select");
                select.className = "form-select genre-select";
                select.name = "genres[]";
                select.required = true;

                fillSelect(select);

                const btnAdd = document.createElement("button");
                btnAdd.type = "button";
                btnAdd.className = "btn btn-primary btn-add fw-bold";
                btnAdd.textContent = "+";

                const btnRemove = document.createElement("button");
                btnRemove.type = "button";
                btnRemove.className = "btn btn-danger btn-remove fw-bold";
                btnRemove.textContent = "-";

                row.appendChild(select);
                row.appendChild(btnAdd);
                row.appendChild(btnRemove);

                container.appendChild(row);
                refreshButtons();
            }

            // HAPUS DROPDOWN
            if (e.target.classList.contains("btn-remove")) {
                e.target.closest(".genre-row").remove();
                refreshButtons();
            }
        });
    });

    // isi option genre
    function fillSelect(select) {
        select.innerHTML = `<option value="" disabled selected>Pilih genre</option>`;
        GENRES.forEach(g => {
            const opt = document.createElement("option");
            opt.value = g.genre_id;
            opt.textContent = g.genre_name;
            select.appendChild(opt);
        });
    }

    // atur tombol + dan -
    function refreshButtons() {
        const rows = document.querySelectorAll(".genre-row");

        rows.forEach((row, index) => {
            let btnAdd = row.querySelector(".btn-add");
            let btnRemove = row.querySelector(".btn-remove");

            // buat tombol - kalau belum ada
            if (!btnRemove) {
                btnRemove = document.createElement("button");
                btnRemove.type = "button";
                btnRemove.className = "btn btn-danger btn-remove fw-bold";
                btnRemove.textContent = "-";
                row.appendChild(btnRemove);
            }

            // + hanya di baris terakhir & < max
            btnAdd.style.display =
                (index === rows.length - 1 && rows.length < MAX_GENRE)
                    ? "inline-block"
                    : "none";

            // - muncul kalau lebih dari 1
            btnRemove.style.display =
                rows.length > 1 ? "inline-block" : "none";
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        const editModal = new bootstrap.Modal(document.getElementById('editGenreModal'));

        document.querySelectorAll(".btn-edit-genre").forEach(btn => {
            btn.addEventListener("click", e => {
                const tr = e.target.closest("tr");
                const id = tr.dataset.genreId;
                const name = tr.dataset.genreName;

                document.getElementById("edit-genre-id").value = id;
                document.getElementById("edit-genre-name").value = name;

                editModal.show();
            });
        });
    });

</script>


</body>
</html>
