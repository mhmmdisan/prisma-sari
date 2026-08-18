@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="batik-bg py-4">
    <div class="container">
        <!-- Welcome Banner -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="welcome-card rounded-4 overflow-hidden shadow position-relative">
                    <div class="corner-decoration"></div>
                    <div class="corner-decoration2"></div>
                    <div class="card-body p-4 p-md-5 position-relative z-1">
                        <div class="row align-items-center">
                            <!-- Logo Prisma Sari - diperbesar -->
                            <div class="col-auto d-none d-sm-block">
                                <img src="{{ asset('images/logowebsite.png') }}" alt="Prisma Sari Catering" style="height: 80px; width: auto; filter: drop-shadow(0 2px 8px rgba(0,0,0,0.3));">
                            </div>
                            <div class="col-12 col-sm">
                                <h2 class="text-white fw-bold mb-2" style="font-size: 1.5rem;">
                                    Selamat Datang, {{ Auth::user()->name }}
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
                <div class="stat-card rounded-4 shadow-sm text-center h-100">
                    <div class="card-body py-4">
                        <div class="stat-icon mx-auto mb-3 d-flex align-items-center justify-content-center">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h5 class="fw-bold mb-2 stat-title">Keranjang Belanja</h5>
                        <p class="display-6 fw-bold text-success mb-2">{{ $jumlahKeranjang }}</p>
                        <p class="text-muted small mb-3">Item di keranjang Anda</p>
                        <a href="{{ route('pelanggan.keranjang.index') }}" class="btn stat-btn">
                            <i class="bi bi-arrow-right me-1"></i> Lihat Keranjang
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="stat-card rounded-4 shadow-sm text-center h-100">
                    <div class="card-body py-4">
                        <div
                            class="stat-icon stat-icon-warning mx-auto mb-3 d-flex align-items-center justify-content-center">
                            <i class="fas fa-box"></i>
                        </div>
                        <h5 class="fw-bold mb-2 stat-title stat-title-warning">Menu Produk</h5>
                        <p class="text-muted small mb-3">Tersedia berbagai pilihan</p>
                        <a href="{{ route('pelanggan.produk.index') }}" class="btn stat-btn stat-btn-warning">
                            <i class="bi bi-arrow-right me-1"></i> Lihat Produk
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="stat-card rounded-4 shadow-sm text-center h-100">
                    <div class="card-body py-4">
                        <div
                            class="stat-icon stat-icon-info mx-auto mb-3 d-flex align-items-center justify-content-center">
                            <i class="fas fa-cube"></i>
                        </div>
                        <h5 class="fw-bold mb-2 stat-title stat-title-info">Custom Snackbox</h5>
                        <p class="text-muted small mb-3">Buat snackbox sendiri</p>
                        <a href="{{ route('pelanggan.custom-snackbox.create') }}" class="btn stat-btn stat-btn-info">
                            <i class="bi bi-arrow-right me-1"></i> Buat Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk Terbaru (Jajanan Basah & Paketan) -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="section-header d-flex justify-content-between align-items-center mb-4">
                    <h3 class="section-title fw-bold mb-0">
                        <i class="bi bi-star-fill me-2"></i> Produk Jajanan Basah
                    </h3>
                    <a href="{{ route('pelanggan.produk.index') }}" class="view-all-link">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="row g-4">
                    @forelse($produkTerbaru as $produk)
                    @php
                    $kategoriNama = $produk->kategori->nama_kategori ?? '';
                    @endphp

                    @if($kategoriNama == 'Jajanan Basah' || $kategoriNama == 'Paketan')
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card rounded-4 shadow-sm overflow-hidden">
                            <div class="product-img-wrapper">
                                @if($produk->gambar)
                                <img src="{{ asset('storage/produk/' . $produk->gambar) }}"
                                    alt="{{ $produk->nama_produk }}" class="product-img">
                                @else
                                <div class="product-img-placeholder">
                                    <img src="{{ asset('images/logowebsite.png') }}" alt="Logo Prisma Sari" style="height: 60px; width: auto; opacity: 0.7;">
                                </div>
                                @endif
                                <div class="product-badge">
                                    <span>{{ $kategoriNama }}</span>
                                </div>
                            </div>
                            <div class="product-body text-center p-3">
                                <h6 class="product-title fw-bold">{{ Str::limit($produk->nama_produk, 30) }}</h6>
                                <p class="product-price fw-bold mb-2">{{ $produk->harga_format }}</p>
                                <a href="{{ route('pelanggan.produk.show', $produk->id) }}"
                                    class="btn btn-detail w-100">
                                    <i class="bi bi-info-circle me-1"></i> Detail & Pesan
                                </a>
                                <small class="product-minimal text-muted d-block mt-2">
                                    Minimal
                                    {{ $kategoriNama == 'Paketan' ? ($produk->min_order ?? 1) . ' order' : '50 pcs' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <div class="col-12">
                        <div class="empty-state text-center py-5 rounded-4">
                            <i class="bi bi-emoji-frown fs-1 text-muted"></i>
                            <p class="mt-3 mb-0">Belum ada produk terbaru.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Produk Paketan -->
        <div class="row">
            <div class="col-12">
                <div class="section-header d-flex justify-content-between align-items-center mb-4">
                    <h3 class="section-title fw-bold mb-0">
                        <i class="bi bi-box-seam me-2"></i> Produk Paketan
                    </h3>
                </div>
                <div class="row g-4">
                    @forelse($produkSnackbox as $produk)
                    @php
                    $kategoriNama = $produk->kategori->nama_kategori ?? '';
                    @endphp

                    @if($kategoriNama == 'Paketan')
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card rounded-4 shadow-sm overflow-hidden">
                            <div class="product-img-wrapper">
                                @if($produk->gambar)
                                <img src="{{ asset('storage/produk/' . $produk->gambar) }}"
                                    alt="{{ $produk->nama_produk }}" class="product-img">
                                @else
                                <div class="product-img-placeholder">
                                    <img src="{{ asset('images/logowebsite.png') }}" alt="Logo Prisma Sari" style="height: 60px; width: auto; opacity: 0.7;">
                                </div>
                                @endif
                                <div class="product-badge product-badge-gold">
                                    <span>Paketan</span>
                                </div>
                            </div>
                            <div class="product-body text-center p-3">
                                <h6 class="product-title fw-bold">{{ Str::limit($produk->nama_produk, 30) }}</h6>
                                <p class="product-price fw-bold mb-2">{{ $produk->harga_format }}</p>
                                <a href="{{ route('pelanggan.produk.show', $produk->id) }}"
                                    class="btn btn-detail w-100">
                                    <i class="bi bi-info-circle me-1"></i> Detail & Pesan
                                </a>
                                <small class="product-minimal text-muted d-block mt-2">
                                    Minimal {{ $produk->min_order ?? 1 }} order
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <div class="col-12">
                        <div class="empty-state text-center py-5 rounded-4">
                            <i class="bi bi-emoji-frown fs-1 text-muted"></i>
                            <p class="mt-3 mb-0">Belum ada produk paketan.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function tambahKeKeranjang(produkId) {
    fetch('{{ route("pelanggan.keranjang.tambah-produk") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                produk_id: produkId,
                jumlah: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✓ Produk ditambahkan ke keranjang!');
                location.reload();
            } else {
                alert('✗ Gagal: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan pada server');
        });
}
</script>
@endpush

<style>
/* ========== BACKGROUND BATIK ========== */
.batik-bg {
    background: linear-gradient(135deg, #faf8f0 0%, #f5f0e6 50%, #fef9e6 100%);
    position: relative;
    min-height: calc(100vh - 200px);
}

.batik-bg::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200' opacity='0.04'%3E%3Cpath fill='%232e7d32' d='M50,30 L70,15 L90,30 L70,45 Z M100,60 L120,45 L140,60 L120,75 Z M150,90 L170,75 L190,90 L170,105 Z M60,100 L80,85 L100,100 L80,115 Z M110,130 L130,115 L150,130 L130,145 Z M30,140 L50,125 L70,140 L50,155 Z M80,170 L100,155 L120,170 L100,185 Z'/%3E%3Cpath fill='%23ffc107' d='M130,20 L145,30 L130,40 L115,30 Z M180,50 L195,60 L180,70 L165,60 Z M40,60 L55,70 L40,80 L25,70 Z M90,20 L105,30 L90,40 L75,30 Z'/%3E%3C/svg%3E");
    background-repeat: repeat;
    background-size: 180px;
    pointer-events: none;
}

/* ========== WELCOME CARD ========== */
.welcome-card {
    background: linear-gradient(135deg, #0d4715 0%, #1b5e20 50%, #2e7d32 100%);
    position: relative;
    overflow: hidden;
}

.corner-decoration {
    position: absolute;
    top: -30px;
    right: -30px;
    width: 150px;
    height: 150px;
    background: radial-gradient(circle, rgba(255, 193, 7, 0.15) 0%, rgba(255, 193, 7, 0) 70%);
    border-radius: 50%;
}

.corner-decoration2 {
    position: absolute;
    bottom: -30px;
    left: -30px;
    width: 120px;
    height: 120px;
    background: radial-gradient(circle, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0) 70%);
    border-radius: 50%;
}

.z-1 {
    z-index: 1;
}

/* ========== STATS CARDS ========== */
.stat-card {
    background: white;
    transition: all 0.3s ease;
    border: none;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #ffc107, #2e7d32);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.stat-card:hover::before {
    transform: scaleX(1);
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 35px rgba(0, 0, 0, 0.1) !important;
}

.stat-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #f5f0e6, #e8e0cc);
    border-radius: 20px;
    transition: all 0.3s ease;
}

.stat-icon i {
    font-size: 2rem;
    color: #ffc107;
}

.stat-card:hover .stat-icon {
    transform: scale(1.05);
    background: linear-gradient(135deg, #ffc107, #ffb300);
}

.stat-card:hover .stat-icon i {
    color: white;
}

.stat-icon-warning {
    background: linear-gradient(135deg, #f5f0e6, #e8e0cc);
}

.stat-icon-warning i {
    color: #ffc107;
}

.stat-card:hover .stat-icon-warning {
    background: linear-gradient(135deg, #ffc107, #ffb300);
}

.stat-icon-info {
    background: linear-gradient(135deg, #f5f0e6, #e8e0cc);
}

.stat-icon-info i {
    color: #ffc107;
}

.stat-card:hover .stat-icon-info {
    background: linear-gradient(135deg, #ffc107, #ffb300);
}

.stat-title {
    color: #1b5e20;
}

.stat-title-warning {
    color: #1b5e20;
}

.stat-title-info {
    color: #1b5e20;
}

.stat-btn {
    background: transparent;
    border: 1.5px solid #2e7d32;
    border-radius: 50px;
    color: #2e7d32;
    font-weight: 600;
    transition: all 0.3s ease;
    padding: 6px 20px;
}

.stat-btn:hover {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #1b5e20;
    transform: translateY(-2px);
    border-color: transparent;
}

.stat-btn-warning {
    border-color: #2e7d32;
    color: #2e7d32;
}

.stat-btn-warning:hover {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #1b5e20;
}

.stat-btn-info {
    border-color: #2e7d32;
    color: #2e7d32;
}

.stat-btn-info:hover {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #1b5e20;
}

/* ========== SECTION HEADER ========== */
.section-title {
    font-size: 1.5rem;
    background: linear-gradient(135deg, #1b5e20, #2e7d32);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.section-title i {
    color: #ffc107;
    background: none;
    -webkit-background-clip: unset;
}

.view-all-link {
    color: #2e7d32;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
    padding: 5px 15px;
    border-radius: 30px;
    background: #f5f0e6;
}

.view-all-link:hover {
    background: #ffc107;
    color: #1b5e20;
    transform: translateX(3px);
}

/* ========== PRODUCT CARDS ========== */
.product-card {
    background: white;
    transition: all 0.3s ease;
    border: none;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 35px rgba(0, 0, 0, 0.1) !important;
}

.product-img-wrapper {
    position: relative;
    height: 160px;
    overflow: hidden;
    background: linear-gradient(135deg, #f5f0e6, #e8e0cc);
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-card:hover .product-img {
    transform: scale(1.08);
}

.product-img-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.product-img-placeholder img {
    transition: transform 0.3s ease;
}

.product-card:hover .product-img-placeholder img {
    transform: scale(1.1);
}

.product-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 600;
    color: white;
}

.product-badge-gold {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #1b5e20;
}

.product-body {
    padding: 1rem;
}

.product-title {
    font-size: 0.9rem;
    color: #1b5e20;
    margin-bottom: 0.5rem;
}

.product-price {
    font-size: 1rem;
    color: #2e7d32;
}

.btn-detail {
    background: transparent;
    border: 1.5px solid #2e7d32;
    border-radius: 50px;
    color: #2e7d32;
    font-size: 0.75rem;
    padding: 6px;
    transition: all 0.3s ease;
}

.btn-detail:hover {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #1b5e20;
    border-color: transparent;
}

.product-minimal {
    font-size: 0.7rem;
}

/* ========== EMPTY STATE ========== */
.empty-state {
    background: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.empty-state i {
    color: #ffc107;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 576px) {
    .stat-icon {
        width: 55px;
        height: 55px;
    }

    .stat-icon i {
        font-size: 1.5rem;
    }

    .stat-title {
        font-size: 0.95rem;
    }

    .display-6 {
        font-size: 1.5rem;
    }

    .stat-btn {
        font-size: 0.7rem;
        padding: 5px 12px;
    }

    .section-title {
        font-size: 1.2rem;
    }

    .view-all-link {
        font-size: 0.7rem;
        padding: 4px 10px;
    }

    .product-title {
        font-size: 0.8rem;
    }

    .product-price {
        font-size: 0.9rem;
    }

    .btn-detail {
        font-size: 0.65rem;
        padding: 5px;
    }
}

@media (min-width: 576px) and (max-width: 768px) {
    .stat-icon {
        width: 60px;
        height: 60px;
    }

    .stat-icon i {
        font-size: 1.75rem;
    }
}
</style>
@endsection