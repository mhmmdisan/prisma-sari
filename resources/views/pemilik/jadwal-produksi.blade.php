@extends('layouts.pemilik')

@section('title', 'Jadwal Produksi')

@section('content')
<div class="container-fluid px-4">
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-3 p-2" style="background-color: #e8f5e9;">
                    <i class="bi bi-calendar-week text-success fs-5"></i>
                </div>
                <h5 class="fw-bold mb-0 text-dark">Jadwal Produksi</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <!-- Filter Tanggal -->
            <div class="filter-section rounded-3 p-4 mb-4"
                style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                <form method="GET" action="{{ route('pemilik.jadwal-produksi.index') }}" class="row g-3 align-items-end"
                    id="filterForm">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2" style="color: #1b5e20;">
                            <i class="bi bi-calendar me-1"></i> Pilih Tanggal
                        </label>
                        <input type="date" name="tanggal" class="form-control form-control-lg rounded-3 date-picker"
                            style="border-color: #e0e0e0;" value="{{ $tanggal }}" id="tanggalInput">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success btn-lg w-100 rounded-3"
                            style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); border: none;">
                            <i class="bi bi-search me-2"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Jadwal Hari Ini -->
            <div class="schedule-section mb-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="line-primary me-3"
                        style="width: 4px; height: 30px; background: linear-gradient(135deg, #1b5e20, #2e7d32); border-radius: 4px;">
                    </div>
                    <h5 class="fw-bold mb-0" style="color: #1b5e20;">Jadwal Produksi -
                        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 text-muted"><i class="bi bi-clock me-1"></i> Jam Mulai</th>
                                <th class="py-3 text-muted"><i class="bi bi-clock me-1"></i> Jam Selesai</th>
                                <th class="py-3 text-muted"><i class="bi bi-receipt me-1"></i> Nomor Pesanan</th>
                                <th class="py-3 text-muted"><i class="bi bi-person me-1"></i> Pelanggan</th>
                                <th class="py-3 text-muted"><i class="bi bi-info-circle me-1"></i> Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwal as $j)
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark py-2 px-3 rounded-pill">
                                        <i class="bi bi-clock me-1"></i> {{ date('H:i', strtotime($j->jam_mulai)) }} WIB
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark py-2 px-3 rounded-pill">
                                        {{ date('H:i', strtotime($j->jam_selesai)) }} WIB
                                    </span>
                                </td>
                                <td><strong class="text-success">{{ $j->pesanan->nomor_pesanan ?? '-' }}</strong></td>
                                <td>{{ $j->pesanan->user->name ?? '-' }}</td>
                                <td>
                                    @php
                                    $statusBadge = match($j->status) {
                                    'selesai' => 'success',
                                    'produksi' => 'warning',
                                    default => 'secondary'
                                    };
                                    $statusIcon = match($j->status) {
                                    'selesai' => 'bi-check-circle',
                                    'produksi' => 'bi-gear',
                                    default => 'bi-clock'
                                    };
                                    @endphp
                                    <span class="badge bg-{{ $statusBadge }} px-3 py-2 rounded-pill">
                                        <i class="bi {{ $statusIcon }} me-1"></i>
                                        {{ ucfirst($j->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <td>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                <p class="mt-2 text-muted mb-0">Tidak ada jadwal produksi pada tanggal ini</p>
                            </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Jadwal Mendatang -->
            <div class="schedule-section">
                <div class="d-flex align-items-center mb-4">
                    <div class="line-primary me-3"
                        style="width: 4px; height: 30px; background: linear-gradient(135deg, #1b5e20, #2e7d32); border-radius: 4px;">
                    </div>
                    <h5 class="fw-bold mb-0" style="color: #1b5e20;">Jadwal Produksi Mendatang (7 Hari Ke Depan)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 text-muted"><i class="bi bi-calendar me-1"></i> Tanggal</th>
                                <th class="py-3 text-muted"><i class="bi bi-clock me-1"></i> Jam Mulai</th>
                                <th class="py-3 text-muted"><i class="bi bi-clock me-1"></i> Jam Selesai</th>
                                <th class="py-3 text-muted"><i class="bi bi-receipt me-1"></i> Nomor Pesanan</th>
                                <th class="py-3 text-muted"><i class="bi bi-person me-1"></i> Pelanggan</th>
                                <th class="py-3 text-muted"><i class="bi bi-info-circle me-1"></i> Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalMendatang as $tgl => $items)
                            @foreach($items as $j)
                            <tr>
                                <td><strong
                                        class="text-success">{{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark py-2 px-3 rounded-pill">
                                        {{ date('H:i', strtotime($j->jam_mulai)) }} WIB
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark py-2 px-3 rounded-pill">
                                        {{ date('H:i', strtotime($j->jam_selesai)) }} WIB
                                    </span>
                                </td>
                                <td><strong class="text-success">{{ $j->pesanan->nomor_pesanan ?? '-' }}</strong></td>
                                <td>{{ $j->pesanan->user->name ?? '-' }}</td>
                                <td>
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
                            @endforeach
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-calendar-week fs-1 text-muted"></i>
                                    <p class="mt-2 text-muted mb-0">Tidak ada jadwal produksi mendatang</p>
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

.form-control:focus {
    border-color: #2e7d32;
    box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
}

.btn-success:hover {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

/* ============================================
   FLATPICKR CALENDAR STYLING - TEMA PRISMA SARI (BACKGROUND FULL)
   ============================================ */

/* Popup kalender utama - background krem penuh */
.flatpickr-calendar {
    border-radius: 20px !important;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
    border: none !important;
    background: linear-gradient(135deg, #fff8e1 0%, #fef9e6 100%) !important;
    overflow: hidden !important;
    width: 320px !important;
}

/* Pastikan seluruh bagian dalam kalender menggunakan background krem */
.flatpickr-calendar .flatpickr-innerContainer,
.flatpickr-calendar .flatpickr-rContainer,
.flatpickr-calendar .flatpickr-days,
.flatpickr-calendar .dayContainer {
    background: transparent !important;
}

/* Header kalender (bulan dan tahun) - HIJAU GRADIEN */
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

/* Container bulan dan tahun */
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

/* Tombol prev/next */
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

/* Hari dalam seminggu */
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

/* Grid tanggal */
.flatpickr-days {
    background: #fff8e1 !important;
    padding: 0 5px 10px 5px !important;
}

/* Angka tanggal */
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

/* Footer (Clear/Today) - background krem */
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

/* Animasi */
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
    flatpickr("#tanggalInput", {
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
});
</script>
@endpush
@endsection