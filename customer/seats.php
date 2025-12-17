<!DOCTYPE html>
<?php
session_start();
require "../config/connection.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit();
}
$user = $_SESSION['user'];
?>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Select Seat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../seat.css">
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <header id="main-header">
        <nav>
            <div class="logo">
                <img src="../assets/filmVerse-light.png" alt="logo" />
                <span>FilmVerse</span>
            </div>
            <ul class="menu">
                <li><a href="#hero">Home</a></li>
                <li><a href="#films">Movies</a></li>
            </ul>
            <div class="akun">
                <?php if ($user): ?>
                    <div class="dropdown">
                        <a href="#"
                            class="dropdown-toggle profile-toggle"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa-regular fa-user me-2"></i>
                            Hi, <strong><?= htmlspecialchars($user['username']) ?></strong>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fa-regular fa-user me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger"
                                    onclick="confirmLogout('../auth/logout.php')">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="customer/loginUI.php" class="login">Login</a>
                <?php endif; ?>
            </div>

            <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
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
        </nav>
    </header>
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

    <script src="../seat.js"></script>
</body>

</html>