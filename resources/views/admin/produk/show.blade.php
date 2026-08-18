@extends('layouts.admin')

@section('title', 'Detail Produk - ' . $produk->nama_produk)

@section('content')
<div class="detail-container">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold mb-1">
                <i class="bi bi-eye me-2" style="color: #ffc107;"></i>
                Detail Produk
            </h2>
            <p class="text-muted mb-0">{{ $produk->nama_produk }}</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card-detail card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
            <div class="d-flex align-items-center gap-2">
                <div class="header-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h5 class="mb-0 fw-bold" style="color: #1b5e20;">Informasi Produk</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="product-image-container">
                        @if($produk->gambar)
                        <div class="image-frame">
                            <img src="{{ $produk->gambar_url }}" class="product-detail-image"
                                alt="{{ $produk->nama_produk }}">
                        </div>
                        @else
                        <div class="no-image-container">
                            <i class="bi bi-image"></i>
                            <p class="text-muted mt-2 mb-0">Tidak ada gambar</p>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-tag text-success"></i> Nama Produk
                            </div>
                            <div class="info-value fw-bold text-success">{{ $produk->nama_produk }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-folder text-warning"></i> Kategori
                            </div>
                            <div class="info-value">
                                <span class="badge-kategori">{{ $produk->kategori->nama_kategori ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-currency-dollar text-primary"></i> Harga
                            </div>
                            <div class="info-value">
                                <span class="badge-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-calculator text-info"></i> Minimal Order
                            </div>
                            <div class="info-value">
                                <span class="badge-min-order">{{ number_format($produk->min_order) }}
                                    {{ $produk->kategori->nama_kategori == 'Paketan' ? 'order' : 'pcs' }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-file-text"></i> Deskripsi
                            </div>
                            <div class="info-value description-text">{{ $produk->deskripsi ?: '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-calendar-plus"></i> Dibuat
                            </div>
                            <div class="info-value">
                                {{ $produk->created_at ? \Carbon\Carbon::parse($produk->created_at)->locale('id')->translatedFormat('d F Y H:i') : '-' }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-calendar-check"></i> Terakhir Update
                            </div>
                            <div class="info-value">
                                {{ $produk->updated_at ? \Carbon\Carbon::parse($produk->updated_at)->locale('id')->translatedFormat('d F Y H:i') : '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-buttons mt-4 pt-3 border-top">
                <a href="{{ route('admin.produk.edit', $produk->id) }}" class="btn btn-warning rounded-pill px-4">
                    <i class="bi bi-pencil me-2"></i> Edit Produk
                </a>
                <button class="btn btn-danger rounded-pill px-4"
                    onclick="openDeleteModal({{ $produk->id }}, '{{ $produk->nama_produk }}')">
                    <i class="bi bi-trash me-2"></i> Hapus Produk
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS PRODUK -->
<div id="deleteModal"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 100000; margin: 0; padding: 0;">
    <div
        style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 20px; box-sizing: border-box;">
        <div
            style="background: white; border-radius: 28px; max-width: 400px; width: 100%; margin: 0 auto; box-shadow: 0 30px 60px rgba(0,0,0,0.4); overflow: hidden;">
            <div
                style="background: linear-gradient(135deg, #dc3545, #c62828); color: white; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; font-size: 1rem; font-weight: 600;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" onclick="closeDeleteModal()"
                    style="background: rgba(255,255,255,0.2); border: none; color: white; width: 30px; height: 30px; border-radius: 50%; font-size: 18px; cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 24px; text-align: center;">
                <i class="bi bi-question-circle" style="font-size: 60px; color: #dc3545;"></i>
                <h5 class="mt-3">Apakah Anda yakin?</h5>
                <p class="text-muted" id="deleteProductName">Produk akan dihapus secara permanen.</p>
                <div class="d-flex gap-3 justify-content-center mt-4">
                    <button type="button" onclick="closeDeleteModal()"
                        class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="button" id="confirmDeleteBtn" class="btn btn-danger rounded-pill px-4">
                        <i class="bi bi-trash me-1"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS untuk Custom Toast Notification -->
<style>
/* Custom Toast */
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

.custom-toast .toast-icon i {
    font-size: 28px;
}

.custom-toast.toast-success .toast-icon i {
    color: #2e7d32;
}

.custom-toast.toast-error .toast-icon i {
    color: #dc3545;
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
</style>

@push('styles')
<style>
.detail-container {
    animation: fadeInUp 0.5s ease;
}

.page-title {
    background: linear-gradient(135deg, #1b5e20, #2e7d32, #ffc107);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-size: 1.8rem;
}

.card-detail {
    overflow: hidden;
}

.header-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2e7d32;
}

/* ================================================================
   PERBAIKAN GAMBAR PRODUK - SAMA SEPERTI HALAMAN PELANGGAN
   ================================================================ */

/* --- GAMBAR UTAMA PRODUK --- */
.product-image-container {
    background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
    border-radius: 20px;
    padding: 20px;
    text-align: center;
    width: 100%;
}

.image-frame {
    overflow: hidden;
    border-radius: 16px;
    background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
    width: 100%;
    height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-detail-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    /* Semua gambar SERAGAM - mengisi area dengan proporsi yang sama */
    border-radius: 12px;
    transition: transform 0.5s ease;
}

.product-detail-image:hover {
    transform: scale(1.05);
}

/* Alternatif jika ingin gambar utuh tanpa dipotong */
/* .product-detail-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 10px;
} */

.no-image-container {
    padding: 40px 20px;
    background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
    border-radius: 16px;
}

.no-image-container i {
    font-size: 4rem;
    color: #2e7d32;
}

/* ================================================================
   AKHIR PERBAIKAN GAMBAR
   ================================================================ */

/* Info Grid */
.info-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-row {
    display: flex;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 12px;
}

.info-label {
    width: 150px;
    font-weight: 600;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-value {
    flex: 1;
    color: #333;
}

.description-text {
    line-height: 1.6;
    text-align: justify;
}

/* Badges */
.badge-kategori {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #2e7d32;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
}

.badge-price {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #2e7d32;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 700;
    display: inline-block;
}

.badge-min-order {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    color: #e65100;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    border: none;
    transition: all 0.3s ease;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    border: none;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* --- RESPONSIVE UNTUK GAMBAR --- */
@media (max-width: 992px) {
    .image-frame {
        height: 240px;
    }
}

@media (max-width: 768px) {
    .page-title {
        font-size: 1.3rem;
    }

    .info-row {
        flex-direction: column;
        gap: 5px;
    }

    .info-label {
        width: 100%;
    }

    .action-buttons {
        flex-direction: column;
    }

    .action-buttons .btn {
        width: 100%;
    }

    .image-frame {
        height: 200px;
        padding: 10px;
    }
}

@media (max-width: 576px) {
    .image-frame {
        height: 180px;
        padding: 8px;
    }

    .product-image-container {
        padding: 12px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// ============================================================
// MODAL KONFIRMASI HAPUS PRODUK
// ============================================================
var productToDelete = null;
var productNameToDelete = '';

function openDeleteModal(id, nama) {
    productToDelete = id;
    productNameToDelete = nama;
    var modal = document.getElementById('deleteModal');
    var productNameSpan = document.getElementById('deleteProductName');
    if (productNameSpan) {
        productNameSpan.innerText = 'Apakah Anda yakin ingin menghapus produk "' + nama + '"?';
    }
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeDeleteModal() {
    var modal = document.getElementById('deleteModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    productToDelete = null;
}

// Tutup modal kalau klik di luar area modal
window.onclick = function(event) {
    var modal = document.getElementById('deleteModal');
    if (event.target == modal) {
        closeDeleteModal();
    }
}

// Event untuk tombol konfirmasi hapus
document.getElementById('confirmDeleteBtn')?.addEventListener('click', function() {
    if (!productToDelete) {
        showCustomToast('error', 'Gagal!', 'Produk tidak ditemukan untuk dihapus');
        return;
    }

    const deleteUrl = '{{ url("admin/produk") }}/' + encodeURIComponent(productToDelete);

    fetch(deleteUrl, {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Server returned status ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            closeDeleteModal();
            if (data.success) {
                showCustomToast('success', 'Berhasil!', data.message || 'Produk berhasil dihapus');
                setTimeout(() => {
                    window.location.href = '{{ route("admin.produk.index") }}';
                }, 1500);
            } else {
                showCustomToast('error', 'Gagal!', data.message || 'Gagal menghapus produk');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            closeDeleteModal();
            showCustomToast('error', 'Kesalahan Server', 'Terjadi kesalahan pada server');
        });
});

// ============================================================
// CUSTOM TOAST NOTIFICATION
// ============================================================
function showCustomToast(type, title, message) {
    var existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(function(toast) {
        toast.remove();
    });

    var toastDiv = document.createElement('div');
    toastDiv.className = 'custom-toast toast-' + type;

    var icon = document.createElement('div');
    icon.className = 'toast-icon';
    if (type === 'success') {
        icon.innerHTML = '<i class="fas fa-check-circle"></i>';
    } else {
        icon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
    }

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
</script>
@endpush
@endsection