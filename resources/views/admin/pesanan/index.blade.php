@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="pesanan-container">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold mb-1">
                <i class="bi bi-receipt me-2" style="color: #ffc107;"></i>
                Kelola Pesanan
            </h2>
            <p class="text-muted mb-0">Manajemen data pesanan catering</p>
        </div>
    </div>

    <!-- Filter Status -->
    <div class="card-filter card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="bi bi-funnel me-1"></i> Filter Status
                    </label>
                    <select name="status" class="form-select rounded-3 filter-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu_pembayaran"
                            {{ request('status') == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran
                        </option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses
                        </option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-filter w-100 rounded-pill">
                        <i class="bi bi-search me-2"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.pesanan.index') }}" class="btn btn-reset w-100 rounded-pill">
                        <i class="bi bi-arrow-repeat me-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Pesanan -->
    <div class="card-table card border-0 rounded-4 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 18%">Nomor Pesanan</th>
                            <th style="width: 20%">Pelanggan</th>
                            <th style="width: 15%">Total</th>
                            <th style="width: 15%">Status Pesanan</th>
                            <th style="width: 15%">Status Pembayaran</th>
                            <th style="width: 12%">Tanggal</th>
                            <th style="width: 5%" class="text-center">Aksi</th>
                        <tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan as $item)
                        <tr class="order-row">
                            <td>
                                <span class="order-number">{{ $item->nomor_pesanan }}</span>
                                @if($item->is_whatsapp_order)
                                <span class="badge-wa ms-1">
                                    <i class="bi bi-whatsapp"></i> WA
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="customer-info">
                                    <i class="bi bi-person-circle text-success me-1"></i>
                                    {{ $item->user->name ?? '-' }}
                                </div>
                            </td>
                            <td class="fw-bold text-success">Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                            </td>
                            <td>
                                @php
                                if ($item->status == 'menunggu_pembayaran') {
                                $statusClass = 'badge-status-warning';
                                $statusLabel = 'Menunggu Pembayaran';
                                } elseif ($item->status == 'diproses') {
                                $statusClass = 'badge-status-primary';
                                $statusLabel = 'Diproses';
                                } elseif ($item->status == 'selesai') {
                                $statusClass = 'badge-status-success';
                                $statusLabel = 'Selesai';
                                } elseif ($item->status == 'dibatalkan') {
                                $statusClass = 'badge-status-danger';
                                $statusLabel = 'Dibatalkan';
                                } else {
                                $statusClass = 'badge-status-secondary';
                                $statusLabel = $item->status;
                                }
                                @endphp
                                <span class="badge-custom-status {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                @php
                                if ($item->status_pembayaran == 'belum_bayar') {
                                $paymentClass = 'badge-status-danger';
                                $paymentLabel = 'Belum Dibayar';
                                } elseif ($item->status_pembayaran == 'menunggu_konfirmasi') {
                                $paymentClass = 'badge-status-warning';
                                $paymentLabel = 'Menunggu Konfirmasi';
                                } elseif ($item->status_pembayaran == 'lunas') {
                                $paymentClass = 'badge-status-success';
                                $paymentLabel = 'Lunas';
                                } else {
                                $paymentClass = 'badge-status-secondary';
                                $paymentLabel = $item->status_pembayaran;
                                }
                                @endphp
                                <span class="badge-custom-status {{ $paymentClass }}">{{ $paymentLabel }}</span>
                            </td>
                            <td>
                                <div class="date-info">
                                    <i class="bi bi-calendar3 text-muted me-1"></i>
                                    {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d M Y H:i') }}
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.pesanan.show', ['id' => $item->id, 'from' => 'pesanan']) }}"
                                    class="btn-action-view" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="mt-2 text-muted mb-0">Belum ada pesanan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            <div class="pagination-wrapper">
                {{ $pesanan->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- CSS untuk Custom Toast Notification -->
<style>
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

<!-- CSS Styling -->
<style>
/* ============================================ */
/* PESANAN CONTAINER */
/* ============================================ */
.pesanan-container {
    animation: fadeInUp 0.5s ease;
}

.page-title {
    background: linear-gradient(135deg, #1b5e20, #2e7d32, #ffc107);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-size: 1.8rem;
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

/* ============================================ */
/* CARD FILTER */
/* ============================================ */
.card-filter {
    background: white;
    border: none;
}

.filter-select {
    border: 1.5px solid #e5e7eb !important;
    border-radius: 12px !important;
    padding: 10px 14px !important;
    font-size: 0.9rem !important;
    transition: all 0.3s ease;
    background-color: white;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%232e7d32'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 12px;
}

.filter-select:hover {
    border-color: #ffc107 !important;
    background-color: #fffbeb !important;
}

.filter-select:focus {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
    outline: none;
}

/* ============================================ */
/* BUTTONS */
/* ============================================ */
.btn-filter {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    border: none;
    color: #1b5e20;
    font-weight: 600;
    transition: all 0.3s ease;
    padding: 10px 16px;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

.btn-reset {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border: 1px solid #2e7d32;
    color: #2e7d32;
    font-weight: 600;
    transition: all 0.3s ease;
    padding: 10px 16px;
}

.btn-reset:hover {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);
}

/* ============================================ */
/* TABLE STYLING */
/* ============================================ */
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

.order-row {
    transition: all 0.2s ease;
}

.order-row:hover {
    background: #fff8e1;
}

/* Order Number */
.order-number {
    font-weight: 700;
    color: #1b5e20;
    font-family: monospace;
    font-size: 0.85rem;
}

/* Customer Info */
.customer-info {
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Date Info */
.date-info {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: #6c757d;
}

/* ============================================ */
/* BADGE STATUS */
/* ============================================ */
.badge-custom-status {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
    text-align: center;
    min-width: 140px;
}

.badge-status-warning {
    background: linear-gradient(135deg, #f57c00, #ef6c00);
    color: white;
}

.badge-status-primary {
    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    color: white;
}

.badge-status-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

.badge-status-success {
    background: linear-gradient(135deg, #198754, #157347);
    color: white;
}

.badge-status-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    color: white;
}

/* Badge WA */
.badge-wa {
    background: linear-gradient(135deg, #25D366, #128C7E);
    color: white;
    padding: 3px 8px;
    border-radius: 50px;
    font-size: 0.65rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}

/* ============================================ */
/* ACTION BUTTON */
/* ============================================ */
.btn-action-view {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #2e7d32;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-action-view:hover {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
    transform: scale(1.05);
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
.pagination-wrapper nav p.small {
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
/* RESPONSIVE */
/* ============================================ */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.3rem;
    }

    .badge-custom-status {
        min-width: 110px;
        padding: 4px 10px;
        font-size: 0.65rem;
    }

    .filter-select {
        padding: 8px 12px !important;
        font-size: 0.85rem !important;
    }

    .btn-filter,
    .btn-reset {
        padding: 8px 12px;
        font-size: 0.85rem;
    }
}
</style>

@push('scripts')
<script>
// ============================================================
// CUSTOM TOAST NOTIFICATION (SATU-SATUNYA NOTIFIKASI)
// ============================================================
function showCustomToast(type, title, message) {
    var existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => toast.remove());

    var toastDiv = document.createElement('div');
    toastDiv.className = 'custom-toast toast-' + type;
    toastDiv.innerHTML = '<div class="toast-icon"><i class="fas fa-' + (type === 'success' ? 'check-circle' :
            'exclamation-triangle') + '"></i></div>' +
        '<div class="toast-content"><div class="toast-title">' + title + '</div><div class="toast-message">' + message +
        '</div></div>' +
        '<button class="toast-close">&times;</button>';

    toastDiv.querySelector('.toast-close').onclick = function() {
        toastDiv.remove();
    };
    document.body.appendChild(toastDiv);
    setTimeout(function() {
        toastDiv.classList.add('show');
    }, 10);
    setTimeout(function() {
        if (toastDiv.parentNode) {
            toastDiv.classList.remove('show');
            setTimeout(function() {
                toastDiv.remove();
            }, 300);
        }
    }, 3000);
}
</script>
@endpush
@endsection