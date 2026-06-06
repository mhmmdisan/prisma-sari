<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Prisma Sari Catering - Solusi Catering Terbaik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
    * {
        font-family: 'Inter', sans-serif;
    }

    body {
        overflow-x: hidden;
    }

    /* Navbar */
    .navbar {
        background: linear-gradient(135deg, #0d4715 0%, #1b5e20 30%, #2e7d32 70%, #43a047 100%);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 12px 0;
    }

    .navbar-brand {
        font-weight: 800;
        font-size: 1.3rem;
        color: white !important;
    }

    @media (min-width: 768px) {
        .navbar-brand {
            font-size: 1.5rem;
        }
    }

    .navbar-brand i {
        color: #ffc107;
        margin-right: 8px;
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        border-radius: 8px;
        padding: 8px 14px;
        margin: 0 2px;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }

    @media (min-width: 992px) {
        .nav-link {
            padding: 8px 18px;
            font-size: 1rem;
        }
    }

    .nav-link:hover {
        background-color: rgba(255, 193, 7, 0.2);
        color: white !important;
        transform: translateY(-2px);
    }

    .btn-nav-register {
        background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
        color: #333 !important;
        font-weight: 600;
    }

    .btn-nav-register:hover {
        background: linear-gradient(135deg, #ffb300 0%, #ffa000 100%);
        color: #333 !important;
    }

    /* Hero Section (Tanpa Overlay Hijau) */
    .hero {
        background: url('https://images.unsplash.com/photo-1555244162-803834f70033?w=1600') no-repeat center center;
        background-size: cover;
        min-height: 85vh;
        display: flex;
        align-items: center;
        position: relative;
    }

    @media (max-width: 768px) {
        .hero {
            min-height: 70vh;
        }
    }

    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.4) 100%);
    }

    .hero-content {
        position: relative;
        z-index: 2;
        animation: fadeInUp 0.6s ease;
    }

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

    .hero h1 {
        font-size: 2rem;
        font-weight: 800;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
    }

    @media (min-width: 768px) {
        .hero h1 {
            font-size: 2.8rem;
        }
    }

    @media (min-width: 992px) {
        .hero h1 {
            font-size: 3.5rem;
        }
    }

    .hero h1 span {
        background: linear-gradient(135deg, #ffc107, #ff8f00);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .hero .lead {
        font-size: 1rem;
    }

    @media (min-width: 768px) {
        .hero .lead {
            font-size: 1.25rem;
        }
    }

    .btn-hero-primary {
        background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
        border: none;
        color: #333;
        font-weight: 700;
        padding: 10px 24px;
        border-radius: 50px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    @media (min-width: 768px) {
        .btn-hero-primary {
            padding: 12px 32px;
            font-size: 1rem;
        }
    }

    .btn-hero-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: #333;
    }

    .btn-hero-outline {
        border: 2px solid white;
        background: transparent;
        color: white;
        font-weight: 600;
        padding: 8px 22px;
        border-radius: 50px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    @media (min-width: 768px) {
        .btn-hero-outline {
            padding: 10px 28px;
            font-size: 1rem;
        }
    }

    .btn-hero-outline:hover {
        background: white;
        color: #2e7d32;
        transform: translateY(-3px);
    }

    /* Feature Cards */
    .feature-card {
        transition: all 0.3s ease;
        padding: 1.5rem;
        text-align: center;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        background: white;
        border: none;
        height: 100%;
    }

    @media (min-width: 768px) {
        .feature-card {
            padding: 2rem;
        }
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(46, 125, 50, 0.15);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.2rem;
    }

    @media (min-width: 768px) {
        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
        }
    }

    .feature-icon i {
        font-size: 1.8rem;
        color: #2e7d32;
    }

    @media (min-width: 768px) {
        .feature-icon i {
            font-size: 2.5rem;
        }
    }

    /* Section Titles */
    .section-title {
        position: relative;
        margin-bottom: 2rem;
        font-weight: 700;
        font-size: 1.5rem;
    }

    @media (min-width: 768px) {
        .section-title {
            margin-bottom: 3rem;
            font-size: 2rem;
        }
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
        border-radius: 2px;
    }

    @media (min-width: 768px) {
        .section-title:after {
            bottom: -15px;
            width: 60px;
            height: 4px;
        }
    }

    /* Menu Cards */
    .menu-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        cursor: pointer;
        overflow: hidden;
        padding: 1rem;
    }

    .menu-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(46, 125, 50, 0.15);
    }

    .menu-icon {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.8rem;
    }

    @media (min-width: 768px) {
        .menu-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
        }
    }

    .menu-icon i {
        font-size: 1.5rem;
        color: #2e7d32;
    }

    @media (min-width: 768px) {
        .menu-icon i {
            font-size: 2rem;
        }
    }

    .menu-card h6 {
        font-size: 0.8rem;
    }

    @media (min-width: 768px) {
        .menu-card h6 {
            font-size: 1rem;
        }
    }

    /* Contact Section */
    .contact-info {
        background: linear-gradient(135deg, #0d4715 0%, #1b5e20 50%, #2e7d32 100%);
        border-radius: 20px;
        padding: 1.5rem;
        color: white;
    }

    @media (min-width: 768px) {
        .contact-info {
            padding: 2rem;
        }
    }

    .contact-info i {
        color: #ffc107;
        margin-right: 12px;
    }

    .contact-info p {
        font-size: 0.85rem;
    }

    @media (min-width: 768px) {
        .contact-info p {
            font-size: 1rem;
        }
    }

    /* Footer */
    .footer {
        background: linear-gradient(135deg, #0d4715 0%, #1b5e20 50%, #2e7d32 100%);
        color: white;
        padding: 1.5rem 0;
    }

    @media (min-width: 768px) {
        .footer {
            padding: 2rem 0;
        }
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
        font-size: 0.75rem;
    }

    @media (min-width: 768px) {
        .footer p {
            font-size: 0.85rem;
        }
    }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-shop me-2"></i> Prisma <span style="color: #ffc107;">Sari</span> Catering
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-1 gap-md-2">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                    @if(Route::has('login'))
                    @auth
                    <li class="nav-item">
                        <a class="nav-link btn-nav-register" href="{{ url('/pelanggan/dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-nav-register" href="{{ route('register') }}">Daftar</a>
                    </li>
                    @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero" style="margin-top: 56px;">
        <div class="container hero-content">
            <div class="row">
                <div class="col-12 text-center text-white">
                    <h1 class="mb-2 mb-md-3">Prisma <span>Sari</span> Catering</h1>
                    <p class="lead mb-2 mb-md-3">Solusi Catering Terbaik untuk Setiap Acara Anda</p>
                    <p class="mb-3 mb-md-4 px-2 px-md-0">Rasakan kelezatan hidangan berkualitas dengan pelayanan
                        profesional</p>
                    @guest
                    <div class="d-flex flex-wrap gap-2 gap-md-3 justify-content-center">
                        <a href="{{ url('/register') }}" class="btn btn-hero-primary">
                            <i class="bi bi-person-plus"></i> Pesan Sekarang
                        </a>
                        <a href="{{ url('/login') }}" class="btn btn-hero-outline">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </div>
                    @else
                    <a href="{{ url('/pelanggan/dashboard') }}" class="btn btn-hero-primary">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #f1f8e9 100%);">
        <div class="container">
            <div class="text-center mb-4 mb-md-5">
                <h2 class="section-title" style="color: #1b5e20;">Mengapa Memilih Kami?</h2>
            </div>
            <div class="row g-3 g-md-4">
                <div class="col-12 col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-cake2"></i>
                        </div>
                        <h4 class="fw-bold mb-2 mb-md-3">Custom Snackbox</h4>
                        <p class="text-muted small mb-0">Buat snackbox sesuai keinginan Anda dengan pilihan jajanan yang
                            beragam</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h4 class="fw-bold mb-2 mb-md-3">Pengiriman Tepat Waktu</h4>
                        <p class="text-muted small mb-0">Kami menjamin pesanan Anda sampai tepat waktu sesuai jadwal</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <h4 class="fw-bold mb-2 mb-md-3">Pembayaran Mudah</h4>
                        <p class="text-muted small mb-0">Transfer ke berbagai bank pilihan Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Populer Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-4 mb-md-5">
                <h2 class="section-title" style="color: #1b5e20;">Menu Populer Kami</h2>
            </div>
            <div class="row g-3 g-md-4">
                <div class="col-6 col-md-3">
                    <div class="menu-card card text-center p-2 p-md-3">
                        <div class="menu-icon mx-auto">
                            <i class="bi bi-egg-fried"></i>
                        </div>
                        <h6 class="fw-bold mb-1">Nasi Kuning</h6>
                        <p class="text-success fw-bold mb-0 small">Rp 25.000</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="menu-card card text-center p-2 p-md-3">
                        <div class="menu-icon mx-auto">
                            <i class="bi bi-cup-straw"></i>
                        </div>
                        <h6 class="fw-bold mb-1">Risoles</h6>
                        <p class="text-success fw-bold mb-0 small">Rp 3.000</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="menu-card card text-center p-2 p-md-3">
                        <div class="menu-icon mx-auto">
                            <i class="bi bi-cake"></i>
                        </div>
                        <h6 class="fw-bold mb-1">Lumpia</h6>
                        <p class="text-success fw-bold mb-0 small">Rp 3.000</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="menu-card card text-center p-2 p-md-3">
                        <div class="menu-icon mx-auto">
                            <i class="bi bi-cup-hot"></i>
                        </div>
                        <h6 class="fw-bold mb-1">Donat</h6>
                        <p class="text-success fw-bold mb-0 small">Rp 4.000</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4 mt-md-5">
                <a href="{{ route('login') }}" class="btn btn-hero-primary px-4 py-2 px-md-5 py-md-3">
                    <i class="bi bi-box-arrow-in-right"></i> Login untuk Memesan
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e8f5e9 100%);">
        <div class="container">
            <div class="row g-3 g-md-4">
                <div class="col-12 col-md-6">
                    <div class="contact-info h-100">
                        <h3 class="fw-bold mb-3 mb-md-4 fs-4 fs-md-3"><i class="bi bi-headset me-2"></i> Hubungi Kami
                        </h3>
                        <p class="mb-2">
                            <i class="bi bi-geo-alt fs-6"></i> Ngembalrejo - Bae - Kudus
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-telephone fs-6"></i> 0813-2609-2609
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-whatsapp fs-6"></i> 0813-2587-2610
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-envelope fs-6"></i> prismasari@catering.com
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="contact-info h-100">
                        <h3 class="fw-bold mb-3 mb-md-4 fs-4 fs-md-3"><i class="bi bi-clock me-2"></i> Jam Operasional
                        </h3>
                        <p class="mb-2">
                            <i class="bi bi-calendar-check"></i> Senin - Sabtu: 08.00 - 17.00
                        </p>
                        <p class="mb-3">
                            <i class="bi bi-calendar-x"></i> Minggu: Libur
                        </p>
                        <hr class="my-3" style="background-color: rgba(255,255,255,0.2);">
                        <div class="d-flex gap-3 mt-2">
                            <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="text-white fs-5"><i class="bi bi-whatsapp"></i></a>
                            <a href="#" class="text-white fs-5"><i class="bi bi-tiktok"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p class="mb-0 small">&copy; {{ date('Y') }} Prisma Sari Catering. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>