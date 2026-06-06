@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="batik-bg py-4">
    <div class="container position-relative" style="z-index: 1;">
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-title">
                    <h2 class="fw-bold mb-2"
                        style="background: linear-gradient(135deg, #1b5e20, #2e7d32, #ffc107); -webkit-background-clip: text; background-clip: text; color: transparent;">
                        <i class="bi bi-clock-history me-2" style="color: #ffc107;"></i>
                        Riwayat Pesanan
                    </h2>
                    <p class="text-muted">Lihat semua pesanan Anda</p>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert-custom alert-success-custom mb-4">
            <i class="fas fa-check-circle"></i>
            <div class="alert-content">{{ session('success') }}</div>
        </div>
        @endif

        @if(session('error'))
        <div class="alert-custom alert-error-custom mb-4">
            <i class="fas fa-exclamation-triangle"></i>
            <div class="alert-content">{{ session('error') }}</div>
        </div>
        @endif

        @if($pesanan->isEmpty())
        <div class="empty-cart text-center py-5">
            <div class="empty-cart-icon mx-auto mb-3">
                <i class="bi bi-inbox"></i>
            </div>
            <h4 class="mb-2">Belum Ada Pesanan</h4>
            <p class="text-muted mb-4">Anda belum memiliki riwayat pesanan</p>
            <a href="{{ route('pelanggan.produk.index') }}" class="btn btn-primary-custom px-4 py-2">
                <i class="bi bi-shop me-2"></i> Mulai Belanja
            </a>
        </div>
        @else
        <div class="row">
            @foreach($pesanan as $item)
            <div class="col-12 mb-4">
                <div class="card border-0 rounded-4 shadow-sm hover-card">
                    <div class="card-header bg-white rounded-top-4 py-3 d-flex justify-content-between align-items-center flex-wrap"
                        style="border-bottom: 2px solid #ffc107;">
                        <div>
                            <strong class="text-success fw-bold">
                                <i class="bi bi-receipt me-1"></i> {{ $item->nomor_pesanan }}
                            </strong>
                            <span class="text-muted ms-2 small">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal_pesanan)->locale('id')->translatedFormat('d F Y H:i') }}
                            </span>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-2 mt-sm-0">
                            @if($item->is_whatsapp_order)
                            <span class="badge-wa">
                                <i class="bi bi-whatsapp"></i> Custom Order
                            </span>
                            @endif

                            @if($item->status == 'terlambat')
                            <span class="badge-terlambat">
                                <i class="bi bi-exclamation-triangle"></i> Terlambat ⚠️
                            </span>
                            @else
                            <span class="badge-custom {{ $item->status_badge['class'] }}">
                                {{ $item->status_badge['label'] }}
                            </span>
                            @endif

                            <span class="badge-custom {{ $item->status_pembayaran_badge['class'] }}">
                                {{ $item->status_pembayaran_badge['label'] }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="fw-bold mb-3" style="color: #1b5e20;">
                                    <i class="bi bi-box-seam me-2"></i>Detail Pesanan:
                                </h6>
                                <ul class="list-unstyled small ps-3 mb-0" style="border-left: 3px solid #ffc107;">
                                    @foreach($item->detailPesanan->take(3) as $detail)
                                    <li class="mb-2">
                                        <i class="bi bi-dot text-success fs-5"></i>
                                        <span class="fw-semibold">{{ $detail->nama_item }}</span>
                                        <span class="text-muted">x{{ $detail->jumlah }}</span>
                                        <span class="text-success float-end fw-semibold">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </span>
                                    </li>
                                    @endforeach
                                    @if($item->detailPesanan->count() > 3)
                                    <li class="text-muted mt-1">
                                        <i class="bi bi-three-dots"></i> dan {{ $item->detailPesanan->count() - 3 }}
                                        item lainnya
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <div class="p-3 rounded-3"
                                    style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9);">
                                    <h5 class="text-success fw-bold mb-2">Total Pembayaran</h5>
                                    <h3 class="text-success fw-bold mb-2">
                                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                    </h3>
                                    <p class="text-muted small mb-0">
                                        <i class="bi bi-calendar-event me-1 text-warning"></i>
                                        Ambil:
                                        {{ \Carbon\Carbon::parse($item->tanggal_pengambilan)->locale('id')->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                                <a href="{{ route('pelanggan.pesanan.show', $item->id) }}"
                                    class="btn btn-outline-success rounded-pill px-4 mt-3">
                                    <i class="bi bi-eye me-2"></i> Detail Pesanan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- PAGINATION SECTION -->
        <div class="pagination-container mt-4">
            <div class="pagination-info-wrapper text-center mb-3">
                <span class="pagination-info-text">
                    <i class="bi bi-info-circle-fill text-warning me-2"></i>
                    Menampilkan {{ $pesanan->firstItem() ?? 0 }} - {{ $pesanan->lastItem() ?? 0 }}
                    dari {{ $pesanan->total() }} pesanan
                </span>
            </div>

            <div class="pagination-wrapper">
                {{ $pesanan->onEachSide(1)->appends(request()->query())->links('pagination::simple-bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
/* ========== BACKGROUND BATIK ========== */
.batik-bg {
    background: linear-gradient(135deg, #faf8f0 0%, #f5f0e6 50%, #fef9e6 100%);
    position: relative;
    min-height: 100vh;
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

.container.position-relative {
    position: relative;
    z-index: 2;
}

.hero-title {
    text-align: center;
    margin-bottom: 1rem;
}

.alert-custom {
    border-radius: 16px;
    padding: 14px 18px;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 12px;
    border: none;
    animation: slideIn 0.3s ease;
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

.empty-cart-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #f5f0e6, #e8e0cc);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.empty-cart-icon i {
    font-size: 3rem;
    color: #ffc107;
}

.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
}

.badge-custom {
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.badge-custom.bg-success {
    background: linear-gradient(135deg, #2e7d32, #1b5e20) !important;
}

.badge-custom.bg-warning {
    background: linear-gradient(135deg, #f57c00, #ef6c00) !important;
    color: white;
}

.badge-custom.bg-danger {
    background: linear-gradient(135deg, #d32f2f, #c62828) !important;
}

.badge-custom.bg-info {
    background: linear-gradient(135deg, #0288d1, #01579b) !important;
}

.badge-custom.bg-secondary {
    background: linear-gradient(135deg, #757575, #616161) !important;
}

.badge-wa {
    background: linear-gradient(135deg, #25D366, #128C7E);
    color: white;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.badge-terlambat {
    background: linear-gradient(135deg, #dc3545, #b71c1c);
    color: white;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% {
        opacity: 1;
    }

    50% {
        opacity: 0.7;
    }

    100% {
        opacity: 1;
    }
}

.btn-primary-custom {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border: none;
    border-radius: 50px;
    color: white;
    transition: all 0.3s ease;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    color: white;
}

/* ============================================ */
/* PAGINATION STYLING - TANPA PSEUDO-ELEMENT */
/* ============================================ */
.pagination-container {
    margin-top: 30px;
    margin-bottom: 20px;
}

.pagination-info-wrapper {
    margin-bottom: 20px;
}

.pagination-info-text {
    display: inline-block;
    padding: 8px 20px;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 500;
    color: #2e7d32;
    border-left: 3px solid #ffc107;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
}

.pagination-wrapper nav {
    display: inline-block;
}

.pagination-wrapper .pagination {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin: 0;
    padding: 0;
    justify-content: center;
}

.pagination-wrapper .page-item {
    list-style: none;
}

.pagination-wrapper .page-link {
    padding: 8px 16px;
    border-radius: 50px !important;
    border: 1px solid #dee2e6;
    color: #2e7d32;
    background: white;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.pagination-wrapper .page-link:hover {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #1b5e20;
    border-color: #ffc107;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border-color: #2e7d32;
    color: white;
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
}

.pagination-wrapper .page-item.disabled .page-link {
    color: #adb5bd;
    background: #f8f9fa;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.pagination-wrapper .page-item.disabled .page-link:hover {
    background: #f8f9fa;
    color: #adb5bd;
    transform: none;
    box-shadow: none;
}

/* Tombol Sebelumnya & Setelahnya */
.pagination-wrapper .page-item:first-child .page-link,
.pagination-wrapper .page-item:last-child .page-link {
    font-weight: 600;
    padding: 8px 20px;
    background: white;
    border-color: #ffc107;
}

.pagination-wrapper .page-item:first-child .page-link:hover,
.pagination-wrapper .page-item:last-child .page-link:hover {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #1b5e20;
    border-color: #ffc107;
}

/* Hilangkan semua pseudo-element */
.pagination-wrapper .page-link::before,
.pagination-wrapper .page-link::after {
    content: none !important;
}

/* Responsive */
@media (max-width: 768px) {
    .pagination-wrapper .pagination {
        gap: 5px;
    }

    .pagination-wrapper .page-link {
        padding: 6px 12px;
        font-size: 0.8rem;
    }

    .pagination-wrapper .page-item:first-child .page-link,
    .pagination-wrapper .page-item:last-child .page-link {
        padding: 6px 14px;
    }

    .pagination-info-text {
        font-size: 0.8rem;
        padding: 6px 16px;
    }
}

@media (max-width: 480px) {
    .pagination-wrapper .pagination {
        gap: 4px;
    }

    .pagination-wrapper .page-link {
        padding: 5px 8px;
        font-size: 0.7rem;
    }

    .pagination-wrapper .page-item:first-child .page-link,
    .pagination-wrapper .page-item:last-child .page-link {
        padding: 5px 10px;
    }

    .pagination-info-text {
        font-size: 0.7rem;
        padding: 5px 12px;
    }

    .pagination-wrapper .page-item:not(:first-child):not(:last-child):not(.active) {
        display: none;
    }
}

@media (max-width: 768px) {
    .hero-title h2 {
        font-size: 1.5rem;
    }

    .badge-custom,
    .badge-wa,
    .badge-terlambat {
        padding: 4px 10px;
        font-size: 0.7rem;
    }

    .card-header {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Script untuk mengganti teks Previous dan Next menjadi Sebelumnya dan Setelahnya
document.addEventListener('DOMContentLoaded', function() {
    // Cari tombol Previous (page-item pertama)
    var prevPageItem = document.querySelector('.pagination .page-item:first-child');
    var nextPageItem = document.querySelector('.pagination .page-item:last-child');

    if (prevPageItem) {
        var prevLink = prevPageItem.querySelector('.page-link');
        if (prevLink) {
            // Kosongkan isi link
            prevLink.innerHTML = '';
            // Set teks baru
            prevLink.textContent = '« Sebelumnya';
        }
    }

    if (nextPageItem) {
        var nextLink = nextPageItem.querySelector('.page-link');
        if (nextLink) {
            // Kosongkan isi link
            nextLink.innerHTML = '';
            // Set teks baru
            nextLink.textContent = 'Setelahnya »';
        }
    }
});
</script>
@endpush
@endsection