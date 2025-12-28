<?php
$BASE_PATH = '/proyek/projectTekweb/';
$user = $_SESSION['user'] ?? null;


// ambil nama depan
$firstName = 'User';
if (!empty($user['full_name'])) {
    $firstName = explode(' ', trim($user['full_name']))[0];
} elseif (!empty($user['username'])) {
    $firstName = $user['username'];
}



?>

<header id="main-header">
        <nav>
            <div class="logo">
                <img src="<?= $BASE_PATH ?>assets/filmVerse-light.png" alt="logo" />
                <span>FilmVerse</span>
            </div>
            <ul class="menu fw-bold gap-4">
                <li><a href="<?= $BASE_PATH ?>index.php">Home</a></li>
                <li><a href="<?= $BASE_PATH ?>customer/movies.php">Movies</a></li>
            </ul>
            <div class="akun">
                <?php if ($user): ?>
                    <div class="dropdown">
                        <a href="#"
                            class="dropdown-toggle profile-toggle"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                             <i class="fa-regular fa-user me-2"></i>
                             Hi, <strong><?= htmlspecialchars($firstName) ?></strong>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= $BASE_PATH ?>customer/profile.php">
                                    <i class="fa-regular fa-user me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger"
                                href="<?= $BASE_PATH ?>auth/logout.php">
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
