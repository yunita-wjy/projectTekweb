<!DOCTYPE html>
<?php
session_start();
require "../config/connection.php";
// require "../config/cek_login.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit();
}
$user = $_SESSION['user'];
$basePath = '/ProjectTekweb';
?>

<html lang="id">

<head>
    <title>Selection Seat</title>
    <?php include '../includes/head.php'; ?>
    <link rel="stylesheet" href="../assets/css/seats.css">
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include '../includes/header.php'; ?>
    <div class="container">
        <a href="javascript:history.back()">← Back</a>
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
            <script src="../seat.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                            console.log('Document loaded, fetching booked seats...');
                            fetch('backend/booked_seats.php')
                                .then(response => response.json())
                                .then(data => {
                                    console.log('Booked seats data:', data);
                                    console.log(data); // cek di console browser
                                    // misal dapat data dari PHP/DB sudah di-parse ke JS variable seatsFromDB

                                    const seatLayout = convertSeatsToLayout(data.seats);
                                    const bookedSeats = data.bookedSeats; // gunakan data dari backend
                                    console.log(seatLayout);

                                    // pastikan seatLayout global di-assign hasil konversi:
                                    window.seatLayout = seatLayout; // atau sesuaikan nama variable global
                                    window.bookedSeats = bookedSeats;
                                    generateSeatLayout();
                                })
                                .catch(error => console.error('Error fetching booked seats:', error));
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
        <div class="transaction-modal">
            <!-- Modal Header -->
            <div class="modal-header">
                <h3><i class="fas fa-ticket-alt me-2"></i>Transaction Details</h3>
                <button class="close-modal" id="closeModalBtn">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Movie Info -->
                <div class="movie-info-section">
                    <div class="movie-poster-placeholder">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="movie-details">
                        <h4 id="modalMovieTitle">Movie Title</h4>
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
                            'Pembelian tiket berhasil!',
                            function() {
                                window.location.href = 'movies.php';
                            }
                        );
                    });
                </script>
            <?php endif; ?>
        </div>
    </div>
    <!-- <script src="../seat.js"></script> -->
</body>

</html>