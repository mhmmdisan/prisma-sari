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
       GLOBAL
       ============================================ */
    html, body {
        overflow-x: hidden; /* cegah scroll horizontal */
        margin: 0;
        padding: 0;
        background: #f5f7fa;
    }

    /* ============================================
       SIDEBAR PREMIUM - GRADIENT EMAS KE HIJAU
       ============================================ */
    .sidebar-premium {
        background: linear-gradient(180deg, #ffc107 0%, #2e7d32 50%, #1b5e20 100%);
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100vh;
        overflow-y: auto;
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

    .logo-area .brand-logo {
        display: block;
        margin: 0 auto 10px auto;
        height: 75px;
        width: auto;
        filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));
        transition: transform 0.3s ease;
    }

    .logo-area .brand-logo:hover {
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .logo-area .brand-logo {
            height: 60px;
        }
    }

    .logo-area h5 {
        font-weight: 700;
        margin-bottom: 5px;
        letter-spacing: 1px;
        color: white;
    }

    .logo-area small {
        font-size: 0.7rem;
        opacity: 0.8;
        color: rgba(255, 255, 255, 0.85);
    }

    /* ============================================
       MAIN CONTENT
       ============================================ */
    .main-content {
        background: #f5f7fa;
        min-height: 100vh;
        padding: 0; /* hapus padding di sini, akan diatur di container */
    }

    /* ============================================
       NAVBAR MOBILE
       ============================================ */
    .navbar-mobile {
        background: white;
        padding: 12px 16px;
        border-bottom: 1px solid #e9ecef;
        display: none;
    }
    .navbar-mobile .btn-toggle {
        background: none;
        border: none;
        font-size: 1.6rem;
        color: #1b5e20;
        padding: 0;
        line-height: 1;
    }
    .navbar-mobile .brand-mobile {
        font-weight: 700;
        color: #1b5e20;
        font-size: 1.2rem;
    }

    /* ============================================
       RESPONSIVE - MOBILE
       ============================================ */
    @media (max-width: 768px) {
        /* Sidebar menjadi offcanvas */
        .sidebar-premium {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            z-index: 1050;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            border-radius: 0 20px 20px 0;
            box-shadow: 4px 0 20px rgba(0,0,0,0.2);
        }
        .sidebar-premium.show {
            transform: translateX(0);
        }

        /* Backdrop */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.4);
            z-index: 1045;
            backdrop-filter: blur(2px);
        }
        .sidebar-backdrop.show {
            display: block;
        }

        /* Tampilkan navbar mobile */
        .navbar-mobile {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Main content: lebar penuh, tanpa padding berlebih */
        .main-content {
            width: 100% !important;
            padding: 0 !important;
            min-height: 100vh;
        }

        /* Container dalam main content */
        .main-content .container-fluid {
            padding-left: 16px !important;
            padding-right: 16px !important;
            padding-top: 16px !important;
            padding-bottom: 16px !important;
        }

        /* Hilangkan grid column yang kosong di mobile */
        .row.g-0 {
            margin: 0;
            padding: 0;
        }
        .row.g-0 > [class*="col-"] {
            padding: 0;
        }
    }

    /* ============================================
       SCROLLBAR
       ============================================ */
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
    <!-- Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <nav class="col-md-2 sidebar-premium vh-100 p-0" id="sidebar">
                <div class="logo-area">
                    <img src="{{ asset('images/logowebsite.png') }}" alt="Prisma Sari Catering" class="brand-logo">
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
            <main class="col-md-10 main-content" id="mainContent">
                <!-- Navbar Mobile -->
                <div class="navbar-mobile">
                    <button class="btn-toggle" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="brand-mobile">Prisma Sari</span>
                    <div style="width: 32px;"></div>
                </div>

                <!-- Container konten -->
                <div class="container-fluid px-4 py-3">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleBtn = document.getElementById('sidebarToggle');

            function toggleSidebar() {
                sidebar.classList.toggle('show');
                backdrop.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', toggleSidebar);
            }
            if (backdrop) {
                backdrop.addEventListener('click', toggleSidebar);
            }

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>