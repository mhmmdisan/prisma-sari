@extends('layouts.admin')

@section('title', 'Edit Pesanan #' . $pesanan->nomor_pesanan)

@section('content')
<div class="edit-container">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold mb-1">
                <i class="bi bi-pencil-square me-2" style="color: #ffc107;"></i>
                Edit Pesanan
            </h2>
            <p class="text-muted mb-0">#{{ $pesanan->nomor_pesanan }}</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn-back rounded-pill px-4 py-2">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Detail
            </a>
        </div>
    </div>

    <!-- Alert Error - Custom -->
    @if($errors->any())
    <div class="alert-custom alert-error-custom mb-4">
        <i class="fas fa-exclamation-triangle"></i>
        <div class="alert-content">
            <strong>Terjadi kesalahan!</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="alert-close" onclick="this.closest('.alert-custom').remove()">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-custom alert-error-custom mb-4">
        <i class="fas fa-exclamation-triangle"></i>
        <div class="alert-content">{{ session('error') }}</div>
        <button type="button" class="alert-close" onclick="this.closest('.alert-custom').remove()">&times;</button>
    </div>
    @endif

    <div class="card-edit card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
            <div class="d-flex align-items-center gap-2">
                <div class="header-icon">
                    <i class="bi bi-pencil"></i>
                </div>
                <h5 class="mb-0 fw-bold" style="color: #1b5e20;">Form Edit Pesanan</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.pesanan.update', $pesanan->id) }}" enctype="multipart/form-data"
                id="formEditPesanan" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Pelanggan -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold" id="label_user_id">
                            <i class="bi bi-person text-success me-1"></i> Pelanggan
                        </label>
                        <select name="user_id" id="user_id" class="form-select rounded-3" required>
                            <option value="">Pilih Pelanggan</option>
                            @foreach($pelanggan as $user)
                            <option value="{{ $user->id }}" {{ $pesanan->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} - {{ $user->email }}
                            </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback-custom" id="error_user_id"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Pelanggan harus dipilih!
                        </div>
                    </div>

                    <!-- Tanggal Pengambilan (Flatpickr Date + Time) -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_tanggal_pengambilan">
                            <i class="bi bi-calendar-event text-warning me-1"></i> Tanggal Pengambilan
                        </label>
                        <input type="text" name="tanggal_pengambilan" id="tanggal_pengambilan"
                            class="form-control rounded-3 date-time-picker" required
                            value="{{ \Carbon\Carbon::parse($pesanan->tanggal_pengambilan)->format('Y-m-d H:i:s') }}">
                        <div class="invalid-feedback-custom" id="error_tanggal_pengambilan"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Tanggal pengambilan harus diisi!
                        </div>
                    </div>

                    <!-- Status Pesanan -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_status">
                            <i class="bi bi-tag text-info me-1"></i> Status Pesanan
                        </label>
                        <select name="status" id="status" class="form-select rounded-3" required>
                            <option value="menunggu_pembayaran"
                                {{ $pesanan->status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran
                            </option>
                            <option value="diproses" {{ $pesanan->status == 'diproses' ? 'selected' : '' }}>Diproses
                            </option>
                            <option value="terlambat" {{ $pesanan->status == 'terlambat' ? 'selected' : '' }}>Terlambat
                            </option>
                            <option value="selesai" {{ $pesanan->status == 'selesai' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="dibatalkan" {{ $pesanan->status == 'dibatalkan' ? 'selected' : '' }}>
                                Dibatalkan</option>
                        </select>
                        <div class="invalid-feedback-custom" id="error_status"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Status pesanan harus dipilih!
                        </div>
                    </div>

                    <!-- Alamat Pengiriman -->
                    <div class="col-12">
                        <label class="form-label fw-semibold" id="label_alamat_pengiriman">
                            <i class="bi bi-geo-alt text-danger me-1"></i> Alamat Pengiriman
                        </label>
                        <textarea name="alamat_pengiriman" id="alamat_pengiriman" class="form-control rounded-3"
                            rows="2" required
                            placeholder="Masukkan alamat lengkap pengiriman">{{ $pesanan->alamat_pengiriman }}</textarea>
                        <div class="invalid-feedback-custom" id="error_alamat_pengiriman"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Alamat pengiriman harus diisi!
                        </div>
                    </div>

                    <!-- Status Pembayaran -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_status_pembayaran">
                            <i class="bi bi-credit-card text-primary me-1"></i> Status Pembayaran
                        </label>
                        <select name="status_pembayaran" id="status_pembayaran" class="form-select rounded-3" required>
                            <option value="belum_bayar"
                                {{ $pesanan->status_pembayaran == 'belum_bayar' ? 'selected' : '' }}>Belum Dibayar
                            </option>
                            <option value="menunggu_konfirmasi"
                                {{ $pesanan->status_pembayaran == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu
                                Konfirmasi</option>
                            <option value="lunas" {{ $pesanan->status_pembayaran == 'lunas' ? 'selected' : '' }}>Lunas
                            </option>
                        </select>
                        <div class="invalid-feedback-custom" id="error_status_pembayaran"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Status pembayaran harus dipilih!
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_id_metode_pembayaran">
                            <i class="bi bi-bank2 text-success me-1"></i> Metode Pembayaran
                        </label>
                        <select name="id_metode_pembayaran" id="id_metode_pembayaran" class="form-select rounded-3">
                            <option value="">Tidak ada / Belum dipilih</option>
                            @foreach($metodePembayaran as $bank)
                            <option value="{{ $bank->id }}"
                                {{ $pesanan->id_metode_pembayaran == $bank->id ? 'selected' : '' }}>
                                {{ $bank->nama_bank }} - {{ $bank->nomor_rekening }} (a.n. {{ $bank->atas_nama }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Detail Pesanan -->
                    <div class="col-12">
                        <label class="form-label fw-semibold mb-2">
                            <i class="bi bi-receipt me-1"></i> Detail Pesanan
                        </label>
                        <div id="detail-pesanan-container">
                            @foreach($pesanan->detailPesanan as $index => $item)
                            <div class="detail-item-card mb-3" id="item-{{ $index }}">
                                <div class="detail-item-header">
                                    <span class="detail-item-number">Item #{{ $loop->iteration }}</span>
                                    <button type="button" class="btn-hapus-item" title="Hapus Item">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>

                                {{-- 🔥 TAMBAHKAN INI: hidden input untuk menyimpan ID custom snackbox --}}
                                @if($item->customSnackbox)
                                    <input type="hidden"
                                           name="detail_pesanan[{{ $index }}][custom_snackbox_id]"
                                           value="{{ $item->customSnackbox->id }}">
                                @endif

                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label small fw-semibold">Nama Produk</label>
                                        <input type="text" name="detail_pesanan[{{ $index }}][nama_item]"
                                            class="form-control rounded-3 nama-item" value="{{ $item->nama_item }}"
                                            required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold">Jumlah</label>
                                        <input type="number" name="detail_pesanan[{{ $index }}][jumlah]"
                                            class="form-control rounded-3 jumlah-item" value="{{ $item->jumlah }}"
                                            required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">Harga Satuan</label>
                                        <input type="number" name="detail_pesanan[{{ $index }}][harga_satuan]"
                                            class="form-control rounded-3 harga-item" value="{{ $item->harga_satuan }}"
                                            required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold">Subtotal</label>
                                        <input type="text" class="form-control rounded-3 subtotal-item" readonly
                                            style="background:#e8f5e9; font-weight:bold; color:#2e7d32;">
                                    </div>
                                </div>
                                @if($item->customSnackbox)
                                <div class="custom-snackbox-info mt-3">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="badge-snackbox"><i class="bi bi-box-seam"></i>
                                            {{ $item->customSnackbox->nama_ukuran }}</span>
                                        <span class="badge-snackbox-secondary">{{ $item->customSnackbox->jumlah_box }}
                                            box</span>
                                        <a href="{{ route('admin.custom-snackbox.edit', $item->customSnackbox->id) }}"
                                            class="btn-edit-snackbox">
                                            <i class="bi bi-pencil-square"></i> Edit Isian
                                        </a>
                                    </div>
                                    <div class="mt-2 small text-muted">
                                        <strong>Isi Snackbox:</strong>
                                        <ul class="mb-0 mt-1 ps-3">
                                            @foreach($item->customSnackbox->detail as $detail)
                                            <li>{{ $detail->produk->nama_produk ?? 'Produk tidak ditemukan' }}
                                                ({{ $detail->jumlah }} pcs)</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn-add-item" id="tambah-item">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Item
                        </button>
                    </div>

                    <!-- Total Harga -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-calculator text-primary me-1"></i> Total Harga
                        </label>
                        <div class="total-harga-wrapper">
                            <span class="total-harga-rupiah" id="total_harga_display">Rp 0</span>
                            <input type="hidden" name="total_harga" id="total_harga"
                                value="{{ $pesanan->total_harga }}">
                        </div>
                        <small class="text-muted">Total akan dihitung otomatis dari detail pesanan</small>
                    </div>

                    <!-- Bukti Pembayaran -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-image me-1"></i> Upload Bukti Pembayaran
                        </label>
                        <div class="upload-area">
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                                class="form-control rounded-3" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG. Maks: 2MB</small>
                            @if($pesanan->bukti_pembayaran)
                            <div class="mt-2">
                                <a href="{{ asset($pesanan->bukti_pembayaran) }}" target="_blank"
                                    class="btn-view-bukti">
                                    <i class="bi bi-eye me-1"></i> Lihat Bukti Saat Ini
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="col-12">
                        <div class="action-buttons">
                            <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn-cancel">
                                <i class="bi bi-x-circle me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn-update" id="btnSubmit">
                                <i class="bi bi-save me-2"></i> Update Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
/* ============================================ */
/* EDIT CONTAINER */
/* ============================================ */
.edit-container {
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
/* ALERT CUSTOM */
/* ============================================ */
.alert-custom {
    border-radius: 16px;
    padding: 14px 18px;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 12px;
    border: none;
    animation: slideIn 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.alert-custom .alert-close {
    margin-left: auto;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s ease;
    color: inherit;
}

.alert-custom .alert-close:hover {
    opacity: 1;
}

.alert-error-custom {
    background: linear-gradient(135deg, #ffebee, #ffcdd2);
    color: #c62828;
    border-left: 4px solid #dc3545;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ============================================ */
/* CARD EDIT */
/* ============================================ */
.card-edit {
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
/* CUSTOM SNACKBOX INFO */
/* ============================================ */
.custom-snackbox-info {
    background: white;
    border-radius: 12px;
    padding: 12px;
    margin-top: 12px;
    border-left: 3px solid #ffc107;
}

.badge-snackbox {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #2e7d32;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-snackbox-secondary {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    color: #e65100;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
}

.btn-edit-snackbox {
    background: linear-gradient(135deg, #e3f2fd, #bbdef5);
    color: #01579b;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-edit-snackbox:hover {
    background: linear-gradient(135deg, #0288d1, #01579b);
    color: white;
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
/* VIEW BUKTI BUTTON */
/* ============================================ */
.btn-view-bukti {
    background: linear-gradient(135deg, #e3f2fd, #bbdef5);
    color: #01579b;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-block;
}

.btn-view-bukti:hover {
    background: linear-gradient(135deg, #0288d1, #01579b);
    color: white;
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

.btn-update {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    border: none;
    color: #1b5e20;
    font-weight: 600;
    padding: 10px 28px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.btn-update:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
    color: #1b5e20;
}

/* ============================================ */
/* FLATPICKR CALENDAR STYLING */
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

/* Waktu (jam & menit) - warna teks hijau tua agar terlihat jelas */
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

.flatpickr-time .numInputWrapper .arrowUp:after,
.flatpickr-time .numInputWrapper .arrowDown:after {
    border-bottom-color: #1b5e20 !important;
    border-top-color: #1b5e20 !important;
}

/* AM/PM styling (jika menggunakan format 12 jam) */
.flatpickr-time .flatpickr-am-pm {
    color: #1b5e20 !important;
    font-weight: bold !important;
    background: #fff8e1 !important;
    border-radius: 8px !important;
}

.flatpickr-time .flatpickr-am-pm:hover {
    background: #ffc107 !important;
    color: #1b5e20 !important;
}

/* ============================================ */
/* CUSTOM TOAST CSS - DITARUH DI LUAR MEDIA QUERY */
/* ============================================ */
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

/* ============================================ */
/* RESPONSIVE */
/* ============================================ */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.3rem;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-cancel,
    .btn-update {
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
// ============================================================
// FLATPICKR DATE + TIME PICKER
// ============================================================
flatpickr("#tanggal_pengambilan", {
    locale: "id",
    enableTime: true,
    dateFormat: "Y-m-d H:i:S",
    altFormat: "l, j F Y H:i",
    altInput: true,
    allowInput: true,
    time_24hr: true,
    minuteIncrement: 1,
    minDate: "today"
});

// ============================================================
// FUNGSI HITUNG TOTAL
// ============================================================
function hitungTotal() {
    let total = 0;
    var items = document.querySelectorAll('.detail-item-card');
    for (var i = 0; i < items.length; i++) {
        var item = items[i];
        var jumlah = parseInt(item.querySelector('.jumlah-item').value) || 0;
        var harga = parseInt(item.querySelector('.harga-item').value) || 0;
        var subtotal = jumlah * harga;
        var subtotalInput = item.querySelector('.subtotal-item');
        if (subtotalInput) {
            subtotalInput.value = 'Rp ' + subtotal.toLocaleString('id-ID');
        }
        total = total + subtotal;
    }
    document.getElementById('total_harga').value = total;
    document.getElementById('total_harga_display').innerText = 'Rp ' + total.toLocaleString('id-ID');
}

// ============================================================
// VALIDASI & SUBMIT FORM AJAX
// ============================================================
document.getElementById('formEditPesanan')?.addEventListener('submit', function(e) {
    e.preventDefault();

    console.log('Form submit - AJAX dimulai'); // DEBUG

    var userId = document.getElementById('user_id');
    var tanggal = document.getElementById('tanggal_pengambilan');
    var alamat = document.getElementById('alamat_pengiriman');
    var status = document.getElementById('status');
    var statusPembayaran = document.getElementById('status_pembayaran');
    var errors = [];
    var isValid = true;

    // Reset error styling
    [userId, tanggal, alamat, status, statusPembayaran].forEach(field => {
        if (field) field.classList.remove('error');
    });
    document.querySelectorAll('.invalid-feedback-custom').forEach(el => el.style.display = 'none');

    // Validasi
    if (!userId.value) {
        userId.classList.add('error');
        document.getElementById('error_user_id').style.display = 'block';
        errors.push('Pelanggan harus dipilih');
        isValid = false;
    }

    if (!tanggal.value) {
        tanggal.classList.add('error');
        document.getElementById('error_tanggal_pengambilan').style.display = 'block';
        errors.push('Tanggal pengambilan harus diisi');
        isValid = false;
    }

    if (!alamat.value.trim()) {
        alamat.classList.add('error');
        document.getElementById('error_alamat_pengiriman').style.display = 'block';
        errors.push('Alamat pengiriman harus diisi');
        isValid = false;
    }

    if (!status.value) {
        status.classList.add('error');
        document.getElementById('error_status').style.display = 'block';
        errors.push('Status pesanan harus dipilih');
        isValid = false;
    }

    if (!statusPembayaran.value) {
        statusPembayaran.classList.add('error');
        document.getElementById('error_status_pembayaran').style.display = 'block';
        errors.push('Status pembayaran harus dipilih');
        isValid = false;
    }

    var detailItems = document.querySelectorAll('.detail-item-card');
    if (detailItems.length === 0) {
        showCustomToast('error', 'Validasi Gagal', 'Minimal harus ada 1 item pesanan!');
        return false;
    }

    if (!isValid && errors.length > 0) {
        showCustomToast('error', 'Validasi Gagal', errors.join(', '));
        var firstError = document.querySelector('.form-control.error, .form-select.error');
        if (firstError) firstError.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
        return false;
    }

    // Submit AJAX
    var formData = new FormData(this);
    var btnSubmit = document.getElementById('btnSubmit');
    var originalText = btnSubmit.innerHTML;

    btnSubmit.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Menyimpan...';
    btnSubmit.disabled = true;

    fetch('{{ route("admin.pesanan.update", $pesanan->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status); // DEBUG
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data); // DEBUG

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
            showCustomToast('error', 'Kesalahan Server', error.message || 'Terjadi kesalahan pada server');
            btnSubmit.innerHTML = originalText;
            btnSubmit.disabled = false;
        });
});

// ============================================================
// INISIALISASI
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - Edit Pesanan'); // DEBUG

    hitungTotal();

    function onInputChange() {
        hitungTotal();
    }

    document.querySelectorAll('.jumlah-item, .harga-item').forEach(input => {
        input.addEventListener('input', onInputChange);
    });

    var itemCount = parseInt('{{ $pesanan->detailPesanan->count() }}');
    var tambahBtn = document.getElementById('tambah-item');

    if (tambahBtn) {
        tambahBtn.addEventListener('click', function() {
            var container = document.getElementById('detail-pesanan-container');
            var newId = itemCount;
            var newRow = document.createElement('div');
            newRow.className = 'detail-item-card mb-3';
            newRow.innerHTML = `
                <div class="detail-item-header">
                    <span class="detail-item-number">Item #${itemCount + 1}</span>
                    <button type="button" class="btn-hapus-item" title="Hapus Item">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">Nama Produk</label>
                        <input type="text" name="detail_pesanan[${newId}][nama_item]" class="form-control rounded-3 nama-item" placeholder="Contoh: Nasi Kuning" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Jumlah</label>
                        <input type="number" name="detail_pesanan[${newId}][jumlah]" class="form-control rounded-3 jumlah-item" placeholder="0" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Harga Satuan</label>
                        <input type="number" name="detail_pesanan[${newId}][harga_satuan]" class="form-control rounded-3 harga-item" placeholder="0" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Subtotal</label>
                        <input type="text" class="form-control rounded-3 subtotal-item" readonly style="background:#e8f5e9; font-weight:bold; color:#2e7d32;">
                    </div>
                </div>
            `;
            container.appendChild(newRow);

            newRow.querySelector('.jumlah-item').addEventListener('input', onInputChange);
            newRow.querySelector('.harga-item').addEventListener('input', onInputChange);
            newRow.querySelector('.btn-hapus-item').addEventListener('click', function() {
                newRow.remove();
                hitungTotal();
            });
            itemCount++;
            hitungTotal();
        });
    }

    document.querySelectorAll('.btn-hapus-item').forEach(btn => {
        btn.addEventListener('click', function() {
            var itemDiv = this.closest('.detail-item-card');
            if (itemDiv) {
                itemDiv.remove();
                hitungTotal();
            }
        });
    });

    // Hilangkan error styling saat user mulai mengetik
    document.getElementById('user_id')?.addEventListener('change', function() {
        this.classList.remove('error');
        document.getElementById('error_user_id').style.display = 'none';
    });
    document.getElementById('tanggal_pengambilan')?.addEventListener('input', function() {
        this.classList.remove('error');
        document.getElementById('error_tanggal_pengambilan').style.display = 'none';
    });
    document.getElementById('alamat_pengiriman')?.addEventListener('input', function() {
        this.classList.remove('error');
        document.getElementById('error_alamat_pengiriman').style.display = 'none';
    });
    document.getElementById('status')?.addEventListener('change', function() {
        this.classList.remove('error');
        document.getElementById('error_status').style.display = 'none';
    });
    document.getElementById('status_pembayaran')?.addEventListener('change', function() {
        this.classList.remove('error');
        document.getElementById('error_status_pembayaran').style.display = 'none';
    });
});

// ============================================================
// CUSTOM TOAST NOTIFICATION
// ============================================================
function showCustomToast(type, title, message) {
    console.log('showCustomToast dipanggil:', type, title, message); // DEBUG

    document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());
    var toastDiv = document.createElement('div');
    toastDiv.className = 'custom-toast toast-' + type;

    var iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';

    toastDiv.innerHTML = `
        <div class="toast-icon"><i class="${iconClass}"></i></div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close">&times;</button>
    `;

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