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

// link trailer Youtube 
$youtubeId = null;

if (!empty($movie['trailer_url'])) {
    parse_str(parse_url($movie['trailer_url'], PHP_URL_QUERY), $yt);
    $youtubeId = $yt['v'] ?? null;
}

// Ambil genre movie
$genreQuery = $conn->prepare("
    SELECT g.genre_name
    FROM movie_genre mg
    JOIN genres g ON mg.genre_id = g.genre_id
    WHERE mg.movie_id = ?
");
$genreQuery->bind_param("i", $id);
$genreQuery->execute();
$genres = $genreQuery->get_result()->fetch_all(MYSQLI_ASSOC);


// Ambil showtimes di db
$showtimesQuery = $conn->prepare("
    SELECT showtime_id, show_date, start_time
    FROM showtimes
    WHERE movie_id = ?
      AND show_date >= CURDATE()
    ORDER BY show_date, start_time
");

$showtimesQuery->bind_param("i", $id);
$showtimesQuery->execute();
$showtimes = $showtimesQuery->get_result()->fetch_all(MYSQLI_ASSOC);

$showtimesByDate = [];
foreach ($showtimes as $st) {
    $showtimesByDate[$st['show_date']][] = [
        'id' => $st['showtime_id'],
        'time' => $st['start_time']
    ];
}


$hasShowtimes = !empty($showtimes);

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

        /* style trailer */
        /* .trailer-wrapper {
            width: 100%;
            aspect-ratio: 16 / 9;
            max-height: 320px;   /* ini yang bikin nggak kegedean */
        /* } */

        /* .trailer-wrapper iframe {
            width: 100%;
            height: 320px;
            border: 0;
        } */

        .trailer-box {
            position: relative;
            width: 100%;
            height: 300px;          /* tinggi aman */
            cursor: pointer;
            overflow: hidden;
            border-radius: 12px;
        }

        .trailer-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .trailer-box iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
            display: none;
        }

        .play-btn {
            position: absolute;
            inset: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0,0,0,0.35);
        }

        .play-btn i {
            font-size: 64px;
            color: white;
            background: rgba(0,0,0,0.6);
            padding: 24px 28px;
            border-radius: 50%;
            transition: transform 0.2s ease;
        }

        .trailer-box:hover .play-btn i {
            transform: scale(1.1);
        }

        /* durasi dan genre */
        .movie-meta {
            font-size: 14px;
        }

        .movie-meta .badge {
            font-weight: 500;
            opacity: 0.9;
        }



      
    </style>
</head>

<body>


<?php include("../includes/header.php"); ?>

<main class="container my-5">




<?php if ($youtubeId): ?>
<div class="trailer-box mb-5" onclick="playTrailer(this)">
    <img 
        src="https://img.youtube.com/vi/<?= $youtubeId ?>/hqdefault.jpg"
        class="trailer-thumb"
        alt="Trailer">
    <div class="play-btn">
        <i class="fa-solid fa-play"></i>
    </div>
    <iframe
        data-src="https://www.youtube.com/embed/<?= $youtubeId ?>?autoplay=1"
        src=""
        allow="autoplay"
        allowfullscreen>
    </iframe>
</div>
<?php endif; ?>



<?php if (!$movie): ?>
    <p>Movie not found</p>
<?php else: ?>
    <div class="row">
        <div class="col-md-4">
            <div class="poster-wrapper">
                <img src="../<?= htmlspecialchars($movie['poster_path']) ?>" alt="Poster">
            </div>
        </div>
        <div class="col-md-8">
            <!-- TITLE -->
            <h1 class="fw-bold" style="font-size: 32px;"><?= htmlspecialchars($movie['title']) ?></h1>
            <div class="movie-meta mb-3">
                <!-- GENRE -->
                <?php if (!empty($genres)): ?>
                    <?php foreach ($genres as $g): ?>
                        <span class="badge bg-secondary me-1 px-2">
                            <?= htmlspecialchars($g['genre_name']) ?>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- DURATION -->
                <span class="text-muted ms-2 movie-duration" data-min="<?= $movie['duration'] ?>">
                    <i class="fa-regular fa-clock me-1"></i>
                    <?= $movie['duration'] ?> min
                </span>
            </div>
            <!-- SINOPSIS -->
            <p><?= nl2br(htmlspecialchars($movie['synopsis'])) ?></p>

        <div class="card bg-light border-0 p-4 mt-4 shadow-sm">
            <h5 class="fw-bold mb-3">Book Tickets</h5>

            <?php if ($hasShowtimes): ?>
                <!-- ADA SHOWTIME -->
                <form action="seats.php" method="GET">
                    <input type="hidden" name="movie_id" value="<?= $movie['movie_id'] ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Select Date</label>
                            <select class="form-select" name="date" id="dateSelect" required>
                                <?php foreach ($showtimesByDate as $date => $times): ?>
                                    <option value="<?= $date ?>">
                                        <?= date('d M Y', strtotime($date)) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Showtime</label>
                            <div class="btn-group w-100" role="group" id="timeContainer">
                                <!-- radio button diisi JS -->
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="continueBtn" class="btn btn-warning w-100 fw-bold py-2 mt-2">
                        CONTINUE TO SEATS
                    </button>
                </form>


            <?php else: ?>
                <!-- TIDAK ADA SHOWTIME -->
                <div class="alert alert-warning mb-0 text-center fw-semibold">
                    Jadwal belum tersedia
                </div>
                <button class="btn btn-secondary w-100 fw-bold py-2 mt-3" disabled>
                    BOOKING CLOSED
                </button>
            <?php endif; ?>
        </div>

        </div>
    </div>
<?php endif; ?>
</main>


<?php include("../includes/footer.php"); ?>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const NOW_DATE = "<?= date('Y-m-d') ?>";
const NOW_TIME = "<?= date('H:i') ?>";

function playTrailer(el) {
    const iframe = el.querySelector('iframe');
    const thumb = el.querySelector('.trailer-thumb');
    const playBtn = el.querySelector('.play-btn');

    // set src baru saat diklik
    iframe.src = iframe.dataset.src;

    thumb.style.display = 'none';
    playBtn.style.display = 'none';
    iframe.style.display = 'block';
}

document.querySelectorAll('.movie-duration').forEach(el => {
    const min = parseInt(el.dataset.min);
    if (!isNaN(min)) {
        const h = Math.floor(min / 60);
        const m = min % 60;
        el.textContent = h > 0 ? `${h}h ${m}m` : `${m}m`;
    }
});

// filter tanggal showtimes
const showtimes = <?= json_encode($showtimesByDate) ?>;

const dateSelect = document.getElementById('dateSelect');
const timeContainer = document.getElementById('timeContainer');
const continueBtn = document.getElementById('continueBtn');

function renderTimes(date) {
    timeContainer.innerHTML = '';
    continueBtn.disabled = true;

    if (!showtimes[date]) return;

    const now = new Date();
    let firstEnabledChecked = false;

    showtimes[date].forEach((item, index) => {
        const showDateTime = new Date(date + ' ' + item.time);
        const isPast = showDateTime <= now;
        const id = `t${index}`;

        timeContainer.innerHTML += `
            <input type="radio" class="btn-check"
                   name="showtime_id"
                   id="${id}"
                   value="${item.id}"
                   ${isPast ? 'disabled' : ''}
                   ${!firstEnabledChecked && !isPast ? 'checked' : ''}>

            <label class="btn btn-outline-dark ${isPast ? 'disabled opacity-50' : ''}"
                   for="${id}">
                ${item.time.slice(0,5)}
            </label>
        `;

        if (!isPast && !firstEnabledChecked) {
            firstEnabledChecked = true;
            continueBtn.disabled = false;
        }
    });

    if (!firstEnabledChecked) {
        timeContainer.innerHTML = `
            <div class="text-muted small">
                Semua jadwal di tanggal ini sudah lewat
            </div>
        `;
        continueBtn.disabled = true;
    }
}

// render pertama
renderTimes(dateSelect.value);

// render saat ganti tanggal
dateSelect.addEventListener('change', () => {
    renderTimes(dateSelect.value);
});

</script>


</body>
</html>
