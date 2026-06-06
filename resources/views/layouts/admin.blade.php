<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - Prisma Sari Catering</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
    * {
        font-family: 'Inter', sans-serif;
    }

    body {
        background: linear-gradient(135deg, #faf8f0 0%, #f5f0e6 50%, #fef9e6 100%);
        min-height: 100vh;
        position: relative;
        margin: 0;
        padding: 0;
    }

    /* Background Batik */
    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200' opacity='0.04'%3E%3Cpath fill='%232e7d32' d='M50,30 L70,15 L90,30 L70,45 Z M100,60 L120,45 L140,60 L120,75 Z M150,90 L170,75 L190,90 L170,105 Z M60,100 L80,85 L100,100 L80,115 Z M110,130 L130,115 L150,130 L130,145 Z M30,140 L50,125 L70,140 L50,155 Z M80,170 L100,155 L120,170 L100,185 Z'/%3E%3Cpath fill='%23ffc107' d='M130,20 L145,30 L130,40 L115,30 Z M180,50 L195,60 L180,70 L165,60 Z M40,60 L55,70 L40,80 L25,70 Z M90,20 L105,30 L90,40 L75,30 Z'/%3E%3C/svg%3E");
        background-repeat: repeat;
        background-size: 180px;
        pointer-events: none;
        z-index: 0;
    }

    /* Reset container padding */
    .container-fluid {
        padding-left: 0;
        padding-right: 0;
    }

    /* ========== NAVBAR ADMIN ========== */
    .navbar-admin {
        background: linear-gradient(135deg, #0a3a10 0%, #1b5e20 30%, #2e7d32 70%, #43a047 100%);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        padding: 10px 20px;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    .navbar-brand-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .brand-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #ffc107;
    }

    .brand-text {
        display: flex;
        flex-direction: column;
    }

    .brand-title {
        font-weight: 800;
        font-size: 1.2rem;
        color: white;
        line-height: 1.2;
    }

    .brand-subtitle {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.7);
        letter-spacing: 0.5px;
    }

    @media (min-width: 768px) {
        .brand-title {
            font-size: 1.4rem;
        }

        .brand-subtitle {
            font-size: 0.75rem;
        }
    }

    /* User Info */
    .user-info {
        background: rgba(255, 255, 255, 0.12);
        border-radius: 50px;
        padding: 6px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .user-info:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        background: #ffc107;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1b5e20;
        font-weight: bold;
    }

    .user-name {
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .user-role {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.6);
    }

    /* Logout Button */
    .btn-logout {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50px;
        padding: 8px 20px;
        color: white;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .btn-logout:hover {
        background: #dc3545;
        border-color: #dc3545;
        transform: translateY(-2px);
    }

    /* ========== SIDEBAR ========== */
    .sidebar {
        background: linear-gradient(180deg, #0a3a10 0%, #1b5e20 50%, #0d4715 100%);
        height: calc(100vh - 70px);
        position: fixed;
        top: 70px;
        left: 0;
        width: 250px;
        transition: all 0.3s ease;
        box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        z-index: 999;
    }

    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.85);
        padding: 12px 20px;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 0.9rem;
        border-radius: 12px;
        margin: 4px 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar .nav-link i {
        width: 24px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .sidebar .nav-link:hover {
        background: rgba(255, 193, 7, 0.15);
        color: #ffc107;
        padding-left: 28px;
    }

    .sidebar .nav-link:hover i {
        transform: translateX(3px);
    }

    .sidebar .nav-link.active {
        background: linear-gradient(135deg, #ffc107, #ffb300);
        color: #1b5e20;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
    }

    .sidebar .nav-link.active i {
        color: #1b5e20;
    }

    /* Main Content - dengan margin left sesuai lebar sidebar */
    .main-content-wrapper {
        margin-top: 70px;
        margin-left: 250px;
        padding: 20px;
        transition: all 0.3s ease;
    }

    .main-content {
        background: transparent;
        min-height: calc(100vh - 90px);
    }

    /* Sidebar Toggle untuk Mobile */
    .sidebar-toggle {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        margin-right: 15px;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .sidebar {
            left: -250px;
            width: 250px;
        }

        .sidebar.show {
            left: 0;
        }

        .main-content-wrapper {
            margin-left: 0;
        }

        .sidebar-toggle {
            display: block;
        }

        .user-name,
        .user-role {
            display: none;
        }

        .brand-subtitle {
            display: none;
        }

        .brand-icon {
            width: 35px;
            height: 35px;
        }
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: #2e7d32;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #1b5e20;
    }
    </style>
    @stack('styles')
</head>

<body>

    <!-- Navbar Admin -->
    <nav class="navbar-admin">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="navbar-brand-wrapper">
                    <div class="brand-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div class="brand-text">
                        <span class="brand-title">
                            Prisma Sari <span style="color: #ffc107;">Admin</span>
                        </span>
                        <span class="brand-subtitle">Panel Administrasi & Manajemen</span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">Administrator</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="adminSidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.produk.*') ? 'active' : '' }}"
                    href="{{ route('admin.produk.index') }}">
                    <i class="bi bi-box"></i> Produk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.pesanan.index') ? 'active' : '' }}"
                    href="{{ route('admin.pesanan.index') }}">
                    <i class="bi bi-receipt"></i> Pesanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.pesanan.create-manual') ? 'active' : '' }}"
                    href="{{ route('admin.pesanan.create-manual') }}">
                    <i class="bi bi-whatsapp"></i> Tambah WA Order
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.metode-pembayaran.*') ? 'active' : '' }}"
                    href="{{ route('admin.metode-pembayaran.index') }}">
                    <i class="bi bi-credit-card"></i> Metode Pembayaran
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.tanggal.*') ? 'active' : '' }}"
                    href="{{ route('admin.tanggal.index') }}">
                    <i class="bi bi-calendar-x"></i> Tanggal Nonaktif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.jadwal-produksi.*') ? 'active' : '' }}"
                    href="{{ route('admin.jadwal-produksi.index') }}">
                    <i class="bi bi-calendar-check"></i> Jadwal Produksi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.kelola-akun.*') ? 'active' : '' }}"
                    href="{{ route('admin.kelola-akun.index') }}">
                    <i class="bi bi-people"></i> Kelola Akun
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content-wrapper">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    // Sidebar Toggle untuk Mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('adminSidebar')?.classList.toggle('show');
    });

    // Tutup sidebar saat klik di luar (mobile)
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('adminSidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        if (window.innerWidth <= 768 && sidebar && toggleBtn) {
            if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
    </script>

    @stack('scripts')
</body>

</html>