@extends('layouts.pemilik')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-3 p-2" style="background-color: #e8f5e9;">
                    <i class="bi bi-graph-up text-success fs-5"></i>
                </div>
                <h5 class="fw-bold mb-0 text-dark">Laporan Penjualan</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <!-- Filter Section -->
            <div class="filter-section rounded-3 p-4 mb-4"
                style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #e0e0e0;">
                <form method="GET" action="{{ route('pemilik.laporan.index') }}" class="row g-3 align-items-end"
                    id="filterForm">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold mb-2" style="color: #1b5e20;">
                            <i class="bi bi-filter me-1"></i> Periode
                        </label>
                        <select name="periode" class="form-select form-select-lg rounded-3 border-2" id="periode"
                            style="border-color: #e0e0e0;">
                            <option value="harian" {{ $periode == 'harian' ? 'selected' : '' }}>📅 Harian</option>
                            <option value="mingguan" {{ $periode == 'mingguan' ? 'selected' : '' }}>📆 Mingguan</option>
                            <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>🗓️ Bulanan</option>
                        </select>
                    </div>

                    <div class="col-md-3" id="range-harian"
                        style="display: {{ $periode == 'harian' ? 'block' : 'none' }}">
                        <label class="form-label fw-semibold mb-2" style="color: #1b5e20;"><i
                                class="bi bi-calendar me-1"></i> Tanggal</label>
                        <input type="date" name="tanggal_mulai" id="tanggalMulaiInput"
                            class="form-control form-control-lg rounded-3 border-2 date-picker"
                            value="{{ $tanggalMulai }}" style="border-color: #e0e0e0;">
                    </div>

                    <div class="col-md-3" id="range-mingguan"
                        style="display: {{ $periode == 'mingguan' ? 'block' : 'none' }}">
                        <label class="form-label fw-semibold mb-2" style="color: #1b5e20;"><i
                                class="bi bi-calendar me-1"></i> Dari Tanggal</label>
                        <input type="date" name="tanggal_mulai" id="dariTanggalInput"
                            class="form-control form-control-lg rounded-3 border-2 date-picker"
                            value="{{ $tanggalMulai }}" style="border-color: #e0e0e0;">
                    </div>

                    <div class="col-md-3" id="range-mingguan2"
                        style="display: {{ $periode == 'mingguan' ? 'block' : 'none' }}">
                        <label class="form-label fw-semibold mb-2" style="color: #1b5e20;"><i
                                class="bi bi-calendar me-1"></i> Sampai Tanggal</label>
                        <input type="date" name="tanggal_selesai" id="sampaiTanggalInput"
                            class="form-control form-control-lg rounded-3 border-2 date-picker"
                            value="{{ $tanggalSelesai }}" style="border-color: #e0e0e0;">
                    </div>

                    <div class="col-md-3" id="range-bulanan"
                        style="display: {{ $periode == 'bulanan' ? 'block' : 'none' }}">
                        <label class="form-label fw-semibold mb-2" style="color: #1b5e20;"><i
                                class="bi bi-calendar-month me-1"></i> Bulan</label>
                        <input type="month" name="bulan" class="form-control form-control-lg rounded-3 border-2"
                            value="{{ $bulan }}" style="border-color: #e0e0e0;">
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success btn-lg w-100 rounded-3"
                            style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); border: none;">
                            <i class="bi bi-search me-2"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Export & Summary -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div class="summary-cards d-flex gap-3">
                    <div class="card border-0 rounded-3 shadow-sm"
                        style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);">
                        <div class="card-body py-2 px-3">
                            <small class="text-white-50">Total Pendapatan</small>
                            <h5 class="mb-0 fw-bold text-white">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                    <div class="card border-0 rounded-3 shadow-sm"
                        style="background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%);">
                        <div class="card-body py-2 px-3">
                            <small class="text-dark-50">Total Pesanan</small>
                            <h5 class="mb-0 fw-bold text-dark">{{ $totalPesanan }} pesanan</h5>
                        </div>
                    </div>
                </div>
                <a href="{{ route('pemilik.laporan.export', request()->all()) }}"
                    class="btn btn-success btn-lg rounded-3"
                    style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); border: none;">
                    <i class="bi bi-file-excel me-2"></i> Export ke Excel
                </a>
            </div>

            <!-- Tabel Pesanan -->
            <div class="table-responsive mb-5">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 text-muted">No</th>
                            <th class="py-3 text-muted"><i class="bi bi-calendar me-1"></i> Tanggal</th>
                            <th class="py-3 text-muted"><i class="bi bi-receipt me-1"></i> Nomor Pesanan</th>
                            <th class="py-3 text-muted"><i class="bi bi-person me-1"></i> Pelanggan</th>
                            <th class="py-3 text-muted"><i class="bi bi-cash me-1"></i> Total</th>
                            <th class="py-3 text-muted"><i class="bi bi-info-circle me-1"></i> Status</th>
                            <th class="py-3 text-muted"><i class="bi bi-calendar-check me-1"></i> Tanggal Ambil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y H:i') }} WIB</td>
                            <td><strong class="text-success">{{ $item->nomor_pesanan }}</strong></td>
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
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pengambilan)->translatedFormat('d F Y') }} WIB
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-folder2-open fs-1 text-muted"></i>
                                <p class="mt-2 text-muted mb-0">Tidak ada data</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-active">
                        <tr>
                            <th colspan="4" class="text-end">TOTAL</th>
                            <th class="text-success fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Produk Terlaris -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 pt-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-3 p-2" style="background-color: #fff8e1;">
                            <i class="bi bi-trophy text-warning fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-0 text-dark">Top 10 Produk Terlaris</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 text-muted">No</th>
                                    <th class="py-3 text-muted"><i class="bi bi-box me-1"></i> Nama Produk</th>
                                    <th class="py-3 text-muted"><i class="bi bi-bar-chart me-1"></i> Total Terjual</th>
                        </table>
                        </thead>
                        <tbody>
                            @forelse($produkTerlaris as $key => $produk)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><strong class="text-success">{{ $produk->nama_item }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-primary">{{ $produk->total_jumlah }}</span>
                                        <span class="text-muted">pcs</span>
                                        <div class="progress flex-grow-1"
                                            style="height: 6px; background-color: #e9ecef;">
                                            <div class="progress-bar bg-success"
                                                style="width: {{ min(($produk->total_jumlah / ($produkTerlaris->first()->total_jumlah ?? 1)) * 100, 100) }}%; border-radius: 10px;">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <i class="bi bi-bar-chart fs-1 text-muted"></i>
                                    <p class="mt-2 text-muted mb-0">Belum ada data penjualan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
.filter-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #e0e0e0;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s ease;
}

.badge {
    font-weight: 500;
}

.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.3s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: #2e7d32;
    box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
}

.btn-success:hover {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.text-white-50 {
    color: rgba(255, 255, 255, 0.7) !important;
}

.text-dark-50 {
    color: rgba(0, 0, 0, 0.6) !important;
}

/* ============================================
   FLATPICKR CALENDAR STYLING - TEMA PRISMA SARI
   ============================================ */

.flatpickr-calendar {
    border-radius: 20px !important;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
    border: none !important;
    background: #fff8e1 !important;
    overflow: hidden !important;
    width: 320px !important;
}

.flatpickr-month {
    background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%) !important;
    margin: 0 !important;
    padding: 0 !important;
    height: 65px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 18px 18px 0 0 !important;
}

.flatpickr-current-month {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
    height: auto !important;
    position: relative !important;
    top: 0 !important;
    left: 0 !important;
    transform: none !important;
    width: 100% !important;
}

.flatpickr-current-month .flatpickr-monthDropdown-months,
.flatpickr-current-month .numInputWrapper {
    color: white !important;
    font-weight: bold !important;
    font-size: 1rem !important;
    background: transparent !important;
}

.flatpickr-monthDropdown-months {
    background: transparent !important;
    color: white !important;
    border: none !important;
}

.flatpickr-monthDropdown-months option {
    color: #1b5e20 !important;
    background: white !important;
}

.numInputWrapper .numInput {
    color: white !important;
    background: transparent !important;
}

.flatpickr-prev-month,
.flatpickr-next-month {
    top: 20px !important;
    padding: 5px !important;
}

.flatpickr-prev-month svg,
.flatpickr-next-month svg {
    fill: white !important;
    stroke: white !important;
}

.flatpickr-prev-month:hover svg,
.flatpickr-next-month:hover svg {
    fill: #ffc107 !important;
    stroke: #ffc107 !important;
    transform: scale(1.1);
}

.flatpickr-weekdays {
    background: #fff8e1 !important;
    padding: 10px 0 8px 0 !important;
    margin: 0 !important;
}

.flatpickr-weekday {
    color: #2e7d32 !important;
    font-weight: bold !important;
    font-size: 0.8rem !important;
    background: transparent !important;
}

.flatpickr-days {
    background: #fff8e1 !important;
    padding: 0 5px 10px 5px !important;
}

.flatpickr-day {
    color: #b8860b !important;
    font-weight: 500 !important;
    border-radius: 12px !important;
    margin: 2px !important;
    transition: all 0.2s ease !important;
    background: transparent !important;
    border: none !important;
}

.flatpickr-day:hover {
    background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%) !important;
    color: #1b5e20 !important;
    border: none !important;
    transform: scale(1.05);
}

.flatpickr-day.selected,
.flatpickr-day.startRange,
.flatpickr-day.endRange {
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%) !important;
    color: white !important;
    border: none !important;
    box-shadow: 0 2px 8px rgba(46, 125, 50, 0.3);
}

.flatpickr-day.today {
    border: 2px solid #ffc107 !important;
    background: #fff8e1 !important;
    color: #1b5e20 !important;
    font-weight: bold !important;
}

.flatpickr-day.prevMonthDay,
.flatpickr-day.nextMonthDay {
    color: #d4c5a0 !important;
    opacity: 0.6 !important;
}

.flatpickr-day.inRange,
.flatpickr-day.week.selected {
    background: #e8f5e9 !important;
    color: #1b5e20 !important;
}

.flatpickr-time {
    background: #fef9e6 !important;
    border-top: 1px solid #e0e0e0 !important;
    border-radius: 0 0 18px 18px !important;
}

.flatpickr-time input,
.flatpickr-time .flatpickr-time-separator {
    color: #1b5e20 !important;
    font-weight: bold !important;
}

.flatpickr-time .numInputWrapper:hover {
    background: #e8f5e9 !important;
}

.flatpickr-day:active {
    transform: scale(0.95);
}

/* Input date styling */
input[type="date"] {
    background: linear-gradient(135deg, #fff8e1 0%, #fef9e6 50%, #e8f5e9 100%) !important;
    border: 1px solid #e0e0e0 !important;
    border-radius: 12px !important;
    padding: 12px 14px !important;
    font-weight: 500 !important;
    color: #1b5e20 !important;
    transition: all 0.3s ease !important;
    cursor: pointer !important;
}

input[type="date"]:hover {
    border-color: #ffc107 !important;
    box-shadow: 0 2px 12px rgba(255, 193, 7, 0.25) !important;
    transform: translateY(-1px) !important;
}

::-webkit-calendar-picker-indicator {
    background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%) !important;
    border-radius: 10px !important;
    padding: 5px 10px !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    margin-right: 5px !important;
}

::-webkit-calendar-picker-indicator:hover {
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%) !important;
    transform: scale(1.05) !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Flatpickr untuk semua input date
    flatpickr("#tanggalMulaiInput", {
        locale: "id",
        dateFormat: "Y-m-d",
        altFormat: "l, j F Y",
        altInput: true,
        allowInput: true,
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                document.getElementById('filterForm').submit();
            }
        }
    });

    flatpickr("#dariTanggalInput", {
        locale: "id",
        dateFormat: "Y-m-d",
        altFormat: "l, j F Y",
        altInput: true,
        allowInput: true
    });

    flatpickr("#sampaiTanggalInput", {
        locale: "id",
        dateFormat: "Y-m-d",
        altFormat: "l, j F Y",
        altInput: true,
        allowInput: true
    });
});

// Event listener untuk periode
document.getElementById('periode').addEventListener('change', function() {
    var periode = this.value;
    document.getElementById('range-harian').style.display = 'none';
    document.getElementById('range-mingguan').style.display = 'none';
    document.getElementById('range-mingguan2').style.display = 'none';
    document.getElementById('range-bulanan').style.display = 'none';

    if (periode == 'harian') {
        document.getElementById('range-harian').style.display = 'block';
    } else if (periode == 'mingguan') {
        document.getElementById('range-mingguan').style.display = 'block';
        document.getElementById('range-mingguan2').style.display = 'block';
    } else if (periode == 'bulanan') {
        document.getElementById('range-bulanan').style.display = 'block';
    }
});
</script>
@endpush
@endsection