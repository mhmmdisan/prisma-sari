@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')
<div class="produk-container">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold mb-1">
                <i class="bi bi-box-seam me-2" style="color: #ffc107;"></i>
                Kelola Produk
            </h2>
            <p class="text-muted mb-0">Manajemen data produk catering</p>
        </div>
        <a href="{{ route('admin.produk.create') }}" class="btn btn-primary-custom rounded-pill px-4 py-2 mt-2 mt-sm-0">
            <i class="bi bi-plus-circle me-2"></i> Tambah Produk
        </a>
    </div>

    <!-- Filter dan Pencarian -->
    <div class="card-filter card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="bi bi-tag me-1"></i> Kategori
                    </label>
                    <select name="kategori" class="form-select rounded-3 filter-kategori">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="bi bi-search me-1"></i> Cari Produk
                    </label>
                    <input type="text" name="cari" class="form-control rounded-3 filter-cari"
                        placeholder="Nama produk..." value="{{ request('cari') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-filter w-100 rounded-pill">
                        <i class="bi bi-search me-2"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Produk -->
    <div class="card-table card border-0 rounded-4 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 8%">Gambar</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Min Order</th>
                            <th style="width: 15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produk as $item)
                        <tr class="product-row" data-id="{{ $item->id }}">
                            <td>
                                @if($item->gambar)
                                <div class="product-image-wrapper">
                                    <img src="{{ $item->gambar_url }}" class="product-thumb"
                                        alt="{{ $item->nama_produk }}">
                                </div>
                                @else
                                <div class="product-image-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="product-name">{{ $item->nama_produk }}</div>
                                @if($item->deskripsi)
                                <div class="product-desc">{{ Str::limit($item->deskripsi, 60) }}</div>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge-kategori">{{ $item->kategori->nama_kategori ?? '-' }}</span>
                            </td>
                            <td class="fw-bold text-success text-center">Rp
                                {{ number_format($item->harga, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="badge-min-order">{{ number_format($item->min_order) }}
                                    {{ $item->kategori->nama_kategori == 'Paketan' ? 'order' : 'pcs' }}</span>
                            </td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.produk.show', $item->id) }}" class="btn-action view"
                                        title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.produk.edit', $item->id) }}" class="btn-action edit"
                                        title="Edit Produk">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn-action delete"
                                        onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->nama_produk) }}')"
                                        title="Hapus Produk">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="mt-2 text-muted mb-0">Belum ada produk</p>
                                <a href="{{ route('admin.produk.create') }}"
                                    class="btn btn-sm btn-primary-custom mt-3 rounded-pill">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Produk Pertama
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="card-footer bg-white border-0 py-3">
            <div class="pagination-wrapper">
                {{ $produk->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS -->
<div id="deleteModal"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 100000; margin: 0; padding: 0;">
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

@push('styles')
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

/* ============================================ */
/* PAGINATION STYLING */
/* ============================================ */
.pagination-wrapper {
    display: flex;
    justify-content: center;
}

.pagination-wrapper nav .hidden,
.pagination-wrapper nav .pagination-info,
.pagination-wrapper nav p.small,
.pagination-wrapper nav .text-muted {
    display: none !important;
}

.pagination {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin: 0;
    padding: 0;
}

.pagination .page-item {
    list-style: none;
}

.pagination .page-link {
    padding: 8px 16px;
    border-radius: 50px !important;
    border: 1px solid #dee2e6;
    color: #2e7d32;
    background: white;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
}

.pagination .page-link:hover {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #1b5e20;
    border-color: #ffc107;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border-color: #2e7d32;
    color: white;
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
}

.pagination .page-item.disabled .page-link {
    color: #adb5bd;
    background: #f8f9fa;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Ganti teks Previous menjadi Sebelumnya */
.pagination .page-item:first-child .page-link span:first-child {
    display: none;
}

.pagination .page-item:first-child .page-link::before {
    content: "« Sebelumnya";
    display: inline-block;
}

/* Ganti teks Next menjadi Selanjutnya */
.pagination .page-item:last-child .page-link span:first-child {
    display: none;
}

.pagination .page-item:last-child .page-link::after {
    content: "Selanjutnya »";
    display: inline-block;
}

.pagination .page-item:first-child .page-link span,
.pagination .page-item:last-child .page-link span {
    display: none !important;
}

/* Responsive Pagination */
@media (max-width: 768px) {
    .pagination {
        gap: 5px;
    }

    .pagination .page-link {
        padding: 6px 12px;
        font-size: 0.8rem;
    }

    .pagination .page-item:first-child .page-link::before {
        content: "« Sebelumnya";
        font-size: 0.75rem;
    }

    .pagination .page-item:last-child .page-link::after {
        content: "Selanjutnya »";
        font-size: 0.75rem;
    }
}

@media (max-width: 480px) {
    .pagination {
        gap: 4px;
    }

    .pagination .page-link {
        padding: 5px 10px;
        font-size: 0.7rem;
    }

    .pagination .page-item:first-child .page-link::before {
        content: "« Sebelumnya";
        font-size: 0.7rem;
    }

    .pagination .page-item:last-child .page-link::after {
        content: "Selanjutnya »";
        font-size: 0.7rem;
    }

    .pagination .page-item:not(:first-child):not(:last-child):not(.active) {
        display: none;
    }
}

/* ============================================ */
/* FILTER DROPDOWN & INPUT STYLING */
/* ============================================ */
.filter-kategori {
    border: 1.5px solid #e5e7eb !important;
    border-radius: 12px !important;
    padding: 10px 14px !important;
    font-size: 0.9rem !important;
    transition: all 0.3s ease;
    background-color: white;
    cursor: pointer;
}

.filter-kategori:hover {
    border-color: #ffc107 !important;
    background-color: #fffbeb !important;
}

.filter-kategori:focus {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
    outline: none;
}

.filter-cari {
    border: 1.5px solid #e5e7eb !important;
    border-radius: 12px !important;
    padding: 10px 14px !important;
    font-size: 0.9rem !important;
    transition: all 0.3s ease;
}

.filter-cari:hover {
    border-color: #ffc107 !important;
    background-color: #fffbeb !important;
}

.filter-cari:focus {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
    outline: none;
}

/* ============================================ */
/* MAIN STYLING */
/* ============================================ */
.produk-container {
    animation: fadeInUp 0.5s ease;
}

.page-title {
    background: linear-gradient(135deg, #1b5e20, #2e7d32, #ffc107);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-size: 1.8rem;
}

.card-filter {
    background: white;
    border: none;
}

.btn-primary-custom {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border: none;
    color: white;
    transition: all 0.3s ease;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    color: white;
}

.btn-filter {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    border: none;
    color: #1b5e20;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

.card-table {
    overflow: hidden;
}

.card-table .table thead th {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #1b5e20;
    font-weight: 600;
    padding: 14px 16px;
    border: none;
    font-size: 0.85rem;
}

.card-table .table tbody td {
    padding: 14px 16px;
    vertical-align: middle;
    border-color: #f0f0f0;
}

.product-row {
    transition: all 0.2s ease;
}

.product-row:hover {
    background: #fff8e1;
}

.product-image-wrapper {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    overflow: hidden;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
}

.product-thumb {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-image-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2e7d32;
    font-size: 1.3rem;
}

.product-name {
    font-weight: 700;
    color: #1b5e20;
    margin-bottom: 4px;
}

.product-desc {
    font-size: 0.75rem;
    color: #6c757d;
}

.badge-kategori {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #2e7d32;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
}

.badge-min-order {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    color: #e65100;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
}

.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    text-decoration: none;
    cursor: pointer;
    border: none;
}

.btn-action.view {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #2e7d32;
}

.btn-action.edit {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    color: #f57c00;
}

.btn-action.delete {
    background: linear-gradient(135deg, #ffebee, #ffcdd2);
    color: #dc3545;
}

.btn-action:hover {
    transform: scale(1.1);
}

.btn-action.view:hover {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
}

.btn-action.edit:hover {
    background: linear-gradient(135deg, #f57c00, #ef6c00);
    color: white;
}

.btn-action.delete:hover {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
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

@media (max-width: 768px) {
    .page-title {
        font-size: 1.3rem;
    }

    .action-buttons {
        gap: 5px;
    }

    .btn-action {
        width: 28px;
        height: 28px;
    }

    .product-name {
        font-size: 0.85rem;
    }

    .product-desc {
        font-size: 0.65rem;
    }

    .filter-kategori,
    .filter-cari {
        padding: 8px 12px !important;
        font-size: 0.85rem !important;
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
                return response.json().then(err => {
                    throw new Error(err.message || 'Server returned status ' + response.status);
                });
            }
            return response.json();
        })
        .then(data => {
            closeDeleteModal();
            if (data.success) {
                showCustomToast('success', 'Berhasil!', data.message || 'Produk berhasil dihapus');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showCustomToast('error', 'Gagal!', data.message || 'Gagal menghapus produk');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            closeDeleteModal();
            showCustomToast('error', 'Kesalahan Server', error.message || 'Terjadi kesalahan pada server');
        });
});

window.onclick = function(event) {
    var modal = document.getElementById('deleteModal');
    if (event.target == modal) {
        closeDeleteModal();
    }
}

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