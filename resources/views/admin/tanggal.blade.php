@extends('layouts.admin')

@section('title', 'Kelola Tanggal Nonaktif')

@section('content')
<div class="tanggal-container">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold mb-1">
                <i class="bi bi-calendar-x me-2" style="color: #ffc107;"></i>
                Kelola Tanggal Pesanan
            </h2>
            <p class="text-muted mb-0">Atur tanggal nonaktif untuk produksi catering</p>
        </div>
    </div>

    <!-- Alert Info -->
    <div class="info-banner mb-4">
        <i class="bi bi-info-circle-fill me-2"></i>
        <span>Tanggal yang dinonaktifkan (OFF) tidak dapat dipilih oleh pelanggan saat checkout.</span>
    </div>

    <div class="row g-4">
        <!-- Form Tambah Tanggal OFF -->
        <div class="col-md-4">
            <div class="card-off card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="header-icon-off">
                            <i class="bi bi-toggle-off"></i>
                        </div>
                        <h5 class="mb-0 fw-bold" style="color: #c62828;">Nonaktifkan Tanggal</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.tanggal.store') }}" id="formTambahTanggal" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="bi bi-calendar-date text-warning me-1"></i> Pilih Tanggal
                            </label>
                            <input type="text" name="tanggal" id="tanggal" class="form-control rounded-3 date-picker"
                                placeholder="Pilih tanggal" required>
                            <div class="invalid-feedback-custom" id="error_tanggal"
                                style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                                <i class="bi bi-exclamation-circle me-1"></i> Tanggal harus dipilih!
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">
                                <i class="bi bi-pencil-square text-info me-1"></i> Keterangan (Opsional)
                            </label>
                            <textarea name="keterangan" class="form-control rounded-3" rows="3"
                                placeholder="Contoh: Kapasitas penuh, Libur Nasional, Cuti Bersama"></textarea>
                        </div>
                        <button type="submit" class="btn-off w-100">
                            <i class="bi bi-toggle-off me-2"></i> Nonaktifkan Tanggal
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Tanggal Nonaktif -->
        <div class="col-md-8">
            <div class="card-table card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="header-icon-table">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <h5 class="mb-0 fw-bold" style="color: #1b5e20;">Daftar Tanggal Nonaktif</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 5%" class="text-center">No</th>
                                    <th style="width: 28%" class="text-center">Tanggal</th>
                                    <th style="width: 22%" class="text-center">Keterangan</th>
                                    <th style="width: 25%" class="text-center">Penanggung Jawab</th> <!-- Kolom baru -->
                                    <th style="width: 15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tanggalNonaktif as $item)
                                @if($item->status == 'nonaktif')
                                <tr class="tanggal-row">
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle">
                                        <div class="d-flex flex-column">
                                            <span class="tanggal-date fw-semibold">
                                                <i class="bi bi-calendar-x-fill text-danger me-2"></i>
                                                {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                                            </span>
                                            <small class="text-muted mt-1">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->diffForHumans() }}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        @if($item->keterangan)
                                        <span class="badge-keterangan">
                                            <i class="bi bi-info-circle me-1"></i> {{ $item->keterangan }}
                                        </span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($item->createdBy)
                                        <span class="badge bg-light text-dark border border-1 border-secondary-subtle rounded-pill px-3 py-2">
                                            <i class="bi bi-person-circle me-1"></i>
                                            {{ $item->createdBy->name }}
                                        </span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn-aktifkan"
                                            onclick="openAktifkanModal({{ $item->id }}, '{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('l, d F Y') }}')">
                                            <i class="bi bi-toggle-on me-1"></i> Aktifkan
                                        </button>
                                    </td>
                                </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="bi bi-calendar-check fs-1" style="color: #2e7d32;"></i>
                                            <p class="mt-3 fw-semibold" style="color: #2e7d32;">Semua Tanggal Aktif</p>
                                            <p class="text-muted small">Tidak ada tanggal yang dinonaktifkan</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($tanggalNonaktif->count() > 0)
                <div class="card-footer bg-white border-0 py-3">
                    <div class="pagination-wrapper">
                        {{ $tanggalNonaktif->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI AKTIFKAN TANGGAL -->
<div id="aktifkanModal"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 100000; margin: 0; padding: 0;">
    <div
        style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 20px; box-sizing: border-box;">
        <div
            style="background: white; border-radius: 28px; max-width: 400px; width: 100%; margin: 0 auto; box-shadow: 0 30px 60px rgba(0,0,0,0.4); overflow: hidden;">
            <div
                style="background: linear-gradient(135deg, #2e7d32, #1b5e20); color: white; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; font-size: 1rem; font-weight: 600;">
                    <i class="bi bi-toggle-on me-2"></i> Konfirmasi Aktifkan
                </h5>
                <button type="button" onclick="closeAktifkanModal()"
                    style="background: rgba(255,255,255,0.2); border: none; color: white; width: 30px; height: 30px; border-radius: 50%; font-size: 18px; cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 24px; text-align: center;">
                <i class="bi bi-question-circle" style="font-size: 60px; color: #2e7d32;"></i>
                <h5 class="mt-3 fw-bold">Aktifkan Tanggal</h5>
                <p class="text-muted mb-2" id="aktifkanModalMessage">Apakah Anda yakin ingin mengaktifkan tanggal ini?
                </p>
                <div class="d-flex gap-3 justify-content-center mt-4">
                    <button type="button" onclick="closeAktifkanModal()"
                        class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="button" id="confirmAktifkanBtn" class="btn btn-success rounded-pill px-4"
                        style="background: linear-gradient(135deg, #2e7d32, #1b5e20); border: none;">
                        <i class="bi bi-check-circle me-1"></i> Ya, Aktifkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS untuk Custom Toast -->
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

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
.tanggal-container {
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

.info-banner {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border-radius: 12px;
    padding: 12px 20px;
    color: #1b5e20;
    border-left: 4px solid #ffc107;
    font-size: 0.85rem;
}

.card-off {
    overflow: hidden;
    background: white;
}

.header-icon-off {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #ffebee, #ffcdd2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #c62828;
}

.btn-off {
    background: linear-gradient(135deg, #dc3545, #c62828);
    border: none;
    color: white;
    font-weight: 600;
    padding: 12px 20px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.btn-off:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    color: white;
}

.card-table {
    overflow: hidden;
    background: white;
}

.header-icon-table {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2e7d32;
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

.tanggal-row {
    transition: all 0.2s ease;
}

.tanggal-row:hover {
    background: #fff8e1;
}

.tanggal-date {
    font-size: 0.9rem;
    color: #1b5e20;
}

.badge-keterangan {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    color: #e65100;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-block;
}

.btn-aktifkan {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border: none;
    color: #2e7d32;
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-aktifkan:hover {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
    transform: scale(1.02);
}

.form-control {
    border-radius: 12px;
    border: 1.5px solid #e5e7eb;
    padding: 10px 14px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    outline: none;
}

.form-control.error {
    border-color: #dc3545 !important;
    background-color: #fff5f5 !important;
}

.empty-state {
    padding: 40px 20px;
}

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
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border-color: #2e7d32;
    color: white;
}

/* ============================================
   FLATPICKR CALENDAR STYLING - TEMA PRISMA SARI
   ============================================ */
.flatpickr-calendar {
    border-radius: 20px !important;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
    border: none !important;
    background: linear-gradient(135deg, #fff8e1 0%, #fef9e6 100%) !important;
    overflow: hidden !important;
    width: 320px !important;
}

.flatpickr-calendar .flatpickr-innerContainer,
.flatpickr-calendar .flatpickr-rContainer,
.flatpickr-calendar .flatpickr-days,
.flatpickr-calendar .dayContainer {
    background: transparent !important;
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

@media (max-width: 768px) {
    .page-title {
        font-size: 1.3rem;
    }

    .tanggal-date {
        font-size: 0.8rem;
    }

    .btn-aktifkan {
        padding: 4px 12px;
        font-size: 0.7rem;
    }

    .badge-keterangan {
        font-size: 0.7rem;
        padding: 3px 10px;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
flatpickr("#tanggal", {
    locale: "id",
    dateFormat: "Y-m-d",
    altFormat: "l, j F Y",
    altInput: true,
    allowInput: true,
    minDate: "today"
});

// ============================================================
// VALIDASI & SUBMIT FORM TAMBAH TANGGAL - AJAX
// ============================================================
document.getElementById('formTambahTanggal')?.addEventListener('submit', function(e) {
    e.preventDefault();

    var tanggalInput = document.getElementById('tanggal');
    var errorTanggal = document.getElementById('error_tanggal');
    tanggalInput.classList.remove('error');
    if (errorTanggal) errorTanggal.style.display = 'none';

    if (!tanggalInput.value) {
        tanggalInput.classList.add('error');
        if (errorTanggal) errorTanggal.style.display = 'block';
        showCustomToast('error', 'Validasi Gagal', 'Tanggal harus dipilih!');
        return false;
    }

    // Submit AJAX
    var formData = new FormData(this);
    var btnSubmit = document.querySelector('.btn-off');
    var originalText = btnSubmit.innerHTML;

    btnSubmit.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Memproses...';
    btnSubmit.disabled = true;

    fetch('{{ route("admin.tanggal.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showCustomToast('success', 'Berhasil!', data.message);
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                showCustomToast('error', 'Gagal!', data.message);
                btnSubmit.innerHTML = originalText;
                btnSubmit.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showCustomToast('error', 'Kesalahan Server', 'Terjadi kesalahan pada server');
            btnSubmit.innerHTML = originalText;
            btnSubmit.disabled = false;
        });
});

// ============================================================
// MODAL KONFIRMASI AKTIFKAN TANGGAL - AJAX
// ============================================================
var aktifkanId = null;
var aktifkanTanggal = '';

function openAktifkanModal(id, tanggal) {
    aktifkanId = id;
    aktifkanTanggal = tanggal;
    document.getElementById('aktifkanModalMessage').innerHTML =
        'Apakah Anda yakin ingin mengaktifkan tanggal <strong>' + tanggal +
        '</strong>?<br>Pelanggan akan bisa memilih tanggal ini kembali.';
    document.getElementById('aktifkanModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeAktifkanModal() {
    document.getElementById('aktifkanModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    aktifkanId = null;
}

document.getElementById('confirmAktifkanBtn')?.addEventListener('click', function() {
    if (!aktifkanId) {
        showCustomToast('error', 'Gagal!', 'Data tidak ditemukan');
        closeAktifkanModal();
        return;
    }

    var confirmBtn = this;
    var originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Memproses...';
    confirmBtn.disabled = true;

    fetch('{{ url("admin/tanggal") }}/' + aktifkanId + '/aktifkan', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            closeAktifkanModal();
            if (data.success) {
                showCustomToast('success', 'Berhasil!', data.message);
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                showCustomToast('error', 'Gagal!', data.message);
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            closeAktifkanModal();
            showCustomToast('error', 'Kesalahan Server', 'Terjadi kesalahan pada server');
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        });
});

// ============================================================
// TUTUP MODAL SAAT KLIK DI LUAR AREA
// ============================================================
window.onclick = function(event) {
    var modal = document.getElementById('aktifkanModal');
    if (event.target == modal) {
        closeAktifkanModal();
    }
};

// ============================================================
// HAPUS ERROR STYLING SAAT USER MENGISI
// ============================================================
document.getElementById('tanggal')?.addEventListener('change', function() {
    this.classList.remove('error');
    var errorEl = document.getElementById('error_tanggal');
    if (errorEl) errorEl.style.display = 'none';
});

// ============================================================
// CUSTOM TOAST NOTIFICATION - HANYA SATU
// ============================================================
function showCustomToast(type, title, message) {
    document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());
    var toastDiv = document.createElement('div');
    toastDiv.className = 'custom-toast toast-' + type;
    toastDiv.innerHTML = '<div class="toast-icon"><i class="fas fa-' + (type === 'success' ? 'check-circle' :
            'exclamation-triangle') + '"></i></div><div class="toast-content"><div class="toast-title">' + title +
        '</div><div class="toast-message">' + message + '</div></div><button class="toast-close">&times;</button>';
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