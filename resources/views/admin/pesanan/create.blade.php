@extends('layouts.admin')

@section('title', 'Tambah Pesanan Manual (WhatsApp Order)')

@section('content')
<div class="create-order-container">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold mb-1">
                <i class="bi bi-whatsapp me-2" style="color: #25D366;"></i>
                Tambah Pesanan Manual
            </h2>
            <p class="text-muted mb-0">Tambah pesanan WhatsApp Custom Order</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <a href="{{ route('admin.pesanan.index') }}" class="btn-back rounded-pill px-4 py-2">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Pesanan
            </a>
        </div>
    </div>

    <div class="card-order card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
            <div class="d-flex align-items-center gap-2">
                <div class="header-icon">
                    <i class="bi bi-whatsapp"></i>
                </div>
                <h5 class="mb-0 fw-bold" style="color: #1b5e20;">Form Tambah Pesanan WhatsApp</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.pesanan.store-manual') }}" enctype="multipart/form-data"
                id="formPesanan" novalidate>
                @csrf

                <div class="row g-4">
                    <!-- Pelanggan -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold" id="label_pelanggan">
                            <i class="bi bi-person text-success me-1"></i> Pelanggan
                        </label>
                        <select name="user_id" id="pelangganSelect" class="form-select rounded-3" required
                            style="width: 100%;">
                            <option value="">Pilih atau cari pelanggan...</option>
                            @foreach($pelanggan as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}
                                ({{ $user->no_telepon ?? 'No WA belum diisi' }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1"><i class="bi bi-search me-1"></i> Ketik nama, email, atau
                            nomor WA untuk mencari</small>
                        <div class="invalid-feedback-custom" id="error_pelanggan"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Pelanggan harus dipilih!
                        </div>
                    </div>

                    <!-- Tanggal Pengambilan (Flatpickr Date + Time) -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_tanggal">
                            <i class="bi bi-calendar-event text-warning me-1"></i> Tanggal Pengambilan
                        </label>
                        <input type="text" name="tanggal_pengambilan" id="tanggal_pengambilan"
                            class="form-control rounded-3 date-time-picker" required
                            value="{{ date('Y-m-d H:i:s', strtotime('+2 days 05:00')) }}">
                        <div class="invalid-feedback-custom" id="error_tanggal"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Tanggal pengambilan harus diisi!
                        </div>
                    </div>

                    <!-- Alamat Pengiriman -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_alamat">
                            <i class="bi bi-geo-alt text-danger me-1"></i> Alamat Pengiriman
                        </label>
                        <textarea name="alamat_pengiriman" id="alamat_pengiriman" class="form-control rounded-3"
                            rows="2" required placeholder="Masukkan alamat lengkap pengiriman"></textarea>
                        <div class="invalid-feedback-custom" id="error_alamat"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Alamat pengiriman harus diisi!
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_metode">
                            <i class="bi bi-bank2 text-success me-1"></i> Metode Pembayaran
                        </label>
                        <select name="id_metode_pembayaran" id="id_metode_pembayaran" class="form-select rounded-3"
                            required>
                            <option value="">Pilih Bank</option>
                            @foreach($metodePembayaran as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->nama_bank }} - {{ $bank->nomor_rekening }} (a.n.
                                {{ $bank->atas_nama }})</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback-custom" id="error_metode"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Metode pembayaran harus dipilih!
                        </div>
                    </div>

                    <!-- Bukti Pembayaran -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_bukti">
                            <i class="bi bi-image me-1"></i> Upload Bukti Pembayaran
                        </label>
                        <div class="upload-area">
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                                class="form-control rounded-3" accept="image/*" required>
                            <small class="text-muted">Format: JPG, PNG. Maks: 2MB (Wajib diupload)</small>
                        </div>
                        <div class="invalid-feedback-custom" id="error_bukti"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Bukti pembayaran harus diupload!
                        </div>
                    </div>

                    <!-- Detail Pesanan -->
                    <div class="col-12">
                        <label class="form-label fw-semibold mb-2">
                            <i class="bi bi-receipt me-1"></i> Detail Pesanan
                        </label>
                        <div id="detail-pesanan-container">
                            <div class="detail-item-card mb-3" id="item-0">
                                <div class="detail-item-header">
                                    <span class="detail-item-number">Item #1</span>
                                    <button type="button" class="btn-hapus-item" style="display: none;"
                                        title="Hapus Item">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                                <div class="row g-3">
                                    <!-- KATEGORI (BARU) -->
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">Kategori</label>
                                        <select name="detail_pesanan[0][kategori_id]" class="form-select rounded-3"
                                            required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach($kategoriList as $kat)
                                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">Nama Produk</label>
                                        <input type="text" name="detail_pesanan[0][nama_item]"
                                            class="form-control rounded-3 nama-item" placeholder="Contoh: Nasi Kuning"
                                            required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold">Jumlah</label>
                                        <input type="text" name="detail_pesanan[0][jumlah]"
                                            class="form-control rounded-3 jumlah-item" placeholder="Contoh: 50"
                                            required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">Harga Satuan</label>
                                        <input type="text" name="detail_pesanan[0][harga_satuan]"
                                            class="form-control rounded-3 harga-item" placeholder="Contoh: 2000"
                                            required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold">Subtotal</label>
                                        <input type="text" class="form-control rounded-3 subtotal-item" readonly
                                            style="background:#e8f5e9; font-weight:bold; color:#2e7d32;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-add-item" id="tambah-item">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Item
                        </button>
                        <small class="text-muted d-block mt-2"><i class="bi bi-info-circle me-1"></i> Tambahkan setiap
                            item pesanan (produk, jumlah, harga)</small>
                    </div>

                    <!-- Total Harga -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-calculator text-primary me-1"></i> Total Harga
                        </label>
                        <div class="total-harga-wrapper">
                            <span class="total-harga-rupiah" id="total_harga_display">Rp 0</span>
                            <input type="hidden" name="total_harga" id="total_harga" value="0">
                        </div>
                        <small class="text-muted">Total akan dihitung otomatis dari detail pesanan</small>
                    </div>

                    <!-- Informasi -->
                    <div class="col-12">
                        <div class="info-custom">
                            <i class="bi bi-info-circle-fill text-warning me-2"></i>
                            <strong>Catatan:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Pesanan akan otomatis berstatus <strong>"Diproses"</strong> dan
                                    <strong>"Lunas"</strong>
                                </li>
                                <li>Pelanggan dapat melihat pesanan ini di riwayat pesanan mereka</li>
                                <li>Pesanan akan otomatis masuk ke jadwal produksi</li>
                                <li>Bukti pembayaran <strong>WAJIB diupload</strong> sebagai arsip admin</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="col-12">
                        <div class="action-buttons">
                            <a href="{{ route('admin.pesanan.index') }}" class="btn-cancel">
                                <i class="bi bi-x-circle me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn-save" id="btnSubmit">
                                <i class="bi bi-save me-2"></i> Simpan Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS untuk Custom Toast Notification (sama seperti sebelumnya, tidak diubah) -->
<style>
/* ... (CSS yang sama persis seperti di file lama, tidak perlu diubah) ... */
</style>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
/* ============================================ */
/* CREATE ORDER CONTAINER */
/* ============================================ */
.create-order-container {
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
/* BUTTON BACK */
/* ============================================ */
.btn-back {
    background: white;
    border: 2px solid #2e7d32;
    color: #2e7d32;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-back:hover {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
    border-color: transparent;
    transform: translateX(-3px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);
}

/* ============================================ */
/* CARD ORDER */
/* ============================================ */
.card-order {
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

/* ============================================ */
/* FORM CONTROL */
/* ============================================ */
.form-control,
.form-select {
    border-radius: 12px;
    border: 1.5px solid #e5e7eb;
    padding: 10px 14px;
    transition: all 0.3s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    outline: none;
}

.form-control.error,
.form-select.error {
    border-color: #dc3545 !important;
    background-color: #fff5f5 !important;
}

/* ============================================ */
/* SELECT2 CUSTOM STYLING */
/* ============================================ */
.select2-container--bootstrap-5 .select2-selection {
    min-height: 46px;
    border-radius: 12px;
    border: 1.5px solid #e5e7eb;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    line-height: 42px;
    padding-left: 14px;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
    height: 42px;
}

.select2-container--bootstrap-5 .select2-selection--single:hover {
    border-color: #ffc107;
}

.select2-container--bootstrap-5.select2-container--focus .select2-selection--single {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

/* ============================================ */
/* DETAIL ITEM CARD */
/* ============================================ */
.detail-item-card {
    background: #f8f9fa;
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 16px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.detail-item-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.detail-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e5e7eb;
}

.detail-item-number {
    font-weight: 600;
    color: #2e7d32;
    font-size: 0.85rem;
}

.btn-hapus-item {
    background: linear-gradient(135deg, #ffebee, #ffcdd2);
    border: none;
    color: #dc3545;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-hapus-item:hover {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    transform: scale(1.02);
}

/* ============================================ */
/* ADD ITEM BUTTON */
/* ============================================ */
.btn-add-item {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border: 1px solid #2e7d32;
    color: #2e7d32;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-add-item:hover {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
    transform: translateY(-2px);
}

/* ============================================ */
/* TOTAL HARGA */
/* ============================================ */
.total-harga-wrapper {
    background: #e8f5e9;
    border-radius: 12px;
    padding: 12px 16px;
    border: 1.5px solid #2e7d32;
}

.total-harga-rupiah {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2e7d32;
    display: block;
}

/* ============================================ */
/* INFO CUSTOM */
/* ============================================ */
.info-custom {
    background: linear-gradient(135deg, #e3f2fd, #bbdef5);
    border-radius: 16px;
    padding: 16px 20px;
    color: #01579b;
    border-left: 4px solid #0288d1;
}

.info-custom ul {
    padding-left: 20px;
}

.info-custom li {
    margin-bottom: 4px;
}

/* ============================================ */
/* ACTION BUTTONS */
/* ============================================ */
.action-buttons {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #f0f0f0;
}

.btn-cancel {
    background: white;
    border: 2px solid #6c757d;
    color: #6c757d;
    padding: 10px 24px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-cancel:hover {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
}

.btn-save {
    background: linear-gradient(135deg, #25D366, #128C7E);
    border: none;
    color: white;
    font-weight: 600;
    padding: 10px 28px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
    color: white;
}

.btn-save:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* ============================================ */
/* FLATPICKR CALENDAR & TIME PICKER STYLING */
/* ============================================ */
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
.flatpickr-time .flatpickr-time-separator,
.flatpickr-time .numInput {
    color: #1b5e20 !important;
    font-weight: bold !important;
    font-size: 1rem !important;
}

.flatpickr-time .numInputWrapper {
    background: #fff8e1 !important;
    border-radius: 8px !important;
}

.flatpickr-time .numInputWrapper:hover {
    background: #ffecb3 !important;
}

.flatpickr-time .numInputWrapper .arrowUp,
.flatpickr-time .numInputWrapper .arrowDown {
    background-color: #ffc107 !important;
    border-radius: 4px !important;
}

.flatpickr-time .numInputWrapper .arrowUp:hover,
.flatpickr-time .numInputWrapper .arrowDown:hover {
    background-color: #2e7d32 !important;
}

.flatpickr-day:active {
    transform: scale(0.95);
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.3rem;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-cancel,
    .btn-save {
        width: 100%;
        text-align: center;
    }

    .detail-item-header {
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
$(document).ready(function() {
    // FLATPICKR
    flatpickr("#tanggal_pengambilan", {
        locale: "id",
        enableTime: true,
        dateFormat: "Y-m-d H:i:S",
        altFormat: "l, j F Y H:i",
        altInput: true,
        allowInput: true,
        time_24hr: true,
        minuteIncrement: 1,
        minDate: new Date().fp_incr(2)
    });

    // SELECT2
    $('#pelangganSelect').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih atau cari pelanggan...',
        allowClear: true,
        width: '100%',
        dropdownParent: $('.card-body')
    });

    // HITUNG TOTAL
    function hitungTotal() {
        let total = 0;
        $('.detail-item-card').each(function() {
            let jumlah = parseInt($(this).find('.jumlah-item').val()) || 0;
            let harga = parseInt($(this).find('.harga-item').val()) || 0;
            let subtotal = jumlah * harga;
            $(this).find('.subtotal-item').val(formatRupiah(subtotal));
            total += subtotal;
        });
        $('#total_harga').val(total);
        $('#total_harga_display').text('Rp ' + formatRupiah(total));
    }

    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    $(document).on('input', '.jumlah-item, .harga-item', function() {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
        hitungTotal();
    });

    // TAMBAH & HAPUS ITEM (dengan kategori)
    let itemCount = 1;

    $('#tambah-item').click(function() {
        const newRow = `
            <div class="detail-item-card mb-3" id="item-${itemCount}">
                <div class="detail-item-header">
                    <span class="detail-item-number">Item #${itemCount + 1}</span>
                    <button type="button" class="btn-hapus-item" title="Hapus Item">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Kategori</label>
                        <select name="detail_pesanan[${itemCount}][kategori_id]" class="form-select rounded-3" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Nama Produk</label>
                        <input type="text" name="detail_pesanan[${itemCount}][nama_item]" class="form-control rounded-3 nama-item" placeholder="Contoh: Nasi Kuning" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Jumlah</label>
                        <input type="text" name="detail_pesanan[${itemCount}][jumlah]" class="form-control rounded-3 jumlah-item" placeholder="Contoh: 50" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Harga Satuan</label>
                        <input type="text" name="detail_pesanan[${itemCount}][harga_satuan]" class="form-control rounded-3 harga-item" placeholder="Contoh: 2000" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Subtotal</label>
                        <input type="text" class="form-control rounded-3 subtotal-item" readonly style="background:#e8f5e9; font-weight:bold; color:#2e7d32;">
                    </div>
                </div>
            </div>
        `;
        $('#detail-pesanan-container').append(newRow);
        itemCount++;
        hitungTotal();
    });

    $(document).on('click', '.btn-hapus-item', function() {
        $(this).closest('.detail-item-card').remove();
        hitungTotal();
    });

    // Tampilkan/hapus tombol hapus
    if ($('.detail-item-card').length === 1) {
        $('.btn-hapus-item').hide();
    }
    $(document).on('click', '#tambah-item', function() {
        if ($('.detail-item-card').length > 1) $('.btn-hapus-item').show();
    });
    $(document).on('click', '.btn-hapus-item', function() {
        if ($('.detail-item-card').length === 1) $('.btn-hapus-item').hide();
    });

    // VALIDASI & SUBMIT (sama seperti sebelumnya, hanya tambahkan validasi untuk kategori? tidak wajib karena required)
    $('#formPesanan').on('submit', function(e) {
        e.preventDefault();
        let isValid = true;
        let errors = [];

        // Reset error
        $('.form-control, .form-select').removeClass('error');
        $('.invalid-feedback-custom').hide();

        if (!$('#pelangganSelect').val()) {
            $('#pelangganSelect').closest('.select2-container').find('.select2-selection').addClass(
                'error');
            $('#error_pelanggan').show();
            errors.push('Pelanggan harus dipilih');
            isValid = false;
        }
        if (!$('#tanggal_pengambilan').val()) {
            $('#tanggal_pengambilan').addClass('error');
            $('#error_tanggal').show();
            errors.push('Tanggal pengambilan harus diisi');
            isValid = false;
        }
        if (!$('#alamat_pengiriman').val().trim()) {
            $('#alamat_pengiriman').addClass('error');
            $('#error_alamat').show();
            errors.push('Alamat pengiriman harus diisi');
            isValid = false;
        }
        if (!$('#id_metode_pembayaran').val()) {
            $('#id_metode_pembayaran').addClass('error');
            $('#error_metode').show();
            errors.push('Metode pembayaran harus dipilih');
            isValid = false;
        }
        let buktiFile = $('#bukti_pembayaran')[0].files[0];
        if (!buktiFile) {
            $('#bukti_pembayaran').addClass('error');
            $('#error_bukti').show();
            errors.push('Bukti pembayaran harus diupload');
            isValid = false;
        } else {
            let validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(buktiFile.type)) {
                $('#bukti_pembayaran').addClass('error');
                $('#error_bukti').show().html(
                    '<i class="bi bi-exclamation-circle me-1"></i> Format file harus JPG, JPEG, atau PNG!'
                    );
                errors.push('Format file harus JPG, JPEG, atau PNG');
                isValid = false;
            }
            if (buktiFile.size > 2 * 1024 * 1024) {
                $('#bukti_pembayaran').addClass('error');
                $('#error_bukti').show().html(
                    '<i class="bi bi-exclamation-circle me-1"></i> Ukuran file maksimal 2MB!');
                errors.push('Ukuran file maksimal 2MB');
                isValid = false;
            }
        }

        let hasValidItem = false;
        $('.detail-item-card').each(function() {
            let kategori = $(this).find('select[name$="[kategori_id]"]').val();
            let nama = $(this).find('.nama-item').val();
            let jumlah = $(this).find('.jumlah-item').val();
            let harga = $(this).find('.harga-item').val();
            if (kategori && nama && jumlah && harga) {
                hasValidItem = true;
            }
        });
        if (!hasValidItem) {
            showCustomToast('error', 'Validasi Gagal',
                'Minimal harus ada 1 item pesanan dengan kategori, nama, jumlah, dan harga lengkap!'
                );
            return false;
        }

        if (!isValid) {
            showCustomToast('error', 'Validasi Gagal', errors.join(', '));
            let firstError = $('.error').first();
            if (firstError.length) firstError[0].scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            return false;
        }

        // Submit AJAX
        var formData = new FormData(this);
        var btnSubmit = $('#btnSubmit');
        var originalText = btnSubmit.html();
        btnSubmit.html('<i class="bi bi-hourglass-split me-2"></i> Menyimpan...');
        btnSubmit.prop('disabled', true);

        fetch('{{ route("admin.pesanan.store-manual") }}', {
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
                    btnSubmit.html(originalText);
                    btnSubmit.prop('disabled', false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showCustomToast('error', 'Kesalahan Server', 'Terjadi kesalahan pada server');
                btnSubmit.html(originalText);
                btnSubmit.prop('disabled', false);
            });
    });

    // Hilangkan error saat input berubah
    $('#pelangganSelect').on('change', function() {
        $(this).closest('.select2-container').find('.select2-selection').removeClass('error');
        $('#error_pelanggan').hide();
    });
    $('#tanggal_pengambilan').on('input', function() {
        $(this).removeClass('error');
        $('#error_tanggal').hide();
    });
    $('#alamat_pengiriman').on('input', function() {
        $(this).removeClass('error');
        $('#error_alamat').hide();
    });
    $('#id_metode_pembayaran').on('change', function() {
        $(this).removeClass('error');
        $('#error_metode').hide();
    });
    $('#bukti_pembayaran').on('change', function() {
        $(this).removeClass('error');
        $('#error_bukti').hide();
    });

    hitungTotal();
});

function showCustomToast(type, title, message) {
    document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());
    var toastDiv = document.createElement('div');
    toastDiv.className = 'custom-toast toast-' + type;
    toastDiv.innerHTML = '<div class="toast-icon"><i class="fas fa-' + (type === 'success' ? 'check-circle' :
            'exclamation-triangle') + '"></i></div>' +
        '<div class="toast-content"><div class="toast-title">' + title + '</div><div class="toast-message">' + message +
        '</div></div>' +
        '<button class="toast-close">&times;</button>';
    toastDiv.querySelector('.toast-close').onclick = () => toastDiv.remove();
    document.body.appendChild(toastDiv);
    setTimeout(() => toastDiv.classList.add('show'), 10);
    setTimeout(() => {
        if (toastDiv.parentNode) {
            toastDiv.classList.remove('show');
            setTimeout(() => toastDiv.remove(), 300);
        }
    }, 3000);
}
</script>
@endpush
@endsection