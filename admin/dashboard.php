<?php
    session_start();
    require "../config/connection.php";
    // require "../includes/admin_auth.php";
    // if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    //     header("Location: ../auth/login.php");
    //     exit();
    // }

    // AMBIL DATA TABLE 
    // $query = "SELECT * FROM transactions ORDER BY date DESC, time DESC LIMIT 5";
    // $result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Admin Dashboard</title>
        <!-- Boostrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- jQuery library -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Chartist -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">

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
        
        .nav-item:hover {
            font-weight: bold;
            background-color: grey;
            font-size: 18px;
        }

        .nav-item .nav-link:hover {
            font-weight: bold;
            background-color: grey;
            font-size: 18px;
            color: #ffefd5 !important;
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
            <h1 class="p-4">Welcome, Admin!</h1>

            <div class="d-flex justify-content-center gap-5 flex-wrap">
                <div class="card shadow-sm text-center" style="width: 300px; height: 200px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <!-- BUTUH AMBIL DATA LEWAT PHP -->
                        <h2 class="fw-bold mb-4" style="font-size: 40px">12</h2>
                        <p class="mb-0 text-muted" style="font-size: 20px;">MOVIES NOW SHOWING</p>
                    </div>
                </div>

                <div class="card shadow-sm text-center" style="width: 300px; height: 200px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <!-- BUTUH AMBIL DATA LEWAT PHP -->
                        <h2 class="fw-bold mb-4" style="font-size: 40px">8</h2>
                        <p class="mb-0 text-muted"  style="font-size: 20px;">SHOWTIMES TODAY</p>
                    </div>
                </div>

                <div class="card shadow-sm text-center" style="width: 300px; height: 200px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <!-- BUTUH AMBIL DATA LEWAT PHP -->
                        <h2 class="fw-bold mb-4" style="font-size: 40px">5</h2>
                        <p class="mb-0 text-muted"  style="font-size: 20px;" >TICKETS SOLD TODAY</p>
                    </div>
                </div>
            </div>

            
                
            
                
            <div class="row px-4 mt-5">
                <!-- recent transactions -->
                <div class="col-12 col-sm-12 col-md-6 col-lg-8 mb-4">
                    <h3 class="mb-3">Recent Transactions</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>User</th>
                                <th>Movie</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- BUTUH AMBIL DATA LEWAT PHP -->
                        </tbody>

                    </table>
                    <button class="btn btn-primary rounded-2">Show More</button>
                </div>

                <!-- daily ticket sales -->
                <div class="col-12 col-sm-12 col-md-6 col-lg-4 mb-4">
                    <h3 class="mb-3">Daily Ticket Sales</h3>
                    <!-- Chart container tanpa card -->
                    <div style="
                        background: white;
                        border-radius: 8px;
                        padding: 20px;
                        border: 1px solid #ddd;
                        min-height: 300px;
                    ">
                        <div id="ticket-sales-chart" style="height: 250px;"></div>

                    <!-- BUTUH AMBIL DATA LEWAT PHP -->
                        <!-- <div class="card">
                        <div class="card-body">
                            <div id="ticket-sales-chart"></div>
                        </div>
                    </div> -->

                </div>
            </div>

            



            
                
        
        </div>



    </div>




    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chartist -->
     <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>

</body>


</html>


