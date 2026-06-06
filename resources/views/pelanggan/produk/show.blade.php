@extends('layouts.app')

@section('title', $produk->nama_produk)

@section('content')
<div class="container py-4">
    <!-- Tombol Kembali -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('pelanggan.produk.index') }}"
                class="btn btn-outline-success rounded-pill px-4 py-2 back-btn">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Produk
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Gambar Produk -->
        <div class="col-12 col-lg-5">
            <div class="product-image-card card border-0 rounded-4 shadow-sm overflow-hidden">
                <div class="card-body p-4 text-center">
                    @if($produk->gambar)
                    <div class="image-frame">
                        <img src="{{ asset('storage/produk/' . $produk->gambar) }}" class="img-fluid product-main-img"
                            alt="{{ $produk->nama_produk }}">
                    </div>
                    @else
                    <div class="no-image-wrapper py-5">
                        <div class="no-image-icon mx-auto mb-3">
                            <i class="bi bi-egg-fried"></i>
                        </div>
                        <p class="text-muted mb-0">Belum ada gambar</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Info Produk -->
        <div class="col-12 col-lg-7">
            <div class="product-info-card card border-0 rounded-4 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <!-- Badge Kategori -->
                    <div class="mb-3">
                        <span class="category-badge">
                            <i class="bi bi-tag me-1"></i> {{ $produk->kategori->nama_kategori ?? 'Produk Nusantara' }}
                        </span>
                    </div>

                    <!-- Nama Produk -->
                    <h1 class="product-title fw-bold mb-3">{{ $produk->nama_produk }}</h1>

                    <!-- Harga -->
                    <div class="price-section mb-4">
                        <span class="price-label">Harga</span>
                        <h2 class="price-value fw-bold mb-0">{{ $produk->harga_format }}</h2>
                    </div>

                    <!-- Deskripsi -->
                    @if(isset($produk->deskripsi) && trim($produk->deskripsi) != '')
                    <div class="description-box mb-4">
                        <div class="description-header">
                            <i class="bi bi-file-text me-2"></i> Deskripsi Produk
                        </div>
                        <div class="description-content">
                            {{ nl2br(e($produk->deskripsi)) }}
                        </div>
                    </div>
                    @endif

                    @php
                    $kategoriNama = $produk->kategori->nama_kategori ?? '';
                    if ($kategoriNama == 'Hantaran') {
                    $minOrder = 1;
                    $satuan = 'pcs';
                    $showWarning = false;
                    } elseif ($kategoriNama == 'Paketan') {
                    $minOrder = $produk->min_order ?? 1;
                    $satuan = 'order';
                    $showWarning = true;
                    } elseif ($kategoriNama == 'Jajanan Basah') {
                    $minOrder = 50;
                    $satuan = 'pcs';
                    $showWarning = true;
                    } else {
                    $minOrder = 50;
                    $satuan = 'pcs';
                    $showWarning = true;
                    }
                    @endphp

                    <!-- Minimal Order Alert -->
                    @if($showWarning)
                    <div class="min-order-alert mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Minimal pesanan: <strong>{{ number_format($minOrder) }}</strong> {{ $satuan }}
                    </div>
                    @endif

                    <!-- Form Pemesanan -->
                    <form id="tambahKeranjangForm" class="order-form">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="bi bi-calculator me-1"></i> Jumlah ({{ $satuan }})
                                </label>
                                <div class="quantity-control">
                                    <button type="button" class="quantity-btn minus-btn" id="btnMinus">
                                        <i class="bi bi-dash-lg"></i>
                                    </button>
                                    <input type="number" name="jumlah" id="jumlah" class="quantity-input"
                                        value="{{ $minOrder }}" min="1" style="width: 70px; text-align: center;"
                                        data-minorder="{{ $minOrder }}">
                                    <button type="button" class="quantity-btn plus-btn" id="btnPlus">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 col-md-8">
                                <button type="button" class="add-to-cart-btn w-100" id="tambahKeKeranjangBtn">
                                    <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Produk Terkait -->
    @if($produkTerkait && $produkTerkait->count() > 0)
    <div class="related-products-section mt-5 pt-3">
        <div class="section-header d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h3 class="section-title mb-0">
                <i class="bi bi-stars me-2"></i> Anda Mungkin Juga Suka
            </h3>
            <a href="{{ route('pelanggan.produk.index') }}" class="view-all-link">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3 g-md-4">
            @foreach($produkTerkait as $item)
            <div class="col-6 col-md-3">
                <div class="related-card" onclick="window.location='{{ route('pelanggan.produk.show', $item->id) }}'">
                    <div class="related-card-img">
                        @if($item->gambar)
                        <img src="{{ asset('storage/produk/' . $item->gambar) }}" alt="{{ $item->nama_produk }}">
                        @else
                        <i class="bi bi-egg-fried"></i>
                        @endif
                    </div>
                    <h6 class="related-card-title">{{ Str::limit($item->nama_produk, 25) }}</h6>
                    <p class="related-card-price">{{ $item->harga_format }}</p>
                    <button class="related-card-cart"
                        onclick="event.stopPropagation(); tambahKeKeranjangLangsung({{ $item->id }})">
                        <i class="bi bi-cart-plus me-1"></i> Keranjang
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
/* ========== CUSTOM TOAST NOTIFICATION ========== */
.custom-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    min-width: 320px;
    max-width: 450px;
    background: white;
    border-radius: 16px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    z-index: 100001;
    transform: translateX(120%);
    transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    border-left: 5px solid;
    cursor: pointer;
}

.custom-toast.show {
    transform: translateX(0);
}

.custom-toast.toast-success {
    border-left-color: #2e7d32;
    background: linear-gradient(135deg, #ffffff, #f0fdf4);
}

.custom-toast.toast-error {
    border-left-color: #dc3545;
    background: linear-gradient(135deg, #ffffff, #fef2f2);
}

.custom-toast.toast-warning {
    border-left-color: #ffc107;
    background: linear-gradient(135deg, #ffffff, #fffbeb);
}

.custom-toast .toast-icon {
    flex-shrink: 0;
}

.custom-toast .toast-icon i {
    font-size: 28px;
}

.custom-toast.toast-success .toast-icon i {
    color: #2e7d32;
}

.custom-toast.toast-error .toast-icon i {
    color: #dc3545;
}

.custom-toast.toast-warning .toast-icon i {
    color: #ffc107;
}

.custom-toast .toast-content {
    flex: 1;
}

.custom-toast .toast-title {
    font-weight: 700;
    font-size: 0.9rem;
    color: #1a1a1a;
    margin-bottom: 4px;
}

.custom-toast .toast-message {
    font-size: 0.8rem;
    color: #666;
}

.custom-toast .toast-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    opacity: 0.5;
    transition: opacity 0.2s;
    color: #333;
    padding: 0;
    line-height: 1;
}

.custom-toast .toast-close:hover {
    opacity: 1;
}

@media (max-width: 480px) {
    .custom-toast {
        left: 20px;
        right: 20px;
        min-width: auto;
        max-width: none;
    }
}

.quantity-input {
    -moz-appearance: textfield;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
// ========== VARIABEL ==========
var minOrder = parseInt("{{ $minOrder }}") || 1;
var jumlahInput = document.getElementById('jumlah');
var btnMinus = document.getElementById('btnMinus');
var btnPlus = document.getElementById('btnPlus');
var tambahBtn = document.getElementById('tambahKeKeranjangBtn');

// ========== FUNGSI TOAST ==========
function showCustomToast(type, title, message) {
    var existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(function(toast) {
        toast.remove();
    });
    var toastDiv = document.createElement('div');
    toastDiv.className = 'custom-toast toast-' + type;
    var icon = document.createElement('div');
    icon.className = 'toast-icon';
    if (type === 'success') icon.innerHTML = '<i class="fas fa-check-circle"></i>';
    else if (type === 'error') icon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
    else icon.innerHTML = '<i class="fas fa-info-circle"></i>';
    var content = document.createElement('div');
    content.className = 'toast-content';
    content.innerHTML = '<div class="toast-title">' + title + '</div><div class="toast-message">' + message + '</div>';
    var closeBtn = document.createElement('button');
    closeBtn.className = 'toast-close';
    closeBtn.innerHTML = '&times;';
    closeBtn.onclick = function() {
        toastDiv.remove();
    };
    toastDiv.appendChild(icon);
    toastDiv.appendChild(content);
    toastDiv.appendChild(closeBtn);
    document.body.appendChild(toastDiv);
    setTimeout(function() {
        toastDiv.classList.add('show');
    }, 10);
    setTimeout(function() {
        if (toastDiv.parentNode) {
            toastDiv.classList.remove('show');
            setTimeout(function() {
                if (toastDiv.parentNode) toastDiv.remove();
            }, 300);
        }
    }, 3000);
    toastDiv.onclick = function(e) {
        if (e.target !== closeBtn) {
            toastDiv.classList.remove('show');
            setTimeout(function() {
                if (toastDiv.parentNode) toastDiv.remove();
            }, 300);
        }
    };
}

// ========== EVENT TOMBOL MINUS & PLUS ==========
btnMinus.addEventListener('click', function(e) {
    e.preventDefault();
    let currentVal = parseInt(jumlahInput.value) || minOrder;
    let newVal = currentVal - 1;
    if (newVal >= minOrder) {
        jumlahInput.value = newVal;
    } else {
        showCustomToast('warning', 'Perhatian', 'Jumlah tidak boleh kurang dari ' + minOrder);
        jumlahInput.value = minOrder;
    }
});

btnPlus.addEventListener('click', function(e) {
    e.preventDefault();
    let currentVal = parseInt(jumlahInput.value) || minOrder;
    let newVal = currentVal + 1;
    if (newVal <= 999) {
        jumlahInput.value = newVal;
    } else {
        showCustomToast('warning', 'Perhatian', 'Maksimal pesanan adalah 999');
    }
});

// ========== INPUT MANUAL (BLUR) ==========
jumlahInput.addEventListener('blur', function() {
    let rawVal = parseInt(this.value);
    if (isNaN(rawVal) || rawVal < 1) rawVal = 1;
    if (rawVal < minOrder) {
        showCustomToast('warning', 'Perhatian', 'Jumlah tidak boleh kurang dari ' + minOrder);
        this.value = minOrder;
    } else if (rawVal > 999) {
        this.value = 999;
    } else {
        this.value = rawVal;
    }
});

// ========== FUNGSI TAMBAH KE KERANJANG (TANPA REDIRECT JIKA KURANG) ==========
function tambahKeKeranjang(event) {
    if (event) event.preventDefault();
    let produkId = "{{ $produk->id }}";
    let jumlah = parseInt(jumlahInput.value);
    if (isNaN(jumlah) || jumlah < 1) jumlah = 1;

    // Validasi minimal order - JIKA KURANG, HANYA TAMPILKAN TOAST DAN STOP
    if (jumlah < minOrder) {
        showCustomToast('warning', 'Perhatian', 'Minimal pesanan adalah ' + minOrder + ' {{ $satuan }}');
        jumlahInput.value = minOrder; // reset tampilan
        return false; // tidak lanjut, tidak redirect
    }
    if (jumlah > 999) {
        showCustomToast('warning', 'Perhatian', 'Maksimal pesanan adalah 999');
        jumlahInput.value = 999;
        return false;
    }

    let csrfToken = document.querySelector('input[name="_token"]').value;
    fetch('{{ route("pelanggan.keranjang.tambah-produk") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                produk_id: produkId,
                jumlah: jumlah
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showCustomToast('success', 'Berhasil!', data.message || 'Produk ditambahkan ke keranjang!');
                // Redirect hanya jika berhasil
                setTimeout(() => {
                    window.location.href = '{{ route("pelanggan.keranjang.index") }}';
                }, 1000);
            } else {
                showCustomToast('error', 'Gagal!', data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showCustomToast('error', 'Kesalahan Server', error.message || 'Terjadi kesalahan pada server');
        });
}

// Pasang event listener tombol
tambahBtn.addEventListener('click', tambahKeKeranjang);

// Event Enter pada input juga akan memanggil fungsi yang sama
jumlahInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        tambahKeKeranjang(e);
    }
});

// ========== TAMBAH LANGSUNG UNTUK PRODUK TERKAIT ==========
function tambahKeKeranjangLangsung(produkId) {
    let csrfToken = document.querySelector('input[name="_token"]').value;
    let produk = @json($produkTerkait);
    let selectedProduk = produk.find(p => p.id == produkId);
    let jumlahMin = selectedProduk ? (selectedProduk.min_order || 50) : 50;

    fetch('{{ route("pelanggan.keranjang.tambah-produk") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                produk_id: produkId,
                jumlah: jumlahMin
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showCustomToast('success', 'Berhasil!', data.message || 'Produk ditambahkan ke keranjang!');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showCustomToast('error', 'Gagal!', data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showCustomToast('error', 'Kesalahan Server', error.message || 'Terjadi kesalahan pada server');
        });
}
</script>
@endpush

<style>
/* ========== SEMUA STYLE YANG SUDAH ADA (tetap dipertahankan) ========== */
:root {
    --green-dark: #1b5e20;
    --green-primary: #2e7d32;
    --green-light: #4caf50;
    --green-soft: #e8f5e9;
    --gold: #ffc107;
    --gold-dark: #ff8f00;
    --cream: #fff8e1;
    --cream-dark: #ffecb3;
    --gray-bg: #f8f9fa;
}

.product-image-card,
.product-info-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: white;
}

.product-image-card:hover,
.product-info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 35px rgba(0, 0, 0, 0.1) !important;
}

.back-btn {
    transition: all 0.3s ease;
    border: 2px solid var(--green-primary);
    color: var(--green-primary);
    font-weight: 500;
    background: white;
}

.back-btn:hover {
    background: linear-gradient(135deg, var(--green-primary), var(--green-dark));
    color: white;
    transform: translateX(-5px);
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);
}

.image-frame {
    overflow: hidden;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--gray-bg), var(--green-soft));
    padding: 20px;
}

.product-main-img {
    transition: transform 0.5s ease;
    max-height: 280px;
    width: auto;
    margin: 0 auto;
}

.product-main-img:hover {
    transform: scale(1.05);
}

.no-image-wrapper {
    background: linear-gradient(135deg, var(--gray-bg), var(--green-soft));
    border-radius: 20px;
    padding: 40px;
}

.no-image-icon i {
    font-size: 5rem;
    color: var(--green-primary);
}

.category-badge {
    background: linear-gradient(135deg, var(--green-soft), #c8e6c9);
    color: var(--green-dark);
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
}

.product-title {
    font-size: 1.8rem;
    color: var(--green-dark);
}

@media (min-width: 768px) {
    .product-title {
        font-size: 2.2rem;
    }
}

.price-section {
    background: linear-gradient(135deg, var(--gray-bg), white);
    padding: 12px 16px;
    border-radius: 16px;
    border-left: 4px solid var(--gold);
}

.price-label {
    font-size: 0.8rem;
    color: #6c757d;
    display: block;
}

.price-value {
    font-size: 1.8rem;
    color: var(--green-primary);
}

.description-box {
    background: linear-gradient(135deg, var(--gray-bg), #f1f8e9);
    border-radius: 16px;
    overflow: hidden;
}

.description-header {
    background: linear-gradient(135deg, var(--green-soft), #c8e6c9);
    padding: 12px 16px;
    font-weight: 600;
    color: var(--green-dark);
}

.description-content {
    padding: 16px;
    color: #4a5568;
    line-height: 1.6;
}

.min-order-alert {
    background: linear-gradient(135deg, var(--cream), var(--cream-dark));
    padding: 12px 16px;
    border-radius: 12px;
    color: var(--gold-dark);
    font-weight: 500;
    border-left: 4px solid var(--gold);
}

.quantity-control {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--gray-bg);
    border-radius: 50px;
    padding: 4px;
    width: fit-content;
}

.quantity-btn {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: white;
    color: var(--green-primary);
    font-size: 1.2rem;
    transition: all 0.2s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.quantity-btn:hover {
    background: linear-gradient(135deg, var(--green-primary), var(--green-dark));
    color: white;
    transform: scale(1.05);
}

.quantity-input {
    width: 70px;
    text-align: center;
    border: none;
    background: transparent;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--green-dark);
}

.quantity-input:focus {
    outline: none;
}

.add-to-cart-btn {
    background: linear-gradient(135deg, var(--green-primary) 0%, var(--green-dark) 100%);
    border: none;
    border-radius: 50px;
    padding: 14px 20px;
    color: white;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(46, 125, 50, 0.2);
}

.add-to-cart-btn:hover {
    transform: translateY(-3px);
    background: linear-gradient(135deg, var(--green-light), var(--green-primary));
    box-shadow: 0 8px 20px rgba(46, 125, 50, 0.3);
}

.related-products-section {
    background: linear-gradient(135deg, var(--gray-bg), white);
    border-radius: 24px;
    padding: 28px 24px;
    position: relative;
    overflow: hidden;
}

.related-products-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--gold), var(--green-primary), var(--gold));
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--green-dark), var(--green-primary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.view-all-link {
    color: var(--green-primary);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s;
    padding: 6px 16px;
    border-radius: 30px;
    background: var(--green-soft);
}

.view-all-link:hover {
    background: var(--green-primary);
    color: white;
    transform: translateX(3px);
}

.related-card {
    background: white;
    border-radius: 20px;
    padding: 20px 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
}

.related-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--gold), var(--green-primary));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.related-card:hover::before {
    transform: scaleX(1);
}

.related-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(46, 125, 50, 0.15);
}

.related-card-img {
    width: 80px;
    height: 80px;
    margin: 0 auto 12px;
    background: linear-gradient(135deg, var(--green-soft), #c8e6c9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.related-card:hover .related-card-img {
    transform: scale(1.08);
}

.related-card-img img {
    width: 55px;
    height: 55px;
    object-fit: cover;
    border-radius: 12px;
}

.related-card-img i {
    font-size: 2rem;
    color: var(--green-primary);
}

.related-card-title {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--green-dark);
    margin-bottom: 6px;
}

.related-card-price {
    font-weight: 700;
    color: var(--green-primary);
    margin-bottom: 12px;
    font-size: 0.9rem;
}

.related-card-cart {
    background: transparent;
    border: 1.5px solid var(--green-primary);
    border-radius: 30px;
    padding: 6px 12px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--green-primary);
    transition: all 0.2s ease;
    cursor: pointer;
    width: 100%;
}

.related-card-cart:hover {
    background: linear-gradient(135deg, var(--green-primary), var(--green-dark));
    color: white;
    border-color: transparent;
    transform: scale(1.02);
}

@media (max-width: 768px) {
    .product-title {
        font-size: 1.5rem;
    }

    .price-value {
        font-size: 1.4rem;
    }

    .quantity-control {
        gap: 8px;
    }

    .quantity-btn {
        width: 35px;
        height: 35px;
    }

    .quantity-input {
        width: 55px;
    }

    .add-to-cart-btn {
        padding: 12px 16px;
    }

    .related-card-img {
        width: 65px;
        height: 65px;
    }

    .related-card-img img {
        width: 45px;
        height: 45px;
    }

    .related-card-title {
        font-size: 0.75rem;
    }

    .section-title {
        font-size: 1.2rem;
    }

    .related-products-section {
        padding: 20px 16px;
    }
}
</style>
@endsection