<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Prisma Sari Catering - Solusi Catering Terbaik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            overflow-x: hidden;
        }

        /* Navbar - lebih gelap dan kontras dengan background */
        .navbar {
            background: rgba(2, 28, 8, 0.95) !important;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            padding: 12px 0;
            border-bottom: 2px solid rgba(255, 215, 0, 0.2);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.3rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        @media (min-width: 768px) {
            .navbar-brand {
                font-size: 1.5rem;
            }
        }

        /* Logo diperbesar agar jelas */
        .navbar-brand img {
            height: 50px;
            width: auto;
            display: inline-block;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        @media (max-width: 767px) {
            .navbar-brand img {
                height: 40px;
            }
        }

        .navbar-brand span {
            color: #ffc107;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.95) !important;
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
            background-color: rgba(255, 193, 7, 0.25);
            color: #ffc107 !important;
            transform: translateY(-2px);
        }

        .btn-nav-register {
            background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
            color: #1b3d1b !important;
            font-weight: 700;
        }

        .btn-nav-register:hover {
            background: linear-gradient(135deg, #ffb300 0%, #ffa000 100%);
            color: #1b3d1b !important;
        }

        /* Hero Section - background sedikit diperbesar agar tidak menyatu dengan navbar */
        .hero {
            background: url('{{ asset("images/dashboard.png") }}') no-repeat center center;
            background-size: 115% 115%;
            min-height: 85vh;
            display: flex;
            align-items: center;
            position: relative;
        }

        @media (max-width: 768px) {
            .hero {
                background-size: 150% 150%;
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
            background: linear-gradient(135deg, #ffd700 0%, #ffb300 40%, #ffa000 70%, #ffd700 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            background-size: 200% 200%;
            animation: shimmerGold 3s ease-in-out infinite;
        }

        @keyframes shimmerGold {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
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
            color: #1b3d1b;
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
            color: #1b3d1b;
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

        /* ===== KATEGORI PRODUK CARDS DENGAN WARNA ===== */
        .category-card {
            transition: all 0.4s ease;
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            cursor: pointer;
            overflow: hidden;
            height: 100%;
            position: relative;
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 45px rgba(46, 125, 50, 0.2);
        }

        .category-card .card-img-top {
            height: 220px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .category-card:hover .card-img-top {
            transform: scale(1.08);
        }

        .category-card .card-body {
            padding: 1.5rem;
            text-align: center;
            color: white;
            position: relative;
            z-index: 2;
        }

        .category-card .card-title {
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: white;
        }

        @media (min-width: 768px) {
            .category-card .card-title {
                font-size: 1.4rem;
            }
        }

        .category-card .card-text {
            font-size: 0.85rem;
            margin-bottom: 1.2rem;
            opacity: 0.9;
            color: rgba(255, 255, 255, 0.9);
        }

        @media (min-width: 768px) {
            .category-card .card-text {
                font-size: 0.95rem;
            }
        }

        .category-card .btn-category {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 50px;
            padding: 8px 24px;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            backdrop-filter: blur(5px);
        }

        @media (min-width: 768px) {
            .category-card .btn-category {
                padding: 10px 32px;
                font-size: 0.9rem;
            }
        }

        .category-card .btn-category:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: scale(1.05);
            color: white;
            border-color: white;
        }

        .category-snackbox .card-body {
            background: linear-gradient(135deg, rgba(46, 125, 50, 0.85), rgba(27, 94, 32, 0.9));
        }

        .category-basah .card-body {
            background: linear-gradient(135deg, rgba(255, 143, 0, 0.85), rgba(230, 81, 0, 0.9));
        }

        .category-paketan .card-body {
            background: linear-gradient(135deg, rgba(46, 125, 50, 0.85), rgba(27, 94, 32, 0.9));
        }

        .category-hantaran .card-body {
            background: linear-gradient(135deg, rgba(255, 143, 0, 0.85), rgba(230, 81, 0, 0.9));
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
                <img src="{{ asset('images/logowebsite.png') }}" alt="Prisma Sari Catering Logo">
                Prisma <span>Sari</span> Catering
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
                        <a class="nav-link" href="#products">Produk</a>
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

    <!-- ===== PRODUK KATEGORI SECTION ===== -->
    <section id="products" class="py-5">
        <div class="container">
            <div class="text-center mb-4 mb-md-5">
                <h2 class="section-title" style="color: #1b5e20;">Kategori Produk Kami</h2>
                <p class="text-muted mt-3">Pilih kategori produk favorit Anda dari Prisma Sari Catering</p>
            </div>
            <div class="row g-3 g-md-4">
                <!-- Kategori 1: Snackbox (Hijau) -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="category-card category-snackbox">
                        <img src="{{ asset('images/snackbox.jpeg') }}" 
                             class="card-img-top" alt="Snackbox">
                        <div class="card-body">
                            <h5 class="card-title">Snackbox</h5>
                            <p class="card-text">Paket snackbox praktis untuk berbagai acara</p>
                            <a href="{{ route('login') }}" class="btn-category">
                                <i class="bi bi-eye"></i> Lihat Produk
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kategori 2: Jajanan Basah (Orange) -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="category-card category-basah">
                        <img src="{{ asset('images/jajanan-basah.jpeg') }}" 
                             class="card-img-top" alt="Jajanan Basah">
                        <div class="card-body">
                            <h5 class="card-title">Jajanan Basah</h5>
                            <p class="card-text">Aneka jajanan basah segar dan lezat</p>
                            <a href="{{ route('login') }}" class="btn-category">
                                <i class="bi bi-eye"></i> Lihat Produk
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kategori 3: Paketan (Hijau) -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="category-card category-paketan">
                        <img src="{{ asset('images/paketan.jpeg') }}" 
                             class="card-img-top" alt="Paketan">
                        <div class="card-body">
                            <h5 class="card-title">Paketan</h5>
                            <p class="card-text">Paket hemat untuk acara spesial Anda</p>
                            <a href="{{ route('login') }}" class="btn-category">
                                <i class="bi bi-eye"></i> Lihat Produk
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kategori 4: Hantaran (Orange) -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="category-card category-hantaran">
                        <img src="{{ asset('images/hantaran.jpeg') }}" 
                             class="card-img-top" alt="Hantaran">
                        <div class="card-body">
                            <h5 class="card-title">Hantaran</h5>
                            <p class="card-text">Hantaran elegan untuk berbagai momen</p>
                            <a href="{{ route('login') }}" class="btn-category">
                                <i class="bi bi-eye"></i> Lihat Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Lihat Semua Produk -->
            <div class="text-center mt-4 mt-md-5">
                <a href="{{ route('login') }}" class="btn btn-hero-primary px-4 py-2 px-md-5 py-md-3">
                    <i class="bi bi-box-arrow-in-right"></i> Login untuk Melihat Semua Produk
                </a>
            </div>
        </div>
    </section>
    <!-- ===== AKHIR PRODUK KATEGORI ===== -->

    <!-- Contact Section -->
    <section id="contact" class="py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e8f5e9 100%);">
        <div class="container">
            <div class="row g-3 g-md-4">
                <div class="col-12 col-md-6">
                    <div class="contact-info h-100">
                        <h3 class="fw-bold mb-3 mb-md-4 fs-4 fs-md-3"><i class="bi bi-headset me-2"></i> Hubungi Kami
                        </h3>
                        <p class="mb-2">
                            <i class="bi bi-geo-alt fs-6"></i> Jl. Serma Abdul Kadir, Sumber, Hadipolo, Jekulo, Kabupaten Kudus
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-instagram fs-6"></i> prismasari_snack
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-whatsapp fs-6"></i> 0813-2609-2609
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-envelope fs-6"></i> Prismasarisnack@gmail.com
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="contact-info h-100">
                        <h3 class="fw-bold mb-3 mb-md-4 fs-4 fs-md-3"><i class="bi bi-clock me-2"></i> Jam Operasional
                        </h3>
                        <p class="mb-2">
                            <i class="bi bi-calendar-check"></i> Setiap hari pukul 08.00 - 17.00 WIB
                        </p>
                        <hr class="my-3" style="background-color: rgba(255,255,255,0.2);">
                        <div class="d-flex gap-3 mt-2">
                            <a href="https://www.instagram.com/prismasari_snack?igsh=ZWtkNDQ2aGpla3Q3" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="text-white fs-5"><i class="bi bi-whatsapp"></i></a>
                            <a href="https://maps.app.goo.gl/Vz4FfujDYRZujrJp8?g_st=ic" class="text-white fs-5"><i class="bi bi-geo-alt"></i></a>
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