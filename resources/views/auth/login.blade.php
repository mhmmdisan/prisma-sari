<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Prisma Sari Catering</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
    * {
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        min-height: 100vh;
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 50%, #a5d6a7 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-container {
        max-width: 450px;
        width: 100%;
    }

    .login-card {
        background: white;
        border-radius: 32px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        padding: 40px 32px;
    }

    .logo-wrapper {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #0d4715, #2e7d32);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .logo-wrapper i {
        font-size: 32px;
        color: #ffc107;
    }

    h2 {
        font-size: 28px;
        font-weight: 800;
        color: #1b5e20;
        text-align: center;
        margin-bottom: 8px;
    }

    .subtitle {
        text-align: center;
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 32px;
    }

    /* ========== ALERT STYLE ELEGAN ========== */
    .alert-custom {
        border-radius: 16px;
        padding: 14px 18px;
        margin-bottom: 24px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
        border: none;
        animation: slideIn 0.3s ease;
    }

    .alert-custom i {
        font-size: 20px;
    }

    .alert-custom .alert-content {
        flex: 1;
    }

    .alert-success-custom {
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
        color: #1b5e20;
        border-left: 4px solid #2e7d32;
    }

    .alert-error-custom {
        background: linear-gradient(135deg, #ffebee, #ffcdd2);
        color: #c62828;
        border-left: 4px solid #d32f2f;
    }

    .alert-warning-custom {
        background: linear-gradient(135deg, #fff8e1, #ffecb3);
        color: #e67e22;
        border-left: 4px solid #ffc107;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        font-weight: 600;
        color: #2e7d32;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
    }

    .form-group label i {
        margin-right: 8px;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 14px 16px;
        font-size: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 16px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #4caf50;
        background-color: white;
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 12px;
        margin-top: 6px;
    }

    .checkbox-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #6c757d;
        cursor: pointer;
    }

    .checkbox-label input {
        width: 16px;
        height: 16px;
        cursor: pointer;
        accent-color: #2e7d32;
    }

    .forgot-link {
        font-size: 14px;
        color: #ff8f00;
        text-decoration: none;
        transition: color 0.3s;
    }

    .forgot-link:hover {
        color: #e65100;
        text-decoration: underline;
    }

    .btn-login {
        width: 100%;
        padding: 14px;
        font-size: 16px;
        font-weight: 700;
        background: linear-gradient(135deg, #2e7d32 0%, #43a047 50%, #4caf50 100%);
        border: none;
        border-radius: 16px;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 24px;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(46, 125, 50, 0.3);
    }

    .register-link {
        text-align: center;
        font-size: 14px;
        color: #6c757d;
    }

    .register-link a {
        color: #ff8f00;
        font-weight: 600;
        text-decoration: none;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .login-card {
            padding: 30px 20px;
        }

        h2 {
            font-size: 24px;
        }

        .logo-wrapper {
            width: 60px;
            height: 60px;
        }

        .logo-wrapper i {
            font-size: 28px;
        }
    }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-wrapper">
                <i class="fas fa-utensils"></i>
            </div>
            <h2>Welcome Back</h2>
            <p class="subtitle">Silakan login untuk melanjutkan</p>

            <!-- Alert Success -->
            @if(session('status'))
            <div class="alert-custom alert-success-custom">
                <i class="fas fa-check-circle"></i>
                <div class="alert-content">{{ session('status') }}</div>
            </div>
            @endif

            <!-- Alert Error untuk Email -->
            @if($errors->has('email'))
            <div class="alert-custom alert-error-custom">
                <i class="fas fa-envelope"></i>
                <div class="alert-content">
                    <strong>Mohon maaf,</strong> email yang Anda masukkan salah atau tidak terdaftar.
                </div>
            </div>
            @endif

            <!-- Alert Error untuk Password -->
            @if($errors->has('password'))
            <div class="alert-custom alert-error-custom">
                <i class="fas fa-lock"></i>
                <div class="alert-content">
                    <strong>Mohon maaf,</strong> kata sandi yang Anda masukkan salah.
                </div>
            </div>
            @endif

            <!-- Alert untuk form kosong (client-side) -->
            <div id="emptyAlert" style="display: none;" class="alert-custom alert-warning-custom">
                <i class="fas fa-exclamation-circle"></i>
                <div class="alert-content">
                    <strong>Tolong isi</strong> email dan kata sandi terlebih dahulu.
                </div>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf

                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror" placeholder="your@email.com"
                        value="{{ old('email') }}" autofocus>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="checkbox-wrapper">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-login" id="submitBtn">
                    <i class="fas fa-sign-in-alt me-2"></i> Log in
                </button>

                <div class="register-link">
                    Don't have an account? <a href="{{ route('register') }}">Register here</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom JavaScript Validation -->
    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        let email = document.getElementById('email').value.trim();
        let password = document.getElementById('password').value.trim();
        let emptyAlert = document.getElementById('emptyAlert');

        // Sembunyikan alert sebelumnya
        if (emptyAlert) emptyAlert.style.display = 'none';

        if (email === '' || password === '') {
            e.preventDefault();

            if (emptyAlert) {
                emptyAlert.style.display = 'flex';
                setTimeout(function() {
                    emptyAlert.style.display = 'none';
                }, 3000);
            }
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>