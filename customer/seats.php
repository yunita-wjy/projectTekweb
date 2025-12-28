<!DOCTYPE html>
<?php
session_start();

$BASE_PATH = '/proyek/projectTekweb/';

require "../config/connection.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit();
}
$user = $_SESSION['user'];


$showtime_id = $_GET['showtime_id'] ?? null;

if (!$showtime_id) {
    die("Showtime tidak ditemukan");
}


$query = "
    SELECT 
        m.title,
        m.poster_path,
        s.show_date,
        s.start_time,
        s.end_time,
        st.studio_name,
        st.studio_id
    FROM showtimes s
    JOIN movies m ON s.movie_id = m.movie_id
    JOIN studios st ON s.studio_id = st.studio_id
    WHERE s.showtime_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $showtime_id);
$stmt->execute();
$detail = $stmt->get_result()->fetch_assoc();

if (!$detail) {
    die("Data showtime tidak valid");
}

// // Booked Seats
// $showtime_id = $_GET['showtime_id'] ?? 0;

// // ambil kursi yang sudah dibooking
// $stmt = $conn->prepare("
//     SELECT s.seat_row, s.seat_column
//     FROM transaction_seats ts
//     JOIN seats s ON ts.seat_id = s.seat_id
//     WHERE ts.showtime_id = ?
// ");
// $stmt->bind_param("i", $showtime_id);
// $stmt->execute();
// $result = $stmt->get_result();

// $bookedSeats = [];
// while ($row = $result->fetch_assoc()) {
//     $bookedSeats[] = $row['seat_row'] . $row['seat_column'];
// }




?>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="<?= $BASE_PATH ?>style.css?v=2"/>

    <link rel="stylesheet" href="../seat.css">
    
    <style>
        .modal-poster {
            width: 120px;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
        }
        .modal-overlay {
            pointer-events: auto;
        }

        .transaction-modal {
            pointer-events: auto;
            z-index: 9999;
        }

        .btn-payment {
            pointer-events: auto;
            cursor: pointer;
        }

    </style>
</head>

<body>
    <?php include("../includes/header.php"); ?>
    
    <main>
        <div class="container">
            <h2 class="text-center mb-1">Seat Selection</h2>
            <p class="text-center text-muted mb-4">Select your preferred seats for the movie</p>

            <!-- SCREEN -->
            <div class="screen-wrapper">
                <div class="screen"></div>
                <div class="screen-text">Area Layar</div>
            </div>

            <!-- SEATS -->
            <div class="seating-wrapper">
                <div class="seating" id="seating"></div>
                
                <script>
                document.addEventListener('DOMContentLoaded', () => {
                    console.log('Document loaded, fetching booked seats...');
                    fetch(`backend/booked_seats.php?showtime_id=${window.SHOWTIME_ID}`)
                        .then(res => res.json())
                        .then(data => {
                            window.bookedSeats = data.bookedSeats || [];
                            generateSeatLayout();
                            loadPreviousSelection();
                        })
                        .catch(err => console.error(err));
                });
                </script>
            </div>

            <!-- LEGEND -->
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color" style="background-color: var(--seat-available);"></div>
                    <span class="legend-text">Available</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: var(--seat-booked);"></div>
                    <span class="legend-text">Booked</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: var(--seat-selected);"></div>
                    <span class="legend-text">Selected</span>
                </div>
            </div>

            <!-- FOOTER ACTION -->
            <div class="selection">
                <div class="seat-summary">
                    <div class="seat-list" id="seatList">
                        <!-- selected  -->
                    </div>
                    <div class="info" id="seatInfo" style="margin-top: 10px;">0 seat selected</div>
                </div>
                <div class="selectionBtn">
                    <div class="total-price" id="totalPrice">Total: Rp.0</div>
                    <div class="actions">
                        <button class="btn-clear" id="clearBtn">Clear</button>
                        <button class="btn-continue" id="continueBtn" disabled>Continue</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- POPUP TRANSACTION DETAIL -->
        <div class="modal-overlay" id="transactionModalOverlay">
            <div class="transaction-modal" onclick="event.stopPropagation()">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h3><i class="fas fa-ticket-alt me-2"></i>Transaction Details</h3>
                    <button class="close-modal" id="closeModalBtn">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Movie Info -->
                    <div class="movie-info-section">
                        <img id="modalPoster" class="modal-poster">
                        <div class="movie-details">
                            <h4 id="modalMovieTitle" class="fw-bold">Movie Title</h4>
                            <div class="detail-row">
                                <i class="far fa-calendar"></i>
                                <span id="modalShowDate">Date: -</span>
                            </div>
                            <div class="detail-row">
                                <i class="far fa-clock"></i>
                                <span id="modalShowTime">Time: -</span>
                            </div>
                            <div class="detail-row">
                                <i class="fas fa-map-marker-alt"></i>
                                <span id="modalStudio">Studio: -</span>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    <div class="transaction-details">
                        <div class="detail-section">
                            <h5><i class="fas fa-chair"></i> Selected Seats</h5>
                            <div class="seats-container" id="modalSeatsList">
                                <!-- Seats will be populated by JavaScript -->
                            </div>
                        </div>

                        <div class="detail-section">
                            <h5><i class="fas fa-receipt"></i> Payment Summary</h5>
                            <div class="summary-table">
                                <div class="summary-row">
                                    <span>Tickets (<span id="modalTicketCount">0</span>x)</span>
                                    <span id="modalTicketPrice">Rp 0,-</span>
                                </div>
                                <div class="summary-row">
                                    <span>Service Fee</span>
                                    <span>Rp 2.500,-</span>
                                </div>
                                <div class="summary-row total">
                                    <span><strong>Total Payment</strong></span>
                                    <span id="modalTotalPrice"><strong>Rp 0,-</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method (Optional) -->
                        <div class="detail-section">
                            <h5><i class="fas fa-credit-card"></i> Payment Method</h5>
                            <div class="payment-methods">
                                <div class="payment-option active">
                                    <i class="fas fa-wallet"></i>
                                    <span>E-Wallet (OVO/DANA/GoPay)</span>
                                </div>
                                <div class="payment-option">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Credit/Debit Card</span>
                                </div>
                                <div class="payment-option">
                                    <i class="fas fa-university"></i>
                                    <span>Bank Transfer</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button class="btn-cancel" id="cancelTransactionBtn">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button class="btn-payment" id="confirmPaymentBtn" onclick="confirmPaymentBtn('backend/payment.php')">
                        <i class="fas fa-lock me-2"></i>Pay Now
                    </button>
                </div>
                <?php if (isset($_GET['payment']) && $_GET['payment'] === 'success'): ?>
                    <script>
                        $(document).ready(function() {
                            showSwal(
                                'success',
                                'Success!',
                                'Anda berhasil logout!',
                                function() {
                                    window.history.replaceState({},
                                        document.title,
                                        'index.php'
                                    );
                                }
                            );
                        });
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </main>


<script>
    window.modalMovieData = <?= json_encode([
        'title'  => $detail['title'],
        'poster' => $BASE_PATH . $detail['poster_path'],
        'date'   => $detail['show_date'],
        'time'   => substr($detail['start_time'], 0, 5),
        'studio' => $detail['studio_name']
    ]) ?>;

    window.USER_ID = <?= (int)$user['user_id'] ?>;
    window.SHOWTIME_ID = <?= (int)$showtime_id ?>;



</script>

<script src="../seat.js"></script>



</body>

</html>