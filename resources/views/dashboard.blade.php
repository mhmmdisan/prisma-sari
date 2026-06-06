<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <div class="py-4">
        <div class="container">
            <!-- Welcome Banner -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 rounded-4 overflow-hidden shadow-sm">
                        <div class="card-body p-4 p-md-5"
                            style="background: linear-gradient(135deg, #0d4715 0%, #1b5e20 50%, #2e7d32 100%);">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h2 class="text-white fw-bold mb-2" style="font-size: 1.5rem;">
                                        Selamat Datang, {{ Auth::user()->name }}! 🎉
                                    </h2>
                                    <p class="text-white-50 mb-0 small">
                                        Pesan catering dan snackbox favorit Anda dengan mudah
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 g-md-4 mb-5">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm h-100 text-center">
                        <div class="card-body py-4">
                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10"
                                style="width: 70px; height: 70px;">
                                <i class="bi bi-cart-check fs-1 text-success"></i>
                            </div>
                            <h5 class="fw-bold mb-2" style="color: #2e7d32;">Keranjang Belanja</h5>
                            <p class="text-muted small mb-3">Lihat dan kelola pesanan Anda</p>
                            <a href="{{ route('pelanggan.keranjang.index') }}"
                                class="btn btn-outline-success rounded-pill px-4">
                                <i class="bi bi-arrow-right me-1"></i> Lihat Keranjang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm h-100 text-center">
                        <div class="card-body py-4">
                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10"
                                style="width: 70px; height: 70px;">
                                <i class="bi bi-box-seam fs-1 text-warning"></i>
                            </div>
                            <h5 class="fw-bold mb-2" style="color: #e67e22;">Menu Produk</h5>
                            <p class="text-muted small mb-3">Lihat semua produk kami</p>
                            <a href="{{ route('pelanggan.produk.index') }}"
                                class="btn btn-outline-warning rounded-pill px-4">
                                <i class="bi bi-arrow-right me-1"></i> Lihat Produk
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm h-100 text-center">
                        <div class="card-body py-4">
                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10"
                                style="width: 70px; height: 70px;">
                                <i class="bi bi-cube fs-1 text-info"></i>
                            </div>
                            <h5 class="fw-bold mb-2" style="color: #0dcaf0;">Custom Snackbox</h5>
                            <p class="text-muted small mb-3">Buat snackbox sesuai selera</p>
                            <a href="{{ route('pelanggan.custom-snackbox.create') }}"
                                class="btn btn-outline-info rounded-pill px-4">
                                <i class="bi bi-arrow-right me-1"></i> Buat Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WhatsApp Custom Order Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 rounded-4 overflow-hidden shadow-sm">
                        <div class="card-body p-4"
                            style="background: linear-gradient(135deg, #075e54 0%, #128c7e 100%);">
                            <div class="row align-items-center gy-3">
                                <div class="col-12 col-md-8">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-white bg-opacity-20 rounded-circle p-3 flex-shrink-0">
                                            <i class="bi bi-whatsapp text-white fs-2"></i>
                                        </div>
                                        <div>
                                            <h5 class="text-white fw-bold mb-1">Butuh Pesanan Custom?</h5>
                                            <p class="text-white-50 mb-0 small">Tidak menemukan produk yang Anda cari?
                                                Pesan melalui WhatsApp</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 text-md-end">
                                    @php
                                    $user = Auth::user();
                                    $message = "Halo, saya ingin custom pesanan.%0A%0A";
                                    $message .= "Nama: " . $user->name . "%0A";
                                    $message .= "Email: " . $user->email . "%0A";
                                    $message .= "No. Telepon: " . ($user->no_telepon ?? '-') . "%0A%0A";
                                    $message .= "Saya ingin memesan produk custom.%0A";
                                    $message .= "Mohon infokan ketersediaan dan harganya. Terima kasih!";
                                    @endphp
                                    <a href="https://wa.me/6281326092609?text={{ $message }}" target="_blank"
                                        class="btn btn-light rounded-pill px-4 py-2 fw-semibold shadow-sm"
                                        style="color: #075e54;">
                                        <i class="bi bi-whatsapp me-2"></i> Pesan via WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .bg-opacity-20 {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .btn-outline-success,
    .btn-outline-warning,
    .btn-outline-info {
        transition: all 0.3s ease;
    }

    .btn-outline-success:hover,
    .btn-outline-warning:hover,
    .btn-outline-info:hover {
        transform: translateY(-2px);
    }

    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .card-body.py-4 {
            padding-top: 1.25rem !important;
            padding-bottom: 1.25rem !important;
        }

        .card-body .rounded-circle {
            width: 50px !important;
            height: 50px !important;
        }

        .card-body .fs-1 {
            font-size: 1.5rem !important;
        }

        h5.fw-bold {
            font-size: 0.95rem !important;
        }

        .btn-outline-success,
        .btn-outline-warning,
        .btn-outline-info {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            font-size: 0.8rem !important;
        }

        .bg-white.bg-opacity-20 {
            padding: 0.5rem !important;
        }

        .bg-white.bg-opacity-20 i {
            font-size: 1.25rem !important;
        }

        h5.text-white {
            font-size: 0.9rem !important;
        }

        .text-white-50.small {
            font-size: 0.7rem !important;
        }
    }

    @media (min-width: 576px) and (max-width: 768px) {
        .card-body .rounded-circle {
            width: 60px !important;
            height: 60px !important;
        }

        .card-body .fs-1 {
            font-size: 1.75rem !important;
        }
    }
    </style>
</x-app-layout>