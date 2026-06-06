@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="batik-bg">
    <div class="container py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-title text-center text-md-start">
                    <h2 class="fw-bold mb-2"
                        style="background: linear-gradient(135deg, #1b5e20, #2e7d32, #ffc107); -webkit-background-clip: text; background-clip: text; color: transparent;">
                        <i class="bi bi-grid-3x3-gap-fill me-2" style="color: #ffc107;"></i>
                        Koleksi Produk Kami
                    </h2>
                    <p class="text-muted">Temukan berbagai pilihan jajanan tradisional berkualitas</p>
                </div>
            </div>
        </div>

        <!-- WhatsApp Custom Order Banner -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="whatsapp-banner rounded-4 overflow-hidden shadow">
                    <div class="row align-items-center p-3 p-md-4 g-3">
                        <div class="col-12 col-md-7">
                            <div class="d-flex align-items-center gap-3">
                                <div
                                    class="whatsapp-icon rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="bi bi-whatsapp text-white fs-3"></i>
                                </div>
                                <div>
                                    <h5 class="text-white fw-bold mb-1">Butuh Pesanan Custom?</h5>
                                    <p class="text-white-50 mb-0 small">Tidak menemukan produk yang dicari? Pesan
                                        melalui WhatsApp</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-5 text-md-end">
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
                                class="btn btn-light rounded-pill px-4 py-2 fw-semibold whatsapp-btn">
                                <i class="bi bi-whatsapp me-2"></i> Custom via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="filter-card rounded-4 shadow-sm p-3 p-md-4">
                    <form method="GET" action="{{ route('pelanggan.produk.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold mb-2 text-secondary">
                                    <i class="bi bi-search me-1"></i> Cari Produk
                                </label>
                                <div class="search-wrapper">
                                    <i class="bi bi-search search-icon"></i>
                                    <input type="text" name="cari" class="form-control search-input"
                                        placeholder="Nama produk..." value="{{ request('cari') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold mb-2 text-secondary">
                                    <i class="bi bi-filter me-1"></i> Kategori
                                </label>
                                <div class="category-select-wrapper">
                                    <select name="kategori" class="form-select category-select">
                                        <option value="">📋 Semua Kategori</option>
                                        @foreach($kategori as $kat)
                                        <option value="{{ $kat->id }}"
                                            {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                            {{ $kat->nama_kategori }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <button type="submit" class="btn btn-search w-100">
                                    <i class="bi bi-search me-1"></i> Cari
                                </button>
                            </div>
                            <div class="col-6 col-md-3">
                                <a href="{{ route('pelanggan.custom-snackbox.create') }}" class="btn btn-custom w-100">
                                    <i class="bi bi-box-seam me-1"></i> Custom Snackbox
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Produk Grid -->
        <div class="row g-4">
            @forelse($produk as $item)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="product-card rounded-4 shadow-sm overflow-hidden">
                    <div class="product-img-wrapper">
                        @if($item->gambar)
                        <img src="{{ asset('storage/produk/' . $item->gambar) }}" alt="{{ $item->nama_produk }}"
                            class="product-img">
                        @else
                        <div class="product-img-placeholder">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        @endif
                        <div class="product-badge">
                            <span>{{ $item->kategori->nama_kategori ?? 'Produk' }}</span>
                        </div>
                    </div>
                    <div class="product-body text-center p-3">
                        <h6 class="product-title fw-bold">{{ Str::limit($item->nama_produk, 30) }}</h6>
                        <p class="product-price fw-bold mb-2">{{ $item->harga_format }}</p>

                        @php $kategoriNama = $item->kategori->nama_kategori ?? ''; @endphp

                        @if($kategoriNama == 'Hantaran')
                        <button class="btn btn-add-cart w-100" onclick="tambahKeKeranjang({{ $item->id }})">
                            <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                        </button>
                        @else
                        <a href="{{ route('pelanggan.produk.show', $item->id) }}" class="btn btn-detail w-100">
                            <i class="bi bi-info-circle me-1"></i> Detail & Pesan
                        </a>
                        <small class="product-minimal text-muted d-block mt-2">
                            Minimal {{ $kategoriNama == 'Paketan' ? ($item->min_order ?? 1) : 50 }}
                            {{ $kategoriNama == 'Paketan' ? 'order' : 'pcs' }}
                        </small>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-state text-center py-5 rounded-4">
                    <i class="bi bi-emoji-frown fs-1" style="color: #ffc107; opacity: 0.5;"></i>
                    <p class="mt-3 mb-0">Tidak ada produk ditemukan.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="row mt-5">
            <div class="col-12">
                @if($produk->hasPages())
                <div
                    class="pagination-wrapper d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="pagination-info">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        <span>Menampilkan <strong>{{ $produk->firstItem() }}</strong> -
                            <strong>{{ $produk->lastItem() }}</strong> dari <strong>{{ $produk->total() }}</strong>
                            produk</span>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            @if($produk->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="bi bi-chevron-left"></i> Sebelumnya</span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $produk->previousPageUrl() }}"><i
                                        class="bi bi-chevron-left"></i> Sebelumnya</a>
                            </li>
                            @endif

                            @foreach($produk->getUrlRange(1, $produk->lastPage()) as $page => $url)
                            @if($page == $produk->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @elseif($page >= $produk->currentPage() - 2 && $page <= $produk->currentPage() + 2)
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                                @endforeach

                                @if($produk->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $produk->nextPageUrl() }}">Selanjutnya <i
                                            class="bi bi-chevron-right"></i></a>
                                </li>
                                @else
                                <li class="page-item disabled">
                                    <span class="page-link">Selanjutnya <i class="bi bi-chevron-right"></i></span>
                                </li>
                                @endif
                        </ul>
                    </nav>
                </div>
                @endif
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

/* ========== HEADER ========== */
.hero-title h2 {
    font-size: 1.8rem;
}

@media (min-width: 768px) {
    .hero-title h2 {
        font-size: 2.2rem;
    }
}

/* ========== WHATSAPP BANNER ========== */
.whatsapp-banner {
    background: linear-gradient(135deg, #075e54 0%, #128c7e 100%);
    position: relative;
    overflow: hidden;
}

.whatsapp-banner::before {
    content: "❦";
    position: absolute;
    bottom: -20px;
    right: -20px;
    font-size: 100px;
    opacity: 0.05;
    color: white;
    font-family: serif;
}

.whatsapp-icon {
    width: 55px;
    height: 55px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(4px);
}

.whatsapp-btn {
    transition: all 0.3s ease;
}

.whatsapp-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

/* ========== FILTER CARD ========== */
.filter-card {
    background: white;
    border: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.filter-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
}

.search-wrapper {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #ffc107;
    z-index: 1;
}

.search-input {
    padding-left: 40px;
    border: 1px solid #e0e0e0;
    border-radius: 50px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: #ffc107;
    background: white;
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
}

.category-select {
    border: 1px solid #e0e0e0;
    border-radius: 50px;
    background: #f8f9fa;
    padding: 10px 16px;
    cursor: pointer;
}

.category-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
}

.btn-search {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border: none;
    border-radius: 50px;
    color: white;
    font-weight: 600;
    padding: 10px;
    transition: all 0.3s ease;
}

.btn-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #1b5e20;
}

.btn-custom {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    border: none;
    border-radius: 50px;
    color: #1b5e20;
    font-weight: 600;
    padding: 10px;
    transition: all 0.3s ease;
}

.btn-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

/* ========== PRODUCT CARD ========== */
.product-card {
    background: white;
    transition: all 0.3s ease;
    border: none;
    position: relative;
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

.product-img-placeholder i {
    font-size: 3rem;
    color: #ffc107;
}

.product-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, #ffc107, #ffb300);
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 600;
    color: #1b5e20;
}

.product-title {
    font-size: 0.9rem;
    color: #1b5e20;
}

.product-price {
    font-size: 1rem;
    color: #2e7d32;
}

.btn-add-cart {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border: none;
    border-radius: 50px;
    color: white;
    font-size: 0.8rem;
    padding: 8px;
    transition: all 0.3s ease;
}

.btn-add-cart:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(46, 125, 50, 0.3);
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #1b5e20;
}

.btn-detail {
    background: transparent;
    border: 1.5px solid #2e7d32;
    border-radius: 50px;
    color: #2e7d32;
    font-size: 0.8rem;
    padding: 8px;
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

/* ========== PAGINATION ========== */
.pagination-wrapper {
    background: white;
    padding: 16px 20px;
    border-radius: 60px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.pagination-info {
    background: linear-gradient(135deg, #f5f0e6, #faf8f0);
    padding: 8px 18px;
    border-radius: 50px;
    font-size: 0.85rem;
    color: #2e7d32;
}

.page-link {
    border-radius: 50px !important;
    margin: 0 3px;
    border: 1px solid #dee2e6;
    color: #2e7d32;
    transition: all 0.3s ease;
}

.page-link:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #1b5e20;
    transform: translateY(-2px);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border-color: #2e7d32;
}

.page-item.disabled .page-link {
    color: #adb5bd;
    background-color: #f8f9fa;
}

/* ========== EMPTY STATE ========== */
.empty-state {
    background: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.empty-state i {
    font-size: 4rem;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 576px) {
    .product-title {
        font-size: 0.8rem;
    }

    .product-price {
        font-size: 0.9rem;
    }

    .btn-add-cart,
    .btn-detail {
        font-size: 0.7rem;
        padding: 6px;
    }

    .pagination-wrapper {
        padding: 12px 16px;
        border-radius: 30px;
    }

    .pagination-info {
        font-size: 0.7rem;
        padding: 5px 12px;
    }

    .page-link {
        padding: 0.4rem 0.7rem !important;
        font-size: 0.75rem !important;
    }

    .whatsapp-icon {
        width: 45px;
        height: 45px;
    }

    .whatsapp-icon i {
        font-size: 1.3rem;
    }
}
</style>
@endsection