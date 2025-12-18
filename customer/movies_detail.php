<?php
session_start();
require_once "../config/connection.php";
require_once "../classes/movie.php";
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit();
}
$user = $_SESSION['user'];

$id = $_GET['id'];
$movieObj = new movie($conn);
$detail = $movieObj->getMovieById($id);

$showtimesQuery = $conn->prepare("
    SELECT show_date, start_time 
    FROM showtimes 
    WHERE movie_id = :movie_id
    ORDER BY show_date, start_time
");

$showtimesQuery->bindParam(':movie_id', $id, PDO::PARAM_INT);
$showtimesQuery->execute();

$showtimes = $showtimesQuery->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Select Seat</title>
    <!-- favicon -->
    <link href="../assets/filmVerse-light.png" rel="icon" media="(prefers-color-scheme: light)" />
    <link href="../assets/filmVerse-dark.png" rel="icon" media="(prefers-color-scheme: dark)" />
    <!-- icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- sweet alert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css?v=2">
    <link rel="stylesheet" href="../movies.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../script.js"></script>
</head>

<body>
    <?php include "../includes/header.php"; ?>
    <div class="mt-5 pt-4">

        <main class="container my-4 mt-5">

            <a href="movies.php" id="back-to-movies" class="btn btn-link mb-3 ps-0 text-dark text-decoration-none fw-bold">← Back to Movies</a>

            <div class="ratio ratio-21x9 bg-dark mb-4 rounded shadow">
                <div class="d-flex align-items-center justify-content-center h-100 text-white">
                    <div class="text-center">
                        <h1><i class="fa fa-play-circle display-1"></i></h1>
                        <p>Trailer Preview</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <img src="../<?= $detail['poster_path'] ?>" class="img-fluid rounded shadow w-100">
                </div>

                <div class="col-md-9">
                    <h1 class="fw-bold"><?= $detail['title'] ?></h1>
                    <p class="text-muted">Genre: Action, Adventure | Duration: <?= $detail['duration'] ?> min</p>

                    <h5 class="mt-4">Synopsis</h5>
                    <p class="text-secondary"><?= $detail['synopsis'] ?></p>
                    <hr>

                    <div class="card bg-light border-0 p-4 mt-4 shadow-sm">
                        <h5 class="fw-bold mb-3">Book Tickets</h5>
                        <form action="seats.php" method="GET">
                            <input type="hidden" name="movie_id" value="<?= $detail['movie_id'] ?>">

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
                                    <div class="btn-group w-100" role="group" id="showtime-group">
                                        <?php foreach ($showtimes as $index => $st): ?>
                                            <input type="radio" class="btn-check" name="time" id="t<?= $index ?>" value="<?= $st['start_time'] ?>" <?= $index === 0 ? 'checked' : '' ?>>
                                            <label class="btn btn-outline-dark" for="t<?= $index ?>"><?= date('H:i', strtotime($st['start_time'])) ?></label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary-custom w-100 fw-bold py-2 mt-2">
                                CONTINUE TO SEATS
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php include "../includes/footer.php"; ?> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        fetch('backend/showtimes.php?movie_id=<?= $detail['movie_id'] ?>')
            .then(res => res.json())
            .then(data => {
                const dateSelect = document.getElementById('date-select');
                const showtimeGroup = document.getElementById('showtime-group');

                // render dates dropdown
                data.dates.forEach(date => {
                    const option = document.createElement('option');
                    const dateObj = new Date(date);
                    option.value = date;
                    option.textContent = dateObj.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                    dateSelect.appendChild(option);
                });

                // render showtime radio buttons
                data.showtimes.forEach((time, idx) => {
                    const input = document.createElement('input');
                    input.type = 'radio';
                    input.className = 'btn-check';
                    input.name = 'time';
                    input.id = 't' + (idx + 1);
                    input.value = time;
                    if (idx === 0) input.checked = true;

                    const label = document.createElement('label');
                    label.className = 'btn btn-outline-dark';
                    label.htmlFor = input.id;
                    label.textContent = time;

                    showtimeGroup.appendChild(input);
                    showtimeGroup.appendChild(label);
                });
            });
    </script>

</body>

</html>