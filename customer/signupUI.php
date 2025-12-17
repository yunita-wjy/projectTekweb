<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp - FilmVerse</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styleRegist.css?v=2" />
</head>

<body>
    <div class="login-box">
        <!-- Logo -->
        <div class="logo-container mb-3 align-items-center">
            <div class="logo-icon">
                <i class="fas fa-film"></i>
                <h1 class="company-name">FilmVerse</h1>
            </div>
        </div>

        <!-- Judul  -->
        <h2 class="text-center mb-4">SignUp</h2>
        <a>Hello! New in here? </a>

        <!-- Form signUp -->
        <form id="signupForm" action="../auth/register.php" method="POST">
            <!-- Username Input -->
            <div class="mb-2">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" required>
            </div>
            <!-- Fullname Input -->
            <div class="mb-2">
                <label for="fullname" class="form-label">Fullname</label>
                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Masukkan nama" required>
                <div id="fullnameError" class="error-message d-none">minimal 2 kata</div>
            </div>
            <!-- Phone Input -->
            <div class="mb-2">
                <label for="phone" class="form-label">Phone number</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Masukkan nomor telepon" required>
                <div id="phoneError" class="error-message d-none">minimal 10 digit angka</div>
            </div>
            <!-- Email Input -->
            <div class="mb-2">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
                <div id="emailError" class="error-message d-none">Email tidak valid</div>
            </div>

            <!-- Password Input -->
            <div class="mb-2">
                <label for="password" class="form-label">Password</label>
                <div class="password-container d-flex align-items-center">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                    <button type="button" class="password-toggle" id="togglePassword">
                    </button>
                    <i class="far fa-eye"></i>
                </div>
                <div id="passwordError" class="error-message d-none">Password minimal 6 karakter</div>
            </div>
            <!-- Password Confirm Input -->
            <div class="mb-2">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <div class="password-container d-flex align-items-center">
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Masukkan password lagi" required>
                    <button type="button" class="password-toggle" id="toggleConfirmPassword">
                    </button>
                    <i class="far fa-eye"></i>
                </div>
                <div id="confirmPasswordError" class="error-message d-none">Password tidak sesuai</div>
            </div>

            <!-- SignUp Button -->
            <button type="submit" class="btn btn-login mb-3" name="register">Sign Up</button>

            <!-- Success Message (akan muncul jika register berhasil) -->
            <?php if (isset($_GET['signup']) && $_GET['signup'] === 'success'): ?>
                <div id="signUpSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
                    Pendaftaran berhasil! Silakan login.
                </div>
            <?php endif; ?>
        </form>

        <!-- Register Link -->
        <div class="register-link">
            Sudah punya akun? <a href="loginUI.php">Masuk</a>
        </div>
        <div class="text-center">
            <a href="../index.php">Back</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../customer.js"></script>
</body>

</html>