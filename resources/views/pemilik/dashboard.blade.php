@extends('layouts.pemilik')

@section('title', 'Dashboard Pemilik')

@section('content')
<div class="container-fluid px-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card p-4 rounded-4 shadow-sm"
                style="background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-white mb-2">Dashboard Pemilik</h2>
                        <p class="text-white-50 mb-0">Selamat datang di panel pemilik Prisma Sari Catering</p>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="bi bi-graph-up fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards - Menggunakan row cols agar rata -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="stats-card h-100 rounded-4 shadow-sm p-3"
                style="background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-white-50 mb-1 small">Penjualan Hari Ini</p>
                        <h4 class="text-white fw-bold mb-0">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</h4>
                    </div>
                    <div class="rounded-3 p-2" style="background-color: rgba(255,255,255,0.15);">
                        <i class="bi bi-cash-stack fs-4 text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="stats-card h-100 rounded-4 shadow-sm p-3"
                style="background: linear-gradient(135deg, #2e7d32 0%, #388e3c 100%);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-white-50 mb-1 small">Penjualan Bulan Ini</p>
                        <h4 class="text-white fw-bold mb-0">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</h4>
                    </div>
                    <div class="rounded-3 p-2" style="background-color: rgba(255,255,255,0.15);">
                        <i class="bi bi-calendar-week fs-4 text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="stats-card h-100 rounded-4 shadow-sm p-3"
                style="background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%);">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-dark-50 mb-1 small">Total Pesanan</p>
                        <h4 class="text-dark fw-bold mb-0">{{ $totalPesanan }}</h4>
                    </div>
                    <div class="rounded-3 p-2" style="background-color: rgba(0,0,0,0.08);">
                        <i class="bi bi-receipt fs-4 text-dark"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="stats-card h-100 rounded-4 shadow-sm p-3 bg-white border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Pesanan Diproses</p>
                        <h4 class="text-dark fw-bold mb-0">{{ $pesananDiproses }}</h4>
                    </div>
                    <div class="rounded-3 p-2" style="background-color: rgba(255,193,7,0.1);">
                        <i class="bi bi-gear fs-4 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="stats-card h-100 rounded-4 shadow-sm p-3 bg-white border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Pesanan Selesai</p>
                        <h4 class="text-success fw-bold mb-0">{{ $pesananSelesai }}</h4>
                    </div>
                    <div class="rounded-3 p-2" style="background-color: rgba(25,135,84,0.1);">
                        <i class="bi bi-check-circle fs-4 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="stats-card h-100 rounded-4 shadow-sm p-3 bg-white border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small">Total Produk / Pelanggan</p>
                        <h4 class="text-primary fw-bold mb-0">{{ $totalProduk }} / {{ $totalPelanggan }}</h4>
                    </div>
                    <div class="rounded-3 p-2" style="background-color: rgba(13,110,253,0.1);">
                        <i class="bi bi-people fs-4 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Produksi Hari Ini -->
    <div class="card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-3 p-2" style="background-color: #e8f5e9;">
                    <i class="bi bi-calendar-check text-success fs-5"></i>
                </div>
                <h5 class="fw-bold mb-0 text-dark">Jadwal Produksi Hari Ini</h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 ps-4">Jam Mulai</th>
                            <th class="py-3">Jam Selesai</th>
                            <th class="py-3">Nomor Pesanan</th>
                            <th class="py-3">Pelanggan</th>
                            <th class="py-3 pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwalHariIni as $j)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-light text-dark py-2 px-3 rounded-pill">
                                    <i class="bi bi-clock me-1"></i> {{ date('H:i', strtotime($j->jam_mulai)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark py-2 px-3 rounded-pill">
                                    {{ date('H:i', strtotime($j->jam_selesai)) }}
                                </span>
                            </td>
                            <td><strong class="text-success">{{ $j->pesanan->nomor_pesanan ?? '-' }}</strong></td>
                            <td>{{ $j->pesanan->user->name ?? '-' }}</td>
                            <td class="pe-4">
                                @php
                                $statusBadge = match($j->status) {
                                'selesai' => 'success',
                                'produksi' => 'warning',
                                default => 'secondary'
                                };
                                @endphp
                                <span class="badge bg-{{ $statusBadge }} px-3 py-2 rounded-pill">
                                    {{ ucfirst($j->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                <p class="mt-2 text-muted mb-0">Tidak ada jadwal produksi hari ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pesanan Terbaru -->
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-3 p-2" style="background-color: #fff8e1;">
                    <i class="bi bi-clock-history text-warning fs-5"></i>
                </div>
                <h5 class="fw-bold mb-0 text-dark">Pesanan Terbaru</h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 ps-4">Nomor Pesanan</th>
                            <th class="py-3">Pelanggan</th>
                            <th class="py-3">Total</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 pe-4">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesananTerbaru as $item)
                        <tr>
                            <td class="ps-4"><strong class="text-success">{{ $item->nomor_pesanan }}</strong></td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td class="text-success fw-bold">Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                            </td>
                            <td>
                                @php
                                $statusBadge = match($item->status) {
                                'selesai' => 'success',
                                'diproses' => 'primary',
                                'menunggu_pembayaran' => 'warning',
                                'dibatalkan' => 'danger',
                                default => 'secondary'
                                };
                                @endphp
                                <span class="badge bg-{{ $statusBadge }} px-3 py-2 rounded-pill">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>
                            <td class="pe-4">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
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
.stats-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
}

.stats-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-weight: 500;
    font-size: 0.75rem;
}

.text-white-50 {
    color: rgba(255, 255, 255, 0.7) !important;
}

.text-dark-50 {
    color: rgba(0, 0, 0, 0.6) !important;
}

/* Responsive */
@media (max-width: 576px) {
    .stats-card h4 {
        font-size: 1.1rem;
    }

    .stats-card p {
        font-size: 0.7rem;
    }

    .table td,
    .table th {
        font-size: 0.75rem;
    }
}

@media (max-width: 768px) {
    .stats-card h4 {
        font-size: 1.2rem;
    }
}
</style>
@endpush
@endsection