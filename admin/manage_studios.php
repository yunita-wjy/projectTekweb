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
        <title>Admin Manage Showtimes</title>
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
                        <li class="nav-item ms-4"><a class="nav-link text-white" href="#">Manage Users</a></li>
                    </ul>
                </div>
            </div>


            <!-- CONTENT PAGE -->
             <div class="container px-4">
                <!-- subtitile & search button -->
                <div class="row d-flex align-items-center g-3">
                    <div class="col-lg-6 col-md-6 col-sm-12"> 
                        <h1 class="mb-4 mt-4">LIST OF STUDIOS</h1>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12"> 
                        <form class="d-flex">
                            <input class="form-control me-2" type="search" placeholder="Search Studio" aria-label="Search">
                            <button class="btn btn-outline-primary" type="submit">Search</button>
                        </form>
                    </div>
                </div>

                <!-- table of studios -->
                <div class="row mt-4">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Studio Name</th>
                                <th>Capacity</th>
                                <th>Status</th> <!--active/inactive-->
                                <th>Action</th> <!--edit/delete-->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- KURANG SAMBUNGIN KE DATABASE -->
                        </tbody>
                    </table>
                </div>

                <!-- form add studio -->
                <div class="card mt-4 mb-4 shadow overflow-hidden" style="width: 100%; ">
                    <!-- card header -->
                    <div class="card-title bg-primary text-white fw-bold px-4 py-3" style="border-top-left-radius: 5px; border-top-right-radius: 5px; font-size: 18px">
                        ADD STUDIO HERE
                    </div>
                    <!-- card body -->
                    <div class="card-body px-4">
                        <form>
                            <div class="row align-items-center mb-3 mt-1">
                                <label class="col-sm-3 col-form-label">Name of the Studio</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text">
                                </div>
                            </div>
                            <div class="row align-items-center mb-3 mt-1">
                                <label class="col-sm-3 col-form-label">Capacity (pax)</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text">
                                </div>
                            </div>
                            <div class="row align-items-center mb-3 mt-3">
                                <label for="genre" class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-9">
                                    <select class="form-select genre-select">
                                        <option value="" disabled selected>Pilih Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>

                                </div>
                            </div>

                        </form>
                        <!-- card footer -->
                        <div class="text-end">
                            <button class="btn btn-success fw-bold">Save</button>
                        </div>
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