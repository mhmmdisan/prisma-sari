<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Prisma Sari Catering (Pemilik)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
    /* ============================================
           SIDEBAR PREMIUM - GRADIENT EMAS KE HIJAU
           ============================================ */
    .sidebar-premium {
        background: linear-gradient(180deg, #ffc107 0%, #2e7d32 50%, #1b5e20 100%);
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .sidebar-premium .nav-link {
        color: rgba(255, 255, 255, 0.9);
        padding: 12px 20px;
        margin: 4px 12px;
        border-radius: 12px;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .sidebar-premium .nav-link:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(5px);
        color: white;
    }

    .sidebar-premium .nav-link.active {
        background: white;
        color: #1b5e20;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar-premium .nav-link.active i {
        color: #ffc107;
    }

    .sidebar-premium .nav-link i {
        margin-right: 12px;
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
    }

    .sidebar-premium hr {
        border-color: rgba(255, 255, 255, 0.2);
        margin: 15px 20px;
    }

    .logo-area {
        padding: 25px 20px 15px 20px;
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 15px;
    }

    .logo-area h5 {
        font-weight: 700;
        margin-bottom: 5px;
        letter-spacing: 1px;
    }

    .logo-area small {
        font-size: 0.7rem;
        opacity: 0.8;
    }

    .bg-white-20 {
        background-color: rgba(255, 255, 255, 0.2);
    }

    /* ============================================
           MAIN CONTENT STYLING
           ============================================ */
    .main-content {
        background: #f5f7fa;
        min-height: 100vh;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar-premium {
            position: fixed;
            z-index: 1000;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .sidebar-premium.show {
            transform: translateX(0);
        }

        .main-content {
            width: 100% !important;
        }
    }

    /* Scrollbar styling */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #ffc107;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #1b5e20;
    }
    </style>
    @stack('styles')
</head>

<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar Premium -->
            <nav class="col-md-2 sidebar-premium vh-100 p-0 position-sticky top-0">
                <!-- Logo Area dengan Gambar -->
                <div class="logo-area">
                    @php
                    $logoPath = asset('images/logo-prisma-sari.png');
                    $logoExists = file_exists(public_path('images/logo-prisma-sari.png'));
                    @endphp
                    @if($logoExists)
                    <img src="{{ $logoPath }}" alt="Logo Prisma Sari"
                        style="width: 70px; height: 70px; object-fit: contain; margin-bottom: 10px; border-radius: 12px;">
                    @else
                    <div class="mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle bg-white-20"
                        style="width: 70px; height: 70px;">
                        <i class="bi bi-cake2 fs-1 text-white"></i>
                    </div>
                    @endif
                    <h5 class="text-white mb-0 mt-2 fw-bold">Prisma Sari</h5>
                    <small class="text-white-50">Panel Pemilik</small>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pemilik.dashboard') ? 'active' : '' }}"
                            href="{{ route('pemilik.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pemilik.laporan.*') ? 'active' : '' }}"
                            href="{{ route('pemilik.laporan.index') }}">
                            <i class="bi bi-graph-up"></i> Laporan Penjualan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pemilik.jadwal-produksi.*') ? 'active' : '' }}"
                            href="{{ route('pemilik.jadwal-produksi.index') }}">
                            <i class="bi bi-calendar-check"></i> Jadwal Produksi
                        </a>
                    </li>
                    <hr>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 main-content px-4 py-3 ms-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>