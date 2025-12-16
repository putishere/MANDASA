<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Managemen Data Santri</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 1rem 1.25rem;
            border: 3px solid #28a745;
            border-radius: 15px;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(40, 167, 69, 0.15);
        }
        @media (max-width: 575.98px) {
            .login-container {
                padding: 0.75rem;
                max-width: 95%;
            }
            .login-card {
                padding: 1.25rem !important;
            }
        }
        .login-icon {
            font-size: 3.5rem;
            color: #28a745;
            margin-bottom: 0.75rem;
            text-align: center;
        }
        .login-title {
            font-size: clamp(1.3rem, 4vw, 1.6rem);
            font-weight: bold;
            color: #28a745;
            text-align: center;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
        }
        .login-subtitle {
            font-size: clamp(0.9rem, 2.5vw, 1.1rem);
            font-weight: 600;
            color: #28a745;
            text-align: center;
            margin-bottom: 1.25rem;
        }
        .login-card {
            background: white;
            border: 2px solid #28a745;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 15px rgba(40, 167, 69, 0.1);
        }
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }
        .form-control {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 0.6rem 0.875rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
        }
        .btn-login {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 0.7rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        .btn-login:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
        }
        .alert {
            border-radius: 8px;
            border: none;
            animation: slideDown 0.3s ease-out;
            font-size: 0.9rem;
        }
        .alert ul {
            padding-left: 1.25rem;
        }
        .alert ul li {
            margin-bottom: 0.25rem;
        }
        .btn-close {
            padding: 0.5rem;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .text-muted {
            color: #6c757d !important;
            font-size: 0.9rem;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 0.4rem;
            transition: color 0.3s ease;
            font-size: 0.9rem;
        }
        .password-toggle:hover {
            color: #28a745;
        }
        .password-wrapper {
            position: relative;
        }
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }
        .form-control.is-invalid {
            border-color: #dc3545;
            animation: shake 0.3s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="text-center mb-3">
            <div class="login-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <h1 class="login-title">MANAGEMEN DATA SANTRI</h1>
            <h2 class="login-subtitle">PP HS AL-FAKKAR</h2>
        </div>

        <div class="login-card">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="bi bi-exclamation-triangle-fill"></i> Perhatian!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="bi bi-exclamation-triangle-fill"></i> Error!</strong>
                    <p class="mb-0 mt-2">{{ session('error') }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><i class="bi bi-check-circle-fill"></i> Berhasil!</strong>
                    <p class="mb-0 mt-2">{{ session('success') }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="login-form">
                @csrf
                
                <div class="mb-3" style="margin-bottom: 0.75rem;">
                    <label for="username" class="form-label">
                        <i class="bi bi-person"></i> Username
                    </label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username atau email" autofocus required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock"></i> Password
                    </label>
                    <div class="password-wrapper">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                        <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-login" id="loginBtn">
                    <span id="loginBtnText">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk
                    </span>
                    <span id="loginBtnSpinner" class="d-none">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Memproses...
                    </span>
                </button>
            </form>
        </div>
    </div>

    <script>
        // Setup CSRF token untuk AJAX (jika diperlukan)
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.csrfToken = token.getAttribute('content');
        }
        
        // Refresh CSRF token hanya saat form akan di-submit (lebih efisien)
        // Tidak perlu auto-refresh yang bisa menyebabkan request lambat
        
        // Toggle show/hide password
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            } else {
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            }
        });

        // Auto-focus ke password setelah username terisi
        const usernameInput = document.getElementById('username');
        usernameInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim() !== '') {
                e.preventDefault();
                passwordInput.focus();
            }
        });

        // Loading state pada tombol login
        const loginForm = document.getElementById('login-form');
        const loginBtn = document.getElementById('loginBtn');
        const loginBtnText = document.getElementById('loginBtnText');
        const loginBtnSpinner = document.getElementById('loginBtnSpinner');

        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                // Pastikan CSRF token selalu fresh sebelum submit
                const formToken = document.querySelector('input[name="_token"]');
                const metaToken = document.querySelector('meta[name="csrf-token"]');
                
                if (formToken && metaToken) {
                    // Update token dari meta tag ke form sebelum submit
                    formToken.value = metaToken.getAttribute('content');
                }
                
                // Disable button untuk mencegah double submit
                loginBtn.disabled = true;
                loginBtnText.classList.add('d-none');
                loginBtnSpinner.classList.remove('d-none');
                
                // Jangan prevent default - biarkan form submit normal
            });
        }

        // Validasi real-time basic
        function validateForm() {
            let isValid = true;
            
            // Validasi username
            if (usernameInput.value.trim() === '') {
                usernameInput.classList.add('is-invalid');
                isValid = false;
            } else {
                usernameInput.classList.remove('is-invalid');
            }
            
            // Validasi password
            if (passwordInput.value.trim() === '') {
                passwordInput.classList.add('is-invalid');
                isValid = false;
            } else {
                passwordInput.classList.remove('is-invalid');
            }
            
            return isValid;
        }

        usernameInput.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });

        passwordInput.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });

        // Hapus invalid state saat user mulai mengetik
        usernameInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });

        passwordInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });

        // Validasi sebelum submit
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }
            });
        }

        // Auto-hide alert setelah 5 detik
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    </script>
</body>
</html>
