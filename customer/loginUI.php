<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FilmVerse</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styleRegist.css?v=2" />
</head>

<body>
    <div class="login-box">
        <!-- Logo -->
        <div class="logo-container mb-3">
            <div class="logo-icon">
                <i class="fas fa-film"></i>
            </div>
            <h1 class="company-name">FilmVerse</h1>
        </div>

        <!-- Judul Login -->
        <h2 class="text-center mb-4">Login</h2>

        <!-- Form Login -->
        <form id="loginForm" action="../auth/login.php" method="POST">
            <!-- Email Input -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
                <div id="emailError" class="error-message d-none">Email tidak valid</div>
            </div>

            <!-- Password Input -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="password-container">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                <div id="passwordError" class="error-message d-none">Password minimal 6 karakter</div>
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn btn-login mb-3">Login</button>

            <!-- Error Message (akan muncul jika login gagal) -->
            <div id="loginError" class="alert alert-danger d-none text-center" role="alert">
                Email atau password salah. Silakan coba lagi.
            </div>
        </form>

        <!-- Register Link -->
        <div class="register-link">
            Tidak punya akun? <a href="signupUI.php">Daftar di sini</a>
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