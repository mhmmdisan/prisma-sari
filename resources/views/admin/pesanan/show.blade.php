@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $pesanan->nomor_pesanan)

@section('content')
<div class="detail-container">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold mb-1">
                <i class="bi bi-receipt me-2" style="color: #ffc107;"></i>
                Detail Pesanan
            </h2>
            <p class="text-muted mb-0">#{{ $pesanan->nomor_pesanan }}</p>
        </div>
        <div class="mt-2 mt-sm-0">
            @if(request()->get('from') == 'jadwal')
            <a href="{{ route('admin.jadwal-produksi.index') }}" class="btn-back rounded-pill px-4 py-2">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Jadwal
            </a>
            @else
            <a href="{{ route('admin.pesanan.index') }}" class="btn-back rounded-pill px-4 py-2">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Pesanan
            </a>
            @endif
        </div>
    </div>

    <!-- Alert WA Custom Order -->
    @if($pesanan->is_whatsapp_order)
    <div class="alert-wa-custom mb-4">
        <button type="button" class="alert-wa-close"
            onclick="this.closest('.alert-wa-custom').remove()">&times;</button>
        <div class="d-flex align-items-center">
            <i class="bi bi-whatsapp fs-2 me-3 text-white"></i>
            <div>
                <h5 class="mb-1 text-white"><i class="bi bi-whatsapp"></i> Informasi Custom Order</h5>
                <p class="mb-0 text-white">Pesanan ini berasal dari WhatsApp Custom Order.</p>
                <small class="text-white-50 d-block mt-1">Pesanan ini sudah lunas dan langsung masuk jadwal
                    produksi.</small>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <!-- Kolom Kiri: Informasi Pesanan -->
        <div class="col-lg-6">
            <div class="card-info card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                    <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                        <i class="bi bi-info-circle me-2"></i> Informasi Pesanan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-upc-scan text-success"></i> Nomor Pesanan</div>
                            <div class="info-value fw-bold text-success">{{ $pesanan->nomor_pesanan }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-person text-success"></i> Pelanggan</div>
                            <div class="info-value">{{ $pesanan->user->name ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-calendar-plus text-success"></i> Tanggal Pesan</div>
                            <div class="info-value">
                                {{ \Carbon\Carbon::parse($pesanan->created_at)->locale('id')->translatedFormat('d F Y H:i') }}
                                WIB
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-calendar-event text-warning"></i> Tanggal
                                Pengambilan</div>
                            <div class="info-value">
                                {{ $pesanan->tanggal_pengambilan ? \Carbon\Carbon::parse($pesanan->tanggal_pengambilan)->locale('id')->translatedFormat('d F Y H:i') : '-' }}
                                WIB
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-geo-alt text-danger"></i> Alamat Pengiriman</div>
                            <div class="info-value">{{ $pesanan->alamat_pengiriman }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-pencil-square text-info"></i> Catatan</div>
                            <div class="info-value">{{ $pesanan->catatan_pesanan ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Status & Pembayaran -->
        <div class="col-lg-6">
            <div class="card-info card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                    <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                        <i class="bi bi-credit-card me-2"></i> Status & Pembayaran
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-tag text-warning"></i> Status Pesanan</div>
                            <div class="info-value">
                                <span class="badge-status {{ $pesanan->status_badge['class'] ?? 'bg-secondary' }}">
                                    {{ $pesanan->status_badge['label'] ?? ucfirst($pesanan->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-credit-card text-info"></i> Status Pembayaran</div>
                            <div class="info-value">
                                <span
                                    class="badge-status {{ $pesanan->status_pembayaran_badge['class'] ?? 'bg-secondary' }}">
                                    {{ $pesanan->status_pembayaran_badge['label'] ?? ucfirst($pesanan->status_pembayaran) }}
                                </span>
                            </div>
                        </div>

                        @if($pesanan->id_metode_pembayaran && $pesanan->metodePembayaran)
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-bank2 text-success"></i> Bank Tujuan</div>
                            <div class="info-value">
                                <strong>{{ $pesanan->metodePembayaran->nama_bank }}</strong><br>
                                <small>No. Rekening: {{ $pesanan->metodePembayaran->nomor_rekening }}</small><br>
                                <small>a.n. {{ $pesanan->metodePembayaran->atas_nama }}</small>
                            </div>
                        </div>
                        @endif

                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-calculator text-primary"></i> Total Harga</div>
                            <div class="info-value">
                                <span class="total-price">Rp
                                    {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if($pesanan->bukti_pembayaran)
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-image text-info"></i> Bukti Pembayaran</div>
                            <div class="info-value">
                                @php
                                    $buktiPath = $pesanan->bukti_pembayaran;

                                    // Jika path dimulai dengan 'public/', hilangkan prefix
                                    if (str_starts_with($buktiPath, 'public/')) {
                                        $buktiPath = str_replace('public/', '', $buktiPath);
                                    }

                                    // Jika belum ada prefix 'storage/', tambahkan
                                    if (!str_starts_with($buktiPath, 'storage/')) {
                                        $buktiPath = 'storage/' . $buktiPath;
                                    }

                                    // Cek apakah file benar-benar ada
                                    $fileExists = file_exists(public_path($buktiPath));
                                @endphp

                                @if($fileExists)
                                <div class="d-flex gap-2">
                                    <a href="{{ asset($buktiPath) }}" target="_blank" class="btn-outline-info-custom">
                                        <i class="bi bi-eye"></i> Lihat Bukti
                                    </a>
                                    <a href="{{ asset($buktiPath) }}" download class="btn-outline-secondary-custom">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                                @else
                                <div class="alert-warning-custom">
                                    <i class="bi bi-exclamation-triangle me-2"></i> File bukti pembayaran tidak
                                    ditemukan.
                                </div>
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-image text-muted"></i> Bukti Pembayaran</div>
                            <div class="info-value text-muted">
                                <i class="bi bi-info-circle me-1"></i> Belum ada bukti pembayaran
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Item Pesanan -->
    <div class="card-table card border-0 rounded-4 shadow-sm mt-4">
        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
            <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                <i class="bi bi-box-seam me-2"></i> Item Pesanan
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Harga Satuan</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan->detailPesanan as $item)
                        <tr class="item-row">
                            <td>
                                <strong class="item-name">{{ $item->nama_item }}</strong>
                                @if($item->customSnackbox)
                                <div class="custom-box-info mt-2">
                                    <div class="badge-box mb-1">
                                        <span class="badge-custom-box">{{ $item->customSnackbox->nama_ukuran }}</span>
                                        <span class="badge-custom-box-secondary">{{ $item->customSnackbox->jumlah_box }}
                                            box</span>
                                    </div>
                                    <div class="small text-muted">
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
                            </td>
                            <td class="text-center">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-end text-primary fw-bold">Rp
                                {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-active">
                        <tr>
                            <th colspan="3" class="text-end">Total</th>
                            <th class="text-end text-success fs-5">Rp
                                {{ number_format($pesanan->total_harga, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Aksi Admin -->
    <div class="card-actions card border-0 rounded-4 shadow-sm mt-4">
        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
            <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                <i class="bi bi-gear me-2"></i> Aksi Admin
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <a href="{{ route('admin.pesanan.edit', $pesanan->id) }}" class="btn-edit w-100">
                        <i class="bi bi-pencil-square me-2"></i> Edit Pesanan
                    </a>
                </div>

                @if($pesanan->status_pembayaran == 'menunggu_konfirmasi')
                <div class="col-md-6">
                    <form method="POST" action="{{ route('admin.pesanan.verifikasi', $pesanan->id) }}"
                        id="formVerifikasi">
                        @csrf
                        <button type="button" id="btnVerifikasi" class="btn-verify w-100">
                            <i class="bi bi-check-circle me-2"></i> Verifikasi Pembayaran
                        </button>
                    </form>
                </div>
                @endif

                <div class="col-md-12">
                    <form method="POST" action="{{ route('admin.pesanan.update-status', $pesanan->id) }}"
                        id="formUpdateStatus">
                        @csrf
                        @method('PUT')
                        <div class="update-status-group">
                            <select name="status" id="statusSelect" class="status-select">
                                <option value="menunggu_pembayaran"
                                    {{ $pesanan->status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu
                                    Pembayaran</option>
                                <option value="diproses" {{ $pesanan->status == 'diproses' ? 'selected' : '' }}>Diproses
                                </option>
                                <option value="terlambat" {{ $pesanan->status == 'terlambat' ? 'selected' : '' }}>
                                    Terlambat ⚠️</option>
                                <option value="selesai" {{ $pesanan->status == 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="dibatalkan" {{ $pesanan->status == 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                            <button type="button" id="btnUpdateStatus" class="btn-update-status">
                                <i class="bi bi-arrow-repeat me-2"></i> Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI VERIFIKASI PEMBAYARAN -->
<div id="verifikasiModal"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 100000; margin: 0; padding: 0;">
    <div
        style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 20px; box-sizing: border-box;">
        <div
            style="background: white; border-radius: 28px; max-width: 400px; width: 100%; margin: 0 auto; box-shadow: 0 30px 60px rgba(0,0,0,0.4); overflow: hidden;">
            <div
                style="background: linear-gradient(135deg, #2e7d32, #1b5e20); color: white; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; font-size: 1rem; font-weight: 600;">
                    <i class="bi bi-check-circle me-2"></i> Konfirmasi Verifikasi
                </h5>
                <button type="button" onclick="closeVerifikasiModal()"
                    style="background: rgba(255,255,255,0.2); border: none; color: white; width: 30px; height: 30px; border-radius: 50%; font-size: 18px; cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 24px; text-align: center;">
                <i class="bi bi-shield-check" style="font-size: 60px; color: #2e7d32;"></i>
                <h5 class="mt-3 fw-bold">Verifikasi Pembayaran</h5>
                <p class="text-muted mb-2">Apakah Anda yakin ingin memverifikasi pembayaran pesanan ini?</p>
                <p class="text-muted small">Setelah diverifikasi, status pesanan akan berubah menjadi
                    <strong>"Diproses"</strong>.
                </p>
                <div class="d-flex gap-3 justify-content-center mt-4">
                    <button type="button" onclick="closeVerifikasiModal()"
                        class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="button" id="confirmVerifikasiBtn" class="btn btn-success rounded-pill px-4"
                        style="background: linear-gradient(135deg, #2e7d32, #1b5e20); border: none;">
                        <i class="bi bi-check-circle me-1"></i> Ya, Verifikasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI UPDATE STATUS -->
<div id="updateStatusModal"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 100000; margin: 0; padding: 0;">
    <div
        style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 20px; box-sizing: border-box;">
        <div
            style="background: white; border-radius: 28px; max-width: 400px; width: 100%; margin: 0 auto; box-shadow: 0 30px 60px rgba(0,0,0,0.4); overflow: hidden;">
            <div
                style="background: linear-gradient(135deg, #ffc107, #ffb300); color: #1b5e20; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; font-size: 1rem; font-weight: 600;">
                    <i class="bi bi-arrow-repeat me-2"></i> Konfirmasi Update Status
                </h5>
                <button type="button" onclick="closeUpdateStatusModal()"
                    style="background: rgba(0,0,0,0.1); border: none; color: #1b5e20; width: 30px; height: 30px; border-radius: 50%; font-size: 18px; cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 24px; text-align: center;">
                <i class="bi bi-question-circle" style="font-size: 60px; color: #ffc107;"></i>
                <h5 class="mt-3 fw-bold">Ubah Status Pesanan</h5>
                <p class="text-muted mb-2">Apakah Anda yakin ingin mengubah status pesanan ini menjadi:</p>
                <p class="fw-bold fs-5" id="selectedStatusText" style="color: #2e7d32;"></p>
                <div class="d-flex gap-3 justify-content-center mt-4">
                    <button type="button" onclick="closeUpdateStatusModal()"
                        class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="button" id="confirmUpdateStatusBtn" class="btn btn-warning rounded-pill px-4"
                        style="background: linear-gradient(135deg, #ffc107, #ffb300); border: none; color: #1b5e20; font-weight: 600;">
                        <i class="bi bi-check-circle me-1"></i> Ya, Update
                    </button>
                </div>
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

.card-info,
.card-table,
.card-actions {
    overflow: hidden;
}

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
    width: 160px;
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

.badge-status {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge-status.bg-success {
    background: linear-gradient(135deg, #198754, #157347) !important;
}

.badge-status.bg-warning {
    background: linear-gradient(135deg, #f57c00, #ef6c00) !important;
    color: white;
}

.badge-status.bg-danger {
    background: linear-gradient(135deg, #dc3545, #c82333) !important;
}

.badge-status.bg-primary {
    background: linear-gradient(135deg, #0d6efd, #0b5ed7) !important;
}

.badge-status.bg-info {
    background: linear-gradient(135deg, #0dcaf0, #0bb5d8) !important;
    color: black;
}

.badge-status.bg-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268) !important;
}

.total-price {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2e7d32;
}

.custom-box-info {
    margin-top: 8px;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 12px;
}

.badge-custom-box {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #2e7d32;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-custom-box-secondary {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    color: #e65100;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.btn-edit {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    border: none;
    color: #1b5e20;
    font-weight: 600;
    padding: 12px 20px;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    text-align: center;
}

.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
    color: #1b5e20;
}

.btn-verify {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border: none;
    color: white;
    font-weight: 600;
    padding: 12px 20px;
    border-radius: 50px;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-verify:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
}

.update-status-group {
    display: flex;
    gap: 12px;
}

.status-select {
    flex: 1;
    padding: 12px 16px;
    border: 1.5px solid #e5e7eb;
    border-radius: 50px;
    font-size: 0.9rem;
    color: #333;
    background: white;
    transition: all 0.3s ease;
    cursor: pointer;
}

.status-select:hover,
.status-select:focus {
    border-color: #ffc107;
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.btn-update-status {
    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    border: none;
    color: white;
    font-weight: 600;
    padding: 12px 24px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.btn-update-status:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

.btn-outline-info-custom {
    background: linear-gradient(135deg, #e3f2fd, #bbdef5);
    color: #01579b;
    padding: 6px 14px;
    border-radius: 50px;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-info-custom:hover {
    background: linear-gradient(135deg, #0288d1, #01579b);
    color: white;
}

.btn-outline-secondary-custom {
    background: linear-gradient(135deg, #f5f5f5, #eeeeee);
    color: #616161;
    padding: 6px 14px;
    border-radius: 50px;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-secondary-custom:hover {
    background: linear-gradient(135deg, #616161, #424242);
    color: white;
}

.alert-wa-custom {
    position: relative;
    background: linear-gradient(135deg, #25D366, #128C7E);
    border-radius: 16px;
    padding: 15px 20px;
    border: none;
    box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
}

.alert-wa-custom .alert-wa-close {
    position: absolute;
    top: 12px;
    right: 15px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    font-size: 18px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.alert-wa-custom .alert-wa-close:hover {
    background: rgba(255, 255, 255, 0.3);
}

.alert-warning-custom {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    color: #e65100;
    padding: 8px 12px;
    border-radius: 12px;
    font-size: 0.8rem;
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

.item-row:hover {
    background: #fff8e1;
}

.item-name {
    color: #1b5e20;
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

    .update-status-group {
        flex-direction: column;
    }

    .status-select,
    .btn-update-status {
        width: 100%;
    }

    .btn-edit,
    .btn-verify {
        width: 100%;
        text-align: center;
    }

    .badge-status {
        font-size: 0.7rem;
        padding: 4px 12px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// ============================================================
// MODAL KONFIRMASI VERIFIKASI PEMBAYARAN - AJAX
// ============================================================
function openVerifikasiModal() {
    var modal = document.getElementById('verifikasiModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeVerifikasiModal() {
    var modal = document.getElementById('verifikasiModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Event untuk tombol verifikasi - BUKA MODAL
document.getElementById('btnVerifikasi')?.addEventListener('click', function(e) {
    e.preventDefault();
    openVerifikasiModal();
});

// Event untuk tombol konfirmasi verifikasi - AJAX
document.getElementById('confirmVerifikasiBtn')?.addEventListener('click', function() {
    var btn = this;
    var originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Memproses...';
    btn.disabled = true;

    fetch('{{ route("admin.pesanan.verifikasi", $pesanan->id) }}', {
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
            closeVerifikasiModal();
            if (data.success) {
                showCustomToast('success', 'Berhasil!', data.message);
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                showCustomToast('error', 'Gagal!', data.message);
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            closeVerifikasiModal();
            showCustomToast('error', 'Kesalahan Server', 'Terjadi kesalahan pada server');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
});

// ============================================================
// MODAL KONFIRMASI UPDATE STATUS - AJAX
// ============================================================
var selectedStatusText = '';
var selectedStatusValue = '';

function openUpdateStatusModal(statusText, statusValue) {
    selectedStatusText = statusText;
    selectedStatusValue = statusValue;
    var statusTextSpan = document.getElementById('selectedStatusText');
    if (statusTextSpan) {
        statusTextSpan.innerText = statusText;
    }
    var modal = document.getElementById('updateStatusModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeUpdateStatusModal() {
    var modal = document.getElementById('updateStatusModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Event untuk tombol update status - BUKA MODAL
document.getElementById('btnUpdateStatus')?.addEventListener('click', function(e) {
    e.preventDefault();
    var selectElement = document.getElementById('statusSelect');
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    var statusText = selectedOption.text;
    var statusValue = selectElement.value;
    var currentStatus = '{{ $pesanan->status }}';

    if (statusValue !== currentStatus) {
        openUpdateStatusModal(statusText, statusValue);
    } else {
        showCustomToast('warning', 'Perhatian', 'Status yang dipilih sama dengan status saat ini');
    }
});

// Event untuk tombol konfirmasi update status - AJAX
document.getElementById('confirmUpdateStatusBtn')?.addEventListener('click', function() {
    var btn = this;
    var originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Memproses...';
    btn.disabled = true;

    fetch('{{ route("admin.pesanan.update-status", $pesanan->id) }}', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                status: selectedStatusValue
            })
        })
        .then(response => response.json())
        .then(data => {
            closeUpdateStatusModal();
            if (data.success) {
                showCustomToast('success', 'Berhasil!', data.message);
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                showCustomToast('error', 'Gagal!', data.message);
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            closeUpdateStatusModal();
            showCustomToast('error', 'Kesalahan Server', 'Terjadi kesalahan pada server');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
});

// Tutup modal kalau klik di luar area modal
window.onclick = function(event) {
    var verifikasiModal = document.getElementById('verifikasiModal');
    if (event.target == verifikasiModal) {
        closeVerifikasiModal();
    }
    var updateStatusModal = document.getElementById('updateStatusModal');
    if (event.target == updateStatusModal) {
        closeUpdateStatusModal();
    }
}

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
    } else if (type === 'error') {
        icon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
    } else {
        icon.innerHTML = '<i class="fas fa-info-circle"></i>';
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
                toastDiv.remove();
            }, 300);
        }
    }, 3000);

    toastDiv.onclick = function(e) {
        if (e.target !== closeBtn) {
            toastDiv.classList.remove('show');
            setTimeout(function() {
                toastDiv.remove();
            }, 300);
        }
    };
}
</script>
@endpush
@endsection