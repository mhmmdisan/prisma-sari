<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Prisma Sari Catering - @yield('title')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- FLATPICKR CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    @stack('styles')

    <style>
    * {
        font-family: 'Inter', sans-serif;
    }

    body {
        background: linear-gradient(135deg, #f8f9fa 0%, #e8f5e9 100%);
        min-height: 100vh;
    }

    /* ========== NAVBAR GRADIEN HIJAU ========== */
    .navbar {
        background: linear-gradient(135deg, #0d4715 0%, #1b5e20 30%, #2e7d32 70%, #43a047 100%);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 10px 0;
    }

    .navbar-brand {
        font-weight: 800;
        font-size: 1.4rem;
        color: white !important;
        transition: transform 0.3s ease;
    }

    @media (min-width: 768px) {
        .navbar-brand {
            font-size: 1.6rem;
        }
    }

    .navbar-brand i {
        color: #ffc107;
        margin-right: 8px;
    }

    .navbar-brand:hover {
        transform: scale(1.02);
    }

    /* Navbar Toggler */
    .navbar-toggler {
        border: none;
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 12px;
    }

    .navbar-toggler:focus {
        box-shadow: none;
    }

    .navbar-toggler-icon {
        filter: brightness(0) invert(1);
    }

    /* Navbar Links */
    .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        border-radius: 30px;
        padding: 8px 16px;
        margin: 0 2px;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        position: relative;
    }

    @media (min-width: 992px) {
        .nav-link {
            padding: 8px 20px;
            font-size: 0.95rem;
        }
    }

    .nav-link:hover {
        background-color: rgba(255, 193, 7, 0.2);
        color: white !important;
        transform: translateY(-2px);
    }

    .nav-link.active {
        background: linear-gradient(135deg, #ffc107, #ffb300);
        color: #1b5e20 !important;
        font-weight: 600;
    }

    .nav-link i {
        margin-right: 6px;
    }

    /* Cart Badge */
    .cart-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        font-size: 0.65rem;
        background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
        color: #333;
        font-weight: bold;
        padding: 3px 6px;
        border-radius: 20px;
    }

    /* User Avatar */
    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ffc107;
    }

    /* ========== DROPDOWN PROFILE - DIPERINDAH ========== */
    .dropdown-menu {
        border: none;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        margin-top: 12px;
        padding: 0;
        min-width: 260px;
        overflow: hidden;
        animation: dropdownFadeIn 0.3s ease;
    }

    @keyframes dropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* User Info Card di dalam Dropdown */
    .dropdown-user-info {
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
        padding: 16px 20px;
        border-bottom: 2px solid #ffc107;
    }

    .dropdown-user-info .user-avatar-large {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ffc107;
        margin-bottom: 12px;
    }

    .dropdown-user-info .user-name {
        font-weight: 700;
        color: #1b5e20;
        font-size: 1rem;
        margin-bottom: 4px;
    }

    .dropdown-user-info .user-email {
        font-size: 0.75rem;
        color: #2e7d32;
        word-break: break-word;
    }

    .dropdown-user-info .user-role {
        display: inline-block;
        background: linear-gradient(135deg, #2e7d32, #1b5e20);
        color: white;
        font-size: 0.7rem;
        padding: 3px 10px;
        border-radius: 50px;
        margin-top: 8px;
    }

    .dropdown-item {
        padding: 12px 20px;
        font-weight: 500;
        transition: all 0.2s ease;
        font-size: 0.9rem;
        color: #333;
    }

    .dropdown-item i {
        width: 24px;
        margin-right: 10px;
        font-size: 1rem;
    }

    .dropdown-item i.fa-user {
        color: #2e7d32;
    }

    .dropdown-item i.fa-sign-out-alt {
        color: #dc3545;
    }

    .dropdown-item:hover {
        background-color: #e8f5e9;
        color: #1b5e20;
        padding-left: 24px;
    }

    .dropdown-item.text-danger {
        color: #dc3545 !important;
    }

    .dropdown-item.text-danger:hover {
        background-color: #ffebee;
        color: #c62828 !important;
    }

    .dropdown-divider {
        margin: 0;
        border-color: #e0e0e0;
    }

    /* Tombol Dropdown Toggle */
    .dropdown-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .dropdown-toggle::after {
        transition: transform 0.3s ease;
    }

    .dropdown.show .dropdown-toggle::after {
        transform: rotate(180deg);
    }

    /* ========== FOOTER ========== */
    .footer {
        background: linear-gradient(135deg, #0d4715 0%, #1b5e20 50%, #2e7d32 100%);
        color: white;
        padding: 2rem 0 1.5rem;
        margin-top: 4rem;
    }

    @media (min-width: 768px) {
        .footer {
            padding: 3rem 0 2rem;
        }
    }

    .footer h5 {
        font-weight: 600;
        margin-bottom: 1rem;
        position: relative;
        display: inline-block;
        font-size: 1.1rem;
    }

    @media (min-width: 768px) {
        .footer h5 {
            font-size: 1.25rem;
            margin-bottom: 1.2rem;
        }
    }

    .footer h5:after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 40px;
        height: 3px;
        background: #ffc107;
        border-radius: 3px;
    }

    .footer a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: color 0.3s;
    }

    .footer a:hover {
        color: #ffc107;
    }

    .footer p {
        font-size: 0.85rem;
    }

    @media (min-width: 768px) {
        .footer p {
            font-size: 0.9rem;
        }
    }

    /* Main Content */
    main {
        min-height: calc(100vh - 200px);
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.5s ease forwards;
    }

    /* Responsive Container */
    .container {
        padding-left: 15px;
        padding-right: 15px;
    }

    @media (min-width: 576px) {
        .container {
            padding-left: 20px;
            padding-right: 20px;
        }
    }

    /* Flatpickr Styling */
    .flatpickr-day.disabled {
        background-color: #f8d7da !important;
        color: #721c24 !important;
        text-decoration: line-through !important;
        cursor: not-allowed !important;
    }

    .flatpickr-day.disabled:hover {
        background-color: #f5c6cb !important;
    }

    /* Responsive Dropdown Mobile */
    @media (max-width: 768px) {
        .dropdown-menu {
            position: absolute;
            min-width: 240px;
        }

        .dropdown-user-info {
            padding: 12px 16px;
        }

        .dropdown-user-info .user-avatar-large {
            width: 40px;
            height: 40px;
        }
    }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('pelanggan.dashboard') }}">
                <i class="fas fa-utensils"></i> Prisma <span style="color: #ffc107;">Sari</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Main Menu - Tengah -->
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}"
                            href="{{ route('pelanggan.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pelanggan.produk.*') ? 'active' : '' }}"
                            href="{{ route('pelanggan.produk.index') }}">
                            <i class="fas fa-box"></i> Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pelanggan.custom-snackbox.*') ? 'active' : '' }}"
                            href="{{ route('pelanggan.custom-snackbox.create') }}">
                            <i class="fas fa-cube"></i> Custom Snackbox
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link {{ request()->routeIs('pelanggan.keranjang.*') ? 'active' : '' }}"
                            href="{{ route('pelanggan.keranjang.index') }}">
                            <i class="fas fa-shopping-cart"></i> Keranjang
                            @php
                            $cartCount = Auth::check() ? App\Models\KeranjangDetail::where('user_id',
                            Auth::id())->count() : 0;
                            @endphp
                            @if($cartCount > 0)
                            <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pelanggan.pesanan.*') ? 'active' : '' }}"
                            href="{{ route('pelanggan.pesanan.index') }}">
                            <i class="fas fa-history"></i> Pesanan
                        </a>
                    </li>
                </ul>

                <!-- User Dropdown dengan Tampilan Lebih Cantik -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button"
                            data-bs-toggle="dropdown">
                            @if(Auth::user()->foto_profil)
                            <img src="{{ asset(Auth::user()->foto_profil) }}" class="user-avatar" alt="Profile">
                            @else
                            <i class="fas fa-user-circle fs-4"></i>
                            @endif
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!-- Header Profil dengan Foto -->
                            <li class="dropdown-user-info text-center text-md-start">
                                <div class="d-flex flex-column align-items-center align-items-md-start">
                                    @if(Auth::user()->foto_profil)
                                    <img src="{{ asset(Auth::user()->foto_profil) }}" class="user-avatar-large mb-2">
                                    @else
                                    <div class="user-avatar-large bg-success d-flex align-items-center justify-content-center mb-2"
                                        style="width: 50px; height: 50px; border-radius: 50%;">
                                        <i class="fas fa-user fa-2x text-white"></i>
                                    </div>
                                    @endif
                                    <div class="user-name">{{ Auth::user()->name }}</div>
                                    <div class="user-email">{{ Auth::user()->email }}</div>
                                    <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
                                </div>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <!-- Profil Saya -->
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="fas fa-user me-2"></i> Profil Saya
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <!-- Logout -->
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container fade-in-up">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Prisma Sari Catering</h5>
                    <p>Solusi catering terbaik untuk acara Anda dengan cita rasa rumah yang autentik.</p>
                    <div class="mt-3 d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-instagram fs-5"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-facebook fs-5"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-whatsapp fs-5"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Kontak Kami</h5>
                    <p class="mb-2">
                        <i class="fas fa-phone me-2"></i> 0813-2609-2609
                    </p>
                    <p class="mb-2">
                        <i class="fab fa-whatsapp me-2"></i> 0813-2587-2610
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-envelope me-2"></i> prismasari@catering.com
                    </p>
                    <p>
                        <i class="fas fa-map-marker-alt me-2"></i> Ngembalrejo - Bae - Kudus
                    </p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Jam Operasional</h5>
                    <p class="mb-2">
                        <i class="fas fa-clock me-2"></i> Senin - Sabtu: 08.00 - 17.00
                    </p>
                    <p>
                        <i class="fas fa-clock me-2"></i> Minggu: Libur
                    </p>
                    <hr style="background-color: rgba(255,255,255,0.2);">
                    <p class="mb-0 small">
                        <i class="fas fa-check-circle text-warning me-1"></i> Pesan H-2 untuk ketersediaan
                    </p>
                </div>
            </div>
            <div class="text-center mt-3 pt-3 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                <p class="mb-0 small">&copy; {{ date('Y') }} Prisma Sari Catering. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    @stack('scripts')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let csrfInput = document.querySelector('input[name="_token"]');
        if (csrfInput) {
            console.log('CSRF token found in layout');
        } else {
            console.error('CSRF token NOT found in layout');
        }
    });
    </script>
</body>

</html>