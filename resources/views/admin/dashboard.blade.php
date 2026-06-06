@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <!-- Header Dashboard -->
    <div class="dashboard-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="dashboard-title fw-bold mb-1">
                    <i class="bi bi-speedometer2 me-2" style="color: #ffc107;"></i>
                    Dashboard Admin
                </h2>
                <p class="text-muted mb-0">Selamat datang, {{ Auth::user()->name }}! 🎉</p>
            </div>
            <div class="dashboard-date">
                <span class="badge-date">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="stat-info">
                        <h6 class="stat-label">Total Pesanan</h6>
                        <h2 class="stat-value">{{ number_format($totalPesanan, 0, ',', '.') }}</h2>
                    </div>
                </div>
                <div class="stat-progress stat-progress-primary"></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="stat-info">
                        <h6 class="stat-label">Menunggu Bayar</h6>
                        <h2 class="stat-value">{{ number_format($pesananMenunggu, 0, ',', '.') }}</h2>
                    </div>
                </div>
                <div class="stat-progress stat-progress-warning"></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card stat-card-info">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="bi bi-gear"></i>
                    </div>
                    <div class="stat-info">
                        <h6 class="stat-label">Diproses</h6>
                        <h2 class="stat-value">{{ number_format($pesananDiproses, 0, ',', '.') }}</h2>
                    </div>
                </div>
                <div class="stat-progress stat-progress-info"></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card stat-card-success">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h6 class="stat-label">Selesai</h6>
                        <h2 class="stat-value">{{ number_format($pesananSelesai, 0, ',', '.') }}</h2>
                    </div>
                </div>
                <div class="stat-progress stat-progress-success"></div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-4">
            <div class="stat-card stat-card-danger">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h6 class="stat-label">Dibatalkan</h6>
                        <h2 class="stat-value">{{ number_format($pesananDibatalkan, 0, ',', '.') }}</h2>
                    </div>
                </div>
                <div class="stat-progress stat-progress-danger"></div>
            </div>
        </div>
        <div class="col-6 col-lg-4">
            <div class="stat-card stat-card-secondary">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="bi bi-box"></i>
                    </div>
                    <div class="stat-info">
                        <h6 class="stat-label">Total Produk</h6>
                        <h2 class="stat-value">{{ number_format($totalProduk, 0, ',', '.') }}</h2>
                    </div>
                </div>
                <div class="stat-progress stat-progress-secondary"></div>
            </div>
        </div>
        <div class="col-6 col-lg-4">
            <div class="stat-card stat-card-cyan">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-info">
                        <h6 class="stat-label">Total Pelanggan</h6>
                        <h2 class="stat-value">{{ number_format($totalPelanggan, 0, ',', '.') }}</h2>
                    </div>
                </div>
                <div class="stat-progress stat-progress-cyan"></div>
            </div>
        </div>
    </div>

    <!-- Pesanan Terbaru -->
    <div class="card-table card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                    <i class="bi bi-clock-history me-2"></i> Pesanan Terbaru
                </h5>
                <a href="{{ route('admin.pesanan.index') }}" class="btn-view-all">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 18%" class="text-center">Nomor Pesanan</th>
                            <th style="width: 25%" class="text-center">Pelanggan</th>
                            <th style="width: 15%" class="text-center">Total</th>
                            <th style="width: 15%" class="text-center">Status</th>
                            <th style="width: 20%" class="text-center">Tanggal</th>
                            <th style="width: 7%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesananTerbaru as $item)
                        <tr>
                            <td class="fw-semibold" style="color: #1b5e20;">
                                <span class="badge-order">{{ $item->nomor_pesanan }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-person-circle text-success"></i>
                                    <span>{{ $item->user->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="fw-bold text-success">Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                            </td>
                            <td>
                                @php
                                $statusClass = match($item->status) {
                                'menunggu_pembayaran' => 'badge-status-warning',
                                'diproses' => 'badge-status-primary',
                                'terlambat' => 'badge-status-danger',
                                'selesai' => 'badge-status-success',
                                'dibatalkan' => 'badge-status-secondary',
                                default => 'badge-status-info'
                                };
                                $statusLabel = match($item->status) {
                                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                'diproses' => 'Diproses',
                                'terlambat' => 'Terlambat ⚠️',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                                default => ucfirst($item->status)
                                };
                                @endphp
                                <span class="badge-custom-status {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="bi bi-calendar3 text-muted small"></i>
                                    <span
                                        class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d F Y H:i') }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.pesanan.show', $item->id) }}" class="btn-action-view"
                                    title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="mt-2 text-muted mb-0">Belum ada pesanan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* ============================================ */
/* DASHBOARD ADMIN STYLING */
/* ============================================ */

.dashboard-container {
    animation: fadeInUp 0.5s ease;
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

/* Dashboard Header */
.dashboard-title {
    background: linear-gradient(135deg, #1b5e20, #2e7d32, #ffc107);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-size: 1.8rem;
}

.badge-date {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
    color: #1b5e20;
}

/* ============================================ */
/* STATISTIC CARDS */
/* ============================================ */
.stat-card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.stat-card-inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
}

.stat-info {
    text-align: right;
}

.stat-label {
    font-size: 0.85rem;
    font-weight: 500;
    color: #6c757d;
    margin-bottom: 5px;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    margin: 0;
    line-height: 1.2;
}

.stat-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 4px;
    width: 0%;
    transition: width 0.3s ease;
}

.stat-card:hover .stat-progress {
    width: 100%;
}

/* Card Colors */
.stat-card-primary {
    border-left: 4px solid #0d6efd;
}

.stat-card-primary .stat-icon {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.stat-card-primary .stat-value {
    color: #0d6efd;
}

.stat-progress-primary {
    background: linear-gradient(90deg, #0d6efd, #0dcaf0);
}

.stat-card-warning {
    border-left: 4px solid #ffc107;
}

.stat-card-warning .stat-icon {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

.stat-card-warning .stat-value {
    color: #ff8f00;
}

.stat-progress-warning {
    background: linear-gradient(90deg, #ffc107, #ffb300);
}

.stat-card-info {
    border-left: 4px solid #0dcaf0;
}

.stat-card-info .stat-icon {
    background: rgba(13, 202, 240, 0.1);
    color: #0dcaf0;
}

.stat-card-info .stat-value {
    color: #0dcaf0;
}

.stat-progress-info {
    background: linear-gradient(90deg, #0dcaf0, #0d6efd);
}

.stat-card-success {
    border-left: 4px solid #198754;
}

.stat-card-success .stat-icon {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.stat-card-success .stat-value {
    color: #198754;
}

.stat-progress-success {
    background: linear-gradient(90deg, #198754, #2e7d32);
}

.stat-card-danger {
    border-left: 4px solid #dc3545;
}

.stat-card-danger .stat-icon {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.stat-card-danger .stat-value {
    color: #dc3545;
}

.stat-progress-danger {
    background: linear-gradient(90deg, #dc3545, #ff6b6b);
}

.stat-card-secondary {
    border-left: 4px solid #6c757d;
}

.stat-card-secondary .stat-icon {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.stat-card-secondary .stat-value {
    color: #6c757d;
}

.stat-progress-secondary {
    background: linear-gradient(90deg, #6c757d, #adb5bd);
}

.stat-card-cyan {
    border-left: 4px solid #20c997;
}

.stat-card-cyan .stat-icon {
    background: rgba(32, 201, 151, 0.1);
    color: #20c997;
}

.stat-card-cyan .stat-value {
    color: #20c997;
}

.stat-progress-cyan {
    background: linear-gradient(90deg, #20c997, #2e7d32);
}

/* ============================================ */
/* TABLE CARD */
/* ============================================ */
.card-table {
    overflow: hidden;
}

.card-table .table {
    margin-bottom: 0;
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
    font-size: 0.85rem;
}

.card-table .table tbody tr:hover {
    background: #fff8e1;
}

/* Badge Status Custom - RAPI */
.badge-custom-status {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
    text-align: center;
    min-width: 160px;
    letter-spacing: 0.3px;
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
    animation: pulse 1.5s infinite;
}

.badge-status-success {
    background: linear-gradient(135deg, #198754, #157347);
    color: white;
}

.badge-status-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    color: white;
}

.badge-status-info {
    background: linear-gradient(135deg, #0dcaf0, #0bb5d8);
    color: black;
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

/* Badge Order Number */
.badge-order {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #1b5e20;
}

/* Button View */
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
}

.btn-action-view:hover {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
    transform: scale(1.05);
}

/* Button View All */
.btn-view-all {
    padding: 6px 16px;
    border-radius: 50px;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #2e7d32;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-view-all:hover {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
    transform: translateX(3px);
}

/* ============================================ */
/* RESPONSIVE */
/* ============================================ */
@media (max-width: 768px) {
    .stat-card {
        padding: 15px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }

    .stat-value {
        font-size: 1.5rem;
    }

    .stat-label {
        font-size: 0.75rem;
    }

    .dashboard-title {
        font-size: 1.3rem;
    }

    .card-table .table thead th,
    .card-table .table tbody td {
        padding: 10px 12px;
        font-size: 0.75rem;
    }

    .badge-custom-status {
        padding: 4px 10px;
        font-size: 0.65rem;
        min-width: 130px;
    }
}

@media (max-width: 576px) {
    .stat-card {
        text-align: center;
    }

    .stat-card-inner {
        flex-direction: column;
        gap: 10px;
    }

    .stat-info {
        text-align: center;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
    }

    .badge-custom-status {
        min-width: 110px;
        font-size: 0.6rem;
    }
}
</style>
@endpush
@endsection