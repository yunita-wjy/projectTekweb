<?php
    session_start();
    // require "../config/connection.php";
    // require "../includes/admin_auth.php";
    
    // if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    //     header("Location: ../auth/login.php");
    //     exit();
    // }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin Manage</title>
        <!-- Boostrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- jQuery library -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    
    <body>
    <style>
        /* Perbesar icon burger */
        .navbar-toggler {
            border: none;
            transform: scale(1.3);
        }

        /* Bikin icon burger putih */
        .navbar-dark .navbar-toggler-icon {
            filter: brightness(0) invert(1);
        }
        
        #sideMenu .nav-item:hover {
            font-weight: bold;
            background-color: grey;
            font-size: 18px;
        }

        #sideMenu .nav-item .nav-link:hover {
            font-weight: bold;
            background-color: grey;
            font-size: 18px;
            color: #00FFFF !important; 
        }
    </style>
        <div class="container-fluid px-0">
            <nav class="navbar navbar-dark bg-dark px-3 py-3 col-12">
                <!-- always visible burger -->
                <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sideMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="ms-auto d-flex align-items-center me-2">

                    <!-- admin text -->
                    <span class="text-white fw-bold me-4" style="font-size: 18px">ADMIN</span>

                    <!-- user admin dropdown (tanpa ikon segitiga) -->
                    <div class="dropdown me-4">
                        <button class="btn btn-outline-light  px-4 py-1" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false"
                                style="border-width: 2px; border-radius: 4px;">

                            <!-- NAMA ADMIN NYA BUTUH PHP-->
                            SARAH

                        </button>

                        <ul class="dropdown-menu dropdown-menu-end mt-2">
                            <li><a class="dropdown-item text-danger" href="../auth/logout.php">Logout</a></li>
                        </ul>
                    </div>

                </div>
            </nav>

            <!-- offcanvas side menu -->
            <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sideMenu">
                <div class="offcanvas-header">

                    <!-- logo -->
                    <div class="d-flex align-items-center">
                        <img src="../assets/filmVerse-dark.png" width="70" height="70" alt="" class="ms-2 me-1">
                        <h5 class="offcanvas-title mb-0 fw-bold" style="font-size: 30px; font-family: 'Courier New', Courier, monospace;">FilmVerse</h5>
                    </div>

                    <!-- close button  -->
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>

                <div class="offcanvas-body">
                    <h3 class="text-bold dark ms-3">Menu</h3>
                    <ul class="nav flex-column">
                        <li class="nav-item ms-4"><a class="nav-link text-white" href="#">Dashboard</a></li>
                        <li class="nav-item ms-4"><a class="nav-link text-white" href="#">Transactions</a></li>
                        <li class="nav-item ms-4"><a class="nav-link text-white" href="#">Manage Movies</a></li>
                        <li class="nav-item ms-4"><a class="nav-link text-white" href="#">Manage Showtimes</a></li>
                        <li class="nav-item ms-4"><a class="nav-link text-white" href="#">Manage Studios</a></li>
                        <li class="nav-item ms-4"><a class="nav-link text-white" href="#">Manage Prices</a></li>
                        <li class="nav-item ms-4"><a class="nav-link text-white" href="#">Manage Users</a></li>
                    </ul>
                </div>
            </div>


            <!-- CONTENT PAGE -->
             <div class="container px-4">


                <div class="container mt-5" style="max-width: 600px;">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white fw-bold">
                            PRICE SETTINGS
                        </div>

                        <!-- CARD BODY MULAI DI SINI -->
                        <div class="card-body">

                            <!-- CURRENT PRICES -->
                            <div class="mb-4 p-3 border rounded bg-light">
                                <h5 class="fw-bold mb-3">Current Prices</h5>

                                <p class="mb-1 ms-2">
                                    <strong>> Weekday Price:</strong> 
                                    <span class="text-primary fw-semibold">Rp 40,000</span>
                                </p>

                                <p class="mb-1 ms-2">
                                    <strong>> Weekend Price:</strong> 
                                    <span class="text-primary fw-semibold">Rp 45,000</span>
                                </p>
                            </div>

                            <!-- UPDATE FORM -->
                            <form method="POST">
                                <div class="mb-3 mt-3">
                                    <label class="form-label fw-bold">New Weekday Price</label>
                                    <input type="number" 
                                        class="form-control" 
                                        name="weekday_price" 
                                        placeholder="Enter new weekday price"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">New Weekend Price</label>
                                    <input type="number" 
                                        class="form-control" 
                                        name="weekend_price" 
                                        placeholder="Enter new weekend price"
                                        required>
                                </div>

                                <div class="text-end">
                                    <button class="btn btn-success fw-bold">Save Changes</button>
                                </div>
                            </form>

                        </div> <!-- card-body -->

                    </div> <!-- card -->
                </div>

     

                






             </div>




        </div>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        
    </body>


</html>