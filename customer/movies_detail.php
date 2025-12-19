<?php
session_start();
require("../config/connection.php");

$user = $_SESSION['user'] ?? null;

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit();
}



$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM movies WHERE movie_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();


$showtimesQuery = $conn->prepare("
    SELECT show_date, start_time 
    FROM showtimes 
    WHERE movie_id = ?
    ORDER BY show_date, start_time
");
$showtimesQuery->bind_param("i", $id);
$showtimesQuery->execute();
$showtimes = $showtimesQuery->get_result()->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $movie ? htmlspecialchars($movie['title']) : 'Movie Detail' ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../style.css">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
    </style>
</head>

<body>


<?php include("../includes/header.php"); ?>

<main class="container my-5">
<?php if (!$movie): ?>
    <p>Movie not found</p>
<?php else: ?>
    <div class="row">
        <div class="col-md-4">
            <img src="../<?= htmlspecialchars($movie['poster_path']) ?>"
                 class="img-fluid rounded shadow">
        </div>
        <div class="col-md-8">
            <h1><?= htmlspecialchars($movie['title']) ?></h1>
            <p><?= nl2br(htmlspecialchars($movie['synopsis'])) ?></p>

        <div class="card bg-light border-0 p-4 mt-4 shadow-sm">
            <h5 class="fw-bold mb-3">Book Tickets</h5>
            
            <form action="seats.php" method="GET">
                <input type="hidden" name="movie_id" value="<?= $movie['movie_id'] ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Select Date</label>
                        <select class="form-select" name="date">
                            <?php
                            $dates = [];
                            foreach ($showtimes as $st) {
                                $dates[$st['show_date']] = true; // uniq date
                            }
                            foreach (array_keys($dates) as $date): ?>
                                <option value="<?= $date ?>"><?= date('d M Y', strtotime($date)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Showtime</label><br>
                        <div class="btn-group w-100" role="group">
                            <?php foreach ($showtimes as $index => $st): ?>
                                <input type="radio" class="btn-check" name="time" id="t<?= $index ?>" value="<?= $st['start_time'] ?>" <?= $index === 0 ? 'checked' : '' ?>>
                                <label class="btn btn-outline-dark" for="t<?= $index ?>"><?= date('H:i', strtotime($st['start_time'])) ?></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold py-2 mt-2">
                    CONTINUE TO SEATS
                </button>
            </form>
        </div>
        </div>
    </div>
<?php endif; ?>
</main>


<?php include("../includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
