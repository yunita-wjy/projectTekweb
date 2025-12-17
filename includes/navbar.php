<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="movies.php">LOGO</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="movies.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#now-showing">Movies</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Theaters</a></li>
            </ul>
        </div>
        
        <div id="authContainer">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="dropdown">
                    <button class="btn btn-warning dropdown-toggle rounded-pill px-4" type="button" data-bs-toggle="dropdown">
                        Hi, <?= $_SESSION['full_name'] ?? 'User' ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="ticket.php">Tiket Saya</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../auth/logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="../auth/login.php" class="btn btn-outline-light rounded-pill px-4">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>