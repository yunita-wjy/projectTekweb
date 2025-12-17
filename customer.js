// customer.js - Versi lengkap untuk login dan signup page
document.addEventListener('DOMContentLoaded', function () {
    console.log('Page initialized');

    // ===== FUNGSI UMUM =====

    // Fungsi toggle password visibility
    function setupPasswordToggle(passwordInputId, toggleButtonId) {
        const togglePassword = document.getElementById(toggleButtonId);
        const passwordInput = document.getElementById(passwordInputId);

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;

                // Ubah ikon mata
                const eyeIcon = this.querySelector('i');
                if (eyeIcon) {
                    eyeIcon.classList.toggle('fa-eye');
                    eyeIcon.classList.toggle('fa-eye-slash');
                }
            });
        }
    }

    // Fungsi validasi email
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Fungsi validasi phone number (minimal 10 digit, hanya angka)
    function isValidPhone(phone) {
        return /^[0-9]{10,13}$/.test(phone.replace(/\D/g, ''));
    }

    // ===== DETEKSI HALAMAN =====

    // Cek apakah di halaman login atau signup
    const isLoginPage = document.getElementById('loginForm') &&
        document.getElementById('loginForm').action.includes('login.php');

    const isSignupPage = document.getElementById('signupForm') &&
        (document.getElementById('signupForm').action.includes('signup.php') ||
            document.title.includes('SignUp'));

    // ===== KODE UNTUK LOGIN PAGE =====
    if (isLoginPage) {
        console.log('Initializing login page...');

        // Setup password toggle untuk password utama
        setupPasswordToggle('password', 'togglePassword');

        // Form validation and submission untuk login
        const loginForm = document.getElementById('loginForm');

        if (loginForm) {
            // Real-time validation
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('emailError');
            const passwordInput = document.getElementById('password');
            const passwordError = document.getElementById('passwordError');
            const loginError = document.getElementById('loginError');

            if (emailInput && emailError) {
                emailInput.addEventListener('input', function () {
                    if (isValidEmail(this.value)) {
                        emailError.classList.add('d-none');
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        emailError.classList.remove('d-none');
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                });
            }


            // Form submission
            loginForm.addEventListener('submit', function (event) {
                event.preventDefault();

                // Reset error
                if (loginError) loginError.classList.add('d-none');

                // Validasi
                const emailValid = isValidEmail(emailInput.value);
                const passwordFilled = passwordInput.value.trim() !== '';

                if (!emailValid && emailError) {
                    emailError.classList.remove('d-none');
                    emailInput.classList.add('is-invalid');
                } else {
                    emailInput.classList.remove('is-invalid');
                }

                if (!passwordFilled) {
                    passwordInput.classList.add('is-invalid');
                    return;
                }

                // submit form ke server
                this.submit(); // Kirim ke login.php
            });
        }
    }

    // ===== KODE UNTUK SIGNUP PAGE =====
    else if (isSignupPage) {
        console.log('Initializing signup page...');

        // Setup password toggle untuk password dan confirm password
        setupPasswordToggle('password', 'togglePassword');
        setupPasswordToggle('confirmPassword', 'toggleConfirmPassword');

        // Form validation and submission untuk signup
        const signupForm = document.getElementById('signupForm');

        if (signupForm) {
            // Get all form elements
            const usernameInput = document.getElementById('username');
            const fullnameInput = document.getElementById('fullname');
            const phoneInput = document.getElementById('phone');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirmPassword');

            // Get error elements
            const phoneError = document.getElementById('phoneError');
            const emailError = document.getElementById('emailError');
            const fullnameError = document.getElementById('fullnameError');
            const passwordError = document.getElementById('passwordError');
            const confirmPasswordError = document.getElementById('confirmPasswordError');
            const signupError = document.getElementById('signUpError');

            // Real-time validation untuk phone
            if (phoneInput && phoneError) {
                phoneInput.addEventListener('input', function () {
                    // Format phone number: hapus karakter non-digit
                    this.value = this.value.replace(/\D/g, '');

                    if (isValidPhone(this.value)) {
                        phoneError.classList.add('d-none');
                        this.classList.remove('is-invalid');
                        this.classList.remove('is-valid');
                    } else {
                        phoneError.classList.remove('d-none');
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                });
            }

            // Real-time validation untuk email
            if (emailInput && emailError) {
                emailInput.addEventListener('input', function () {
                    if (isValidEmail(this.value)) {
                        emailError.classList.add('d-none');
                        this.classList.remove('is-invalid');
                        this.classList.remove('is-valid');
                    } else {
                        emailError.classList.remove('d-none');
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                });
            }

            // Real-time validation untuk password
            if (passwordInput && passwordError) {
                passwordInput.addEventListener('input', function () {
                    if (this.value.length >= 6) {
                        passwordError.classList.add('d-none');
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');

                        // Validasi confirm password jika sudah ada isi
                        if (confirmPasswordInput && confirmPasswordInput.value) {
                            if (this.value === confirmPasswordInput.value) {
                                confirmPasswordInput.classList.remove('is-invalid');
                                confirmPasswordInput.classList.add('is-valid');
                                if (confirmPasswordError) confirmPasswordError.classList.add('d-none');
                            } else {
                                confirmPasswordInput.classList.add('is-invalid');
                                confirmPasswordInput.classList.remove('is-valid');
                                if (confirmPasswordError) confirmPasswordError.classList.remove('d-none');
                            }
                        }
                    } else {
                        passwordError.classList.remove('d-none');
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                });
            }

            // Real-time validation untuk confirm password
            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', function () {
                    if (passwordInput && passwordInput.value === this.value && passwordInput.value.length >= 6) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                        if (confirmPasswordError) confirmPasswordError.classList.add('d-none');
                    } else {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                        if (confirmPasswordError) confirmPasswordError.classList.remove('d-none');
                    }
                });
            }

            // Real-time validation untuk fullname (minimal 2 kata)
            if (fullnameInput && fullnameError) {
                fullnameInput.addEventListener('input', function () {
                    const words = this.value.trim().split(/\s+/);

                    if (words.length >= 2) {
                        fullnameError.classList.add('d-none');
                        this.classList.remove('is-invalid');
                        this.classList.remove('is-valid'); // balik polosan
                    } else {
                        fullnameError.classList.remove('d-none');
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                });
            }


            // Form submission untuk signup
            signupForm.addEventListener('submit', function (event) {
                event.preventDefault();

                // Reset errors
                if (signupError) signupError.classList.add('d-none');

                // Validasi semua field
                let isValid = true;

                // Validasi username (minimal 3 karakter)
                if (usernameInput && usernameInput.value.length < 3) {
                    usernameInput.classList.add('is-invalid');
                    isValid = false;
                } else if (usernameInput) {
                    usernameInput.classList.remove('is-invalid');
                }

                // Validasi fullname (minimal 2 kata)
                if (fullnameInput && fullnameInput.value.trim().split(' ').length < 2) {
                    fullnameInput.classList.add('is-invalid');
                    isValid = false;
                } else if (fullnameInput) {
                    fullnameInput.classList.remove('is-invalid');
                }

                // Validasi phone
                if (phoneInput && !isValidPhone(phoneInput.value)) {
                    phoneInput.classList.add('is-invalid');
                    if (phoneError) phoneError.classList.remove('d-none');
                    isValid = false;
                } else if (phoneInput) {
                    phoneInput.classList.remove('is-invalid');
                    if (phoneError) phoneError.classList.add('d-none');
                }

                // Validasi email
                if (emailInput && !isValidEmail(emailInput.value)) {
                    emailInput.classList.add('is-invalid');
                    if (emailError) emailError.classList.remove('d-none');
                    isValid = false;
                } else if (emailInput) {
                    emailInput.classList.remove('is-invalid');
                    if (emailError) emailError.classList.add('d-none');
                }

                // Validasi password
                if (passwordInput && passwordInput.value.length < 6) {
                    passwordInput.classList.add('is-invalid');
                    if (passwordError) passwordError.classList.remove('d-none');
                    isValid = false;
                } else if (passwordInput) {
                    passwordInput.classList.remove('is-invalid');
                    if (passwordError) passwordError.classList.add('d-none');
                }

                // Validasi confirm password
                if (confirmPasswordInput && (!passwordInput || passwordInput.value !== confirmPasswordInput.value)) {
                    confirmPasswordInput.classList.add('is-invalid');
                    if (signupError) {
                        signupError.textContent = 'Password tidak sesuai. Silakan coba lagi.';
                        signupError.classList.remove('d-none');
                    }
                    isValid = false;
                } else if (confirmPasswordInput) {
                    confirmPasswordInput.classList.remove('is-invalid');
                }

                // Jika semua valid, submit form ke server
                if (isValid) {
                    console.log('Signup form valid, submitting to server...');

                    // Tampilkan pesan sukses (untuk demo)
                    // alert('Pendaftaran berhasil! (Ini hanya demo)\n\nDalam implementasi nyata, data akan dikirim ke server.');

                    // Untuk implementasi nyata, uncomment baris berikut:
                    this.submit(); // Kirim ke register.php

                    // Untuk demo, redirect ke login page setelah 2 detik
                    // setTimeout(() => {
                    //     window.location.href = 'loginUI.php';
                    // }, 2000);
                } else {
                    console.log('Signup form invalid');
                }
            });
        }
    }
});