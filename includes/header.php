<header id="main-header">
        <nav>
            <div class="logo">
                <img src="../assets/filmVerse-light.png" alt="logo" />
                <span>FilmVerse</span>
            </div>
            <ul class="menu">
                <li><a href="#hero">Home</a></li>
                <li><a href="../customer/movies.php">Movies</a></li>
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
                                <a class="dropdown-item" href="customer/profile.php">
                                    <i class="fa-regular fa-user me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger"
                                    onclick="confirmLogout('auth/logout.php')">
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