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
        <title>Manage Movies</title>
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

        /* tab button - outline only, rounded bigger */
        .tab-btn {
            background-color: transparent;    /* no fill */
            border: 2px solid #cfcfcf;        /* light outline when inactive */
            padding: 10px 28px;
            font-weight: 600;
            border-radius: 14px;              /* lebih rounded */
            color: #6c757d;                   /* inactive text color */
            transition: background-color .15s, color .15s, border-color .15s;
        }

        /* ACTIVE = black outline + black text (no fill) */
        .tab-btn.active {
            background-color: rgba(0,0,0,0);  /* tetap transparant */
            color: #000;
            border-color: #000;
        }

        /* INACTIVE look */
        .tab-btn.inactive {
            color: #6c757d;
            border-color: #cfcfcf;
        }

        /* hover for inactive */
        .tab-btn.inactive:hover {
            background-color: rgba(0,0,0,0.03);
            color: #444;
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

                <div class="d-flex gap-2 mb-3 mt-4">
                    <button id="btnMovies" class="tab-btn active me-1">Manage Movies</button>
                    <button id="btnGenres" class="tab-btn inactive">Manage Genres</button>
                </div>


                <div id="myTabContent">

                    <!-- tab 1: manage movies -->
                    <div class="tab-pane fade show active" id="contentMovies">

                        <!-- subtitile & search button -->
                        <div class="row d-flex align-items-center g-3">
                            <div class="col-lg-6 col-md-6 col-sm-12"> 
                                <h1 class="mb-4 mt-4">LIST OF MOVIES</h1>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12"> 
                                <form class="d-flex">
                                    <input class="form-control me-2" type="search" placeholder="Search Movie" aria-label="Search">
                                    <button class="btn btn-outline-primary" type="submit">Search</button>
                                </form>
                            </div>
                        </div>

                        <!-- table of movies -->
                        <div class="row mt-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Poster</th>
                                        <th>Title</th>
                                        <th>Genre</th>
                                        <th>Duration</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Action</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- KURANG SAMBUNGIN KE DATABASE -->
                                </tbody>
                            </table>
                        </div>

                        <!-- form add movie -->
                        <div class="card mt-4 mb-4 shadow overflow-hidden" style="width: 100%; ">
                            <!-- card header -->
                            <div class="card-title bg-primary text-white fw-bold px-4 py-3" style="border-top-left-radius: 5px; border-top-right-radius: 5px; font-size: 18px">
                                ADD MOVIE HERE
                            </div>
                            <!-- card body -->
                            <div class="card-body px-4 py-2">
                                <form>
                                    <div class="row align-items-center mb-3 mt-1">
                                        <label for="title" class="col-sm-3 col-form-label">Movie Title</label>
                                        <div class="col-sm-9">
                                            <input id="title" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3 mt-3">
                                        <label for="genre" class="col-sm-3 col-form-label">Genre</label>
                                        <div class="col-sm-9">
                                            <select id="genre" name="genreDropdown" class="form-select" style="width: 200px;">
                                                <!-- OPTION BASED DI DATABASE -->
                                                <option value="option1">Option 1</option>
                                                <option value="option2">Option 2</option>
                                                <option value="option3">Option 3</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3 mt-3">
                                        <label for="duration" class="col-sm-3 col-form-label">Duration</label>
                                        <div class="col-sm-9">
                                            <input id="duration" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3 mt-3">
                                        <label for="start-date" class="col-sm-3 col-form-label">Start Showing Date</label>
                                        <div class="col-sm-9">
                                            <input type="date" id="start-date" name="start-date" class="form-control" style="width: 200px;">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3 mt-3">
                                        <label for="end-date" class="col-sm-3 col-form-label">End Showing Date</label>
                                        <div class="col-sm-9">
                                            <input type="date" id="end-date" name="end-date" class="form-control" style="width: 200px;">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3 mt-3">
                                        <label for="synopsis" class="col-sm-3 col-form-label">Synopsis</label>
                                        <div class="col-sm-9">
                                            <textarea id="synopsis" name="synopsis" class="form-control" rows="5" cols="100" style="outline-color: #DCDCDC !important; "></textarea><br>
                                        </div>
                                    </div>      
                                    <div class="row align-items-center mb-3 mt-3">
                                        <label for="poster" class="col-sm-3 col-form-label">Poster (upload file .png)</label>
                                        <div class="col-sm-9">
                                            <input id="poster" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3 mt-3">
                                        <label for="trailer-link" class="col-sm-3 col-form-label">Trailer Youtube Link</label>
                                        <div class="col-sm-9">
                                            <input id="trailer-link" class="form-control" type="text">
                                        </div>
                                    </div> 
                                </form>

                                <div class="card-footer bg-transparent text-end">
                                    <button class="btn btn-success fw-bold mt-2" style="border-top-left-radius: 5px; border-top-right-radius: 5px; width: 80px;">Save</button>
                                </div>
                            </div>
                        </div>
                    
                    </div>

                    <!-- tab 2: manage genres -->
                    <div id="contentGenres" class="d-none">
                        <h1 class="mt-4">LIST OF GENRES</h1>

                        <table class="table table-striped mt-4">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Genre</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- AMBIL DARI DATABASE GENRE -->
                            </tbody>

                        </table>

                        <!-- form add genre -->
                        <div class="card mt-4 shadow">
                            <div class="card-title bg-primary text-white fw-bold px-4 py-3">
                                ADD GENRE HERE
                            </div>
                            <div class="card-body px-4">
                                <form>
                                    <div class="row align-items-center mb-3 mt-1">
                                        <label class="col-sm-3 col-form-label">Genre</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text">
                                        </div>
                                    </div>
                                </form>

                                <div class="text-end">
                                    <button class="btn btn-success fw-bold">Save</button>
                                </div>
                            </div>

                    </div>

                </div>


                








            </div>

        </div>
        

        <script>

            const btnMovies = document.getElementById("btnMovies");
            const btnGenres = document.getElementById("btnGenres");

            const contentMovies = document.getElementById("contentMovies");
            const contentGenres = document.getElementById("contentGenres");

            // saat klik tombol Movies
            btnMovies.onclick = function () {
                btnMovies.classList.add("active");
                btnMovies.classList.remove("inactive");

                btnGenres.classList.remove("active");
                btnGenres.classList.add("inactive");

                contentMovies.classList.remove("d-none");
                contentGenres.classList.add("d-none");
            };

            // saat klik tombol Genres
            btnGenres.onclick = function () {
                btnGenres.classList.add("active");
                btnGenres.classList.remove("inactive");

                btnMovies.classList.remove("active");
                btnMovies.classList.add("inactive");

                contentGenres.classList.remove("d-none");
                contentMovies.classList.add("d-none");
            };
        </script>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Chartist -->
        <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>

        
    </body>


</html>