<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Prisma Sari Catering</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

    .register-container {
        max-width: 500px;
        width: 100%;
    }

    .register-card {
        background: white;
        border-radius: 32px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        padding: 40px 32px;
    }

    /* ===== LOGO DENGAN GAMBAR ===== */
    .logo-container {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo-container img {
        max-width: 200px;
        width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
        transition: transform 0.3s ease;
    }

    .logo-container img:hover {
        transform: scale(1.05);
    }

    /* Alternatif jika logo dalam kotak dengan background */
    .logo-box-wrapper {
        width: 100px;
        height: 100px;
        margin: 0 auto 12px;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(46, 125, 50, 0.2);
        background: linear-gradient(135deg, #1b5e20, #2e7d32);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
    }

    .logo-box-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    /* ===== AKHIR LOGO ===== */

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
        margin-bottom: 20px;
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

    .btn-register {
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

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(46, 125, 50, 0.3);
    }

    .login-link {
        text-align: center;
        font-size: 14px;
        color: #6c757d;
    }

    .login-link a {
        color: #ff8f00;
        font-weight: 600;
        text-decoration: none;
    }

    .login-link a:hover {
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .register-card {
            padding: 30px 20px;
        }

        h2 {
            font-size: 24px;
        }

        .logo-container img {
            max-width: 150px;
        }

        .logo-box-wrapper {
            width: 80px;
            height: 80px;
        }
    }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-card">
            <div class="logo-container">
                <img src="{{ asset('images/logo-prisma-sari.png') }}" alt="Prisma Sari Catering">
            </div>

            <h2>Daftar Akun</h2>
            <p class="subtitle">Bergabunglah dengan Prisma Sari Catering</p>

            <!-- Alert Error Kustom untuk Register -->
            @if($errors->has('name'))
            <div class="alert-custom alert-error-custom">
                <i class="fas fa-user"></i>
                <div class="alert-content">
                    <strong>Mohon isi</strong> nama lengkap Anda.
                </div>
            </div>
            @endif

            @if($errors->has('email'))
            @if(str_contains($errors->first('email'), 'already been taken') || str_contains($errors->first('email'),
            'taken'))
            <div class="alert-custom alert-error-custom">
                <i class="fas fa-envelope"></i>
                <div class="alert-content">
                    <strong>Mohon maaf,</strong> email sudah terdaftar. Silakan gunakan email lain.
                </div>
            </div>
            @else
            <div class="alert-custom alert-error-custom">
                <i class="fas fa-envelope"></i>
                <div class="alert-content">
                    <strong>Mohon masukkan</strong> email yang valid.
                </div>
            </div>
            @endif
            @endif

            @if($errors->has('password'))
            @if(str_contains($errors->first('password'), 'min'))
            <div class="alert-custom alert-error-custom">
                <i class="fas fa-lock"></i>
                <div class="alert-content">
                    <strong>Password minimal</strong> 6 karakter.
                </div>
            </div>
            @else
            <div class="alert-custom alert-error-custom">
                <i class="fas fa-lock"></i>
                <div class="alert-content">
                    <strong>Mohon maaf,</strong> {{ $errors->first('password') }}
                </div>
            </div>
            @endif
            @endif

            @if($errors->has('password_confirmation'))
            <div class="alert-custom alert-error-custom">
                <i class="fas fa-check-circle"></i>
                <div class="alert-content">
                    <strong>Konfirmasi password</strong> tidak cocok.
                </div>
            </div>
            @endif

            <!-- Alert untuk form kosong (client-side) -->
            <div id="emptyAlert" style="display: none;" class="alert-custom alert-warning-custom">
                <i class="fas fa-exclamation-circle"></i>
                <div class="alert-content">
                    <strong>Tolong isi</strong> semua data terlebih dahulu.
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                @csrf

                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="Masukkan nama lengkap" value="{{ old('name') }}" autofocus>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email"
                        value="{{ old('email') }}">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-check-circle"></i> Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="Konfirmasi password">
                </div>

                <button type="submit" class="btn-register" id="submitBtn">
                    <i class="fas fa-user-plus me-2"></i> Register
                </button>

                <div class="login-link">
                    Already have an account? <a href="{{ route('login') }}">Log in here</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Client-side validation untuk form kosong -->
    <script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        let name = document.getElementById('name').value.trim();
        let email = document.getElementById('email').value.trim();
        let password = document.getElementById('password').value.trim();
        let confirmPassword = document.getElementById('password_confirmation').value.trim();
        let emptyAlert = document.getElementById('emptyAlert');

        // Sembunyikan alert sebelumnya
        if (emptyAlert) emptyAlert.style.display = 'none';

        if (name === '' || email === '' || password === '' || confirmPassword === '') {
            e.preventDefault();

            if (emptyAlert) {
                emptyAlert.style.display = 'flex';

                // Hapus alert setelah 3 detik
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