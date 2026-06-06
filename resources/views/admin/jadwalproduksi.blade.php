@extends('layouts.admin')

@section('title', 'Jadwal Produksi')

@section('content')
<div class="jadwal-container">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold mb-1">
                <i class="bi bi-calendar-check me-2" style="color: #ffc107;"></i>
                Jadwal Produksi
            </h2>
            <p class="text-muted mb-0">Manajemen jadwal produksi pesanan catering</p>
        </div>
        <div class="d-flex gap-2 mt-2 mt-sm-0">
            <button onclick="window.print()" class="btn-print">
                <i class="bi bi-printer me-2"></i> Cetak
            </button>
            <button class="btn-generate" onclick="openGenerateModal()">
                <i class="bi bi-calendar-plus me-2"></i> Generate Jadwal
            </button>
            <button class="btn-reset" onclick="openResetModal()">
                <i class="bi bi-arrow-repeat me-2"></i> Reset Jadwal
            </button>
        </div>
    </div>

    <!-- Header untuk cetak -->
    <div class="print-header">
        <h2>Jadwal Produksi Prisma Sari Catering</h2>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y H:i') }} WIB</p>
        <hr>
    </div>

    @if(isset($jadwalByTanggal) && count($jadwalByTanggal) > 0)
    @foreach($jadwalByTanggal as $tanggal => $items)
    @php
    $firstItem = is_array($items) ? reset($items) : $items->first();
    $tanggalPengambilan = isset($firstItem->pesanan->tanggal_pengambilan) ? $firstItem->pesanan->tanggal_pengambilan :
    null;
    $tanggalMulaiProduksi = $tanggalPengambilan ? \Carbon\Carbon::parse($tanggalPengambilan)->subDay() : null;
    $tanggalMulai = $tanggalMulaiProduksi ?: \Carbon\Carbon::parse($tanggal);
    @endphp
    <div class="card-jadwal card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
            <div class="d-flex align-items-center gap-2">
                <div class="header-icon">
                    <i class="bi bi-calendar"></i>
                </div>
                <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                    {{ $tanggalMulai->locale('id')->translatedFormat('l, d F Y') }}
                </h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 12%">Nomor Pesanan</th>
                            <th>Detail Produk</th>
                            <th style="width: 22%">Waktu Produksi</th>
                            <th style="width: 15%">Waktu Pengambilan</th>
                            <th style="width: 10%">Status</th>
                            <th style="width: 8%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr class="detail-item-in-card" data-id="{{ $item->id }}">
                            <td class="align-middle text-center fw-bold">{{ $item->urutan }}</td>
                            <td class="align-middle text-center">
                                <span class="order-number">{{ $item->nomor_pesanan }}</span>
                            </td>
                            <td class="align-middle">
                                @php $detailProduk = $item->pesanan->detailPesanan ?? collect(); @endphp
                                @if($detailProduk->count() > 0)
                                <div class="produk-detail-list">
                                    @foreach($detailProduk as $detail)
                                    <div class="produk-item mb-2">
                                        <div class="d-flex justify-content-between">
                                            <strong class="produk-name">{{ $detail->nama_item }}</strong>
                                            <span class="produk-qty">x{{ $detail->jumlah }}</span>
                                        </div>
                                        @if($detail->customSnackbox && $detail->customSnackbox->detail &&
                                        $detail->customSnackbox->detail->count() > 0)
                                        <div class="custom-snackbox-info mt-1">
                                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                                <span class="badge-snackbox"><i class="bi bi-box-seam"></i>
                                                    {{ $detail->customSnackbox->nama_ukuran }}</span>
                                                <span
                                                    class="badge-snackbox-secondary">{{ $detail->customSnackbox->jumlah_box }}
                                                    box</span>
                                            </div>
                                            <div class="snackbox-items mt-1">
                                                @foreach($detail->customSnackbox->detail as $jajanan)
                                                <div class="d-flex justify-content-between small">
                                                    <span>•
                                                        {{ $jajanan->produk->nama_produk ?? 'Produk tidak ditemukan' }}</span>
                                                    <span>{{ $jajanan->jumlah }} pcs</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                    <hr class="my-1">
                                    <small class="text-muted"><i class="bi bi-box"></i> Total:
                                        {{ $detailProduk->sum('jumlah') }} item</small>
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                <div class="waktu-produksi-group">
                                    <div class="waktu-input-wrapper">
                                        <input type="time" class="form-control waktu-mulai" data-id="{{ $item->id }}"
                                            value="{{ $item->jam_mulai && $item->jam_mulai != '00:00:00' ? \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') : '20:00' }}">
                                        <span class="separator">—</span>
                                        <input type="time" class="form-control waktu-selesai" data-id="{{ $item->id }}"
                                            value="{{ $item->jam_selesai && $item->jam_selesai != '00:00:00' ? \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') : '20:30' }}">
                                    </div>
                                    <button class="btn-save-waktu" data-id="{{ $item->id }}" title="Simpan Waktu">
                                        <i class="bi bi-save"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="align-middle text-center">
                                @php
                                $tanggalPengambilanItem = $item->pesanan->tanggal_pengambilan ?? null;
                                $waktuSekarang = \Carbon\Carbon::now();
                                $warnaWaktu = 'badge-waktu-info';
                                $statusProduksi = $item->status ?? null;
                                $badgeText = '';
                                $formattedDate = '';

                                if($tanggalPengambilanItem) {
                                $waktuPengambilanObj = \Carbon\Carbon::parse($tanggalPengambilanItem);
                                $formattedDate = $waktuPengambilanObj->locale('id')->translatedFormat('d F Y H:i');

                                if ($statusProduksi == 'selesai') {
                                $warnaWaktu = 'badge-waktu-success';
                                } else {
                                if ($waktuSekarang->gt($waktuPengambilanObj)) {
                                $warnaWaktu = 'badge-waktu-danger';
                                } elseif ($statusProduksi == 'produksi') {
                                $selisihMenit = $waktuSekarang->diffInMinutes($waktuPengambilanObj, false);
                                if ($selisihMenit <= 30 && $selisihMenit>= 0) {
                                    $warnaWaktu = 'badge-waktu-warning';
                                    }
                                    }
                                    }
                                    $badgeText = '<i class="bi bi-calendar3 me-1"></i> ' . $formattedDate . ' WIB';
                                    } else {
                                    $warnaWaktu = 'badge-waktu-secondary';
                                    $badgeText = '<i class="bi bi-question-circle me-1"></i> Belum diatur';
                                    }
                                    @endphp
                                    <span class="badge-waktu {{ $warnaWaktu }}"
                                        data-tanggal-pengambilan="{{ $tanggalPengambilanItem }}"
                                        data-formatted-date="{{ $formattedDate }}">
                                        {!! $badgeText !!}
                                    </span>
                            </td>
                            <td class="align-middle text-center">
                                <select class="status-select form-select-sm" data-id="{{ $item->id }}">
                                    <option value="menunggu" {{ $item->status == 'menunggu' ? 'selected' : '' }}>
                                        Menunggu</option>
                                    <option value="produksi" {{ $item->status == 'produksi' ? 'selected' : '' }}>
                                        Produksi</option>
                                    <option value="selesai" {{ $item->status == 'selesai' ? 'selected' : '' }}>Selesai
                                    </option>
                                </select>
                            </td>
                            <td class="align-middle text-center">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.pesanan.show', ['id' => $item->pesanan_id, 'from' => 'jadwal']) }}"
                                        class="btn-action view" title="Lihat Pesanan">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="btn-action delete"
                                        onclick="openHapusModal({{ $item->id }}, '{{ $item->nomor_pesanan }}')"
                                        title="Hapus Jadwal">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="empty-state-jadwal text-center py-5">
        <i class="bi bi-calendar-x fs-1 text-muted"></i>
        <p class="mt-3 text-muted mb-0">Belum ada jadwal produksi</p>
        <p class="text-muted small">Klik "Generate Jadwal" untuk membuat jadwal</p>
    </div>
    @endif
</div>

<!-- MODAL KONFIRMASI GENERATE JADWAL -->
<div id="generateModal"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 100000; margin: 0; padding: 0;">
    <div
        style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 20px; box-sizing: border-box;">
        <div
            style="background: white; border-radius: 28px; max-width: 400px; width: 100%; margin: 0 auto; box-shadow: 0 30px 60px rgba(0,0,0,0.4); overflow: hidden;">
            <div
                style="background: linear-gradient(135deg, #2e7d32, #1b5e20); color: white; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; font-size: 1rem; font-weight: 600;">
                    <i class="bi bi-calendar-plus me-2"></i> Generate Jadwal
                </h5>
                <button type="button" onclick="closeGenerateModal()"
                    style="background: rgba(255,255,255,0.2); border: none; color: white; width: 30px; height: 30px; border-radius: 50%; font-size: 18px; cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 24px; text-align: center;">
                <i class="bi bi-question-circle" style="font-size: 60px; color: #2e7d32;"></i>
                <h5 class="mt-3 fw-bold">Generate Jadwal Produksi</h5>
                <p class="text-muted mb-2">Pesanan yang sudah dijadwalkan tidak akan digenerate ulang.</p>
                <p class="text-warning small">⚠️ Proses ini akan membuat jadwal untuk pesanan baru.</p>
                <div class="d-flex gap-3 justify-content-center mt-4">
                    <button type="button" onclick="closeGenerateModal()"
                        class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="button" id="confirmGenerateBtn" class="btn btn-success rounded-pill px-4"
                        style="background: linear-gradient(135deg, #2e7d32, #1b5e20); border: none;">
                        <i class="bi bi-check-circle me-1"></i> Ya, Generate
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI RESET JADWAL -->
<div id="resetModal"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 100000; margin: 0; padding: 0;">
    <div
        style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 20px; box-sizing: border-box;">
        <div
            style="background: white; border-radius: 28px; max-width: 400px; width: 100%; margin: 0 auto; box-shadow: 0 30px 60px rgba(0,0,0,0.4); overflow: hidden;">
            <div
                style="background: linear-gradient(135deg, #ff9800, #e65100); color: white; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; font-size: 1rem; font-weight: 600;">
                    <i class="bi bi-trash3 me-2"></i> Hapus Jadwal Selesai
                </h5>
                <button type="button" onclick="closeResetModal()"
                    style="background: rgba(255,255,255,0.2); border: none; color: white; width: 30px; height: 30px; border-radius: 50%; font-size: 18px; cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 24px; text-align: center;">
                <i class="bi bi-exclamation-triangle-fill" style="font-size: 60px; color: #ff9800;"></i>
                <h5 class="mt-3 fw-bold">Hapus Semua Jadwal Selesai</h5>
                <p class="text-muted mb-2">Apakah Anda yakin ingin menghapus semua jadwal produksi yang berstatus
                    <strong class="text-success">SELESAI</strong>?</p>
                <p class="text-warning small">⚠️ Tindakan ini tidak dapat dibatalkan! Jadwal yang sudah selesai akan
                    dihapus dari tampilan.</p>
                <div class="d-flex gap-3 justify-content-center mt-4">
                    <button type="button" onclick="closeResetModal()"
                        class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="button" id="confirmResetBtn" class="btn btn-warning rounded-pill px-4">
                        <i class="bi bi-trash me-1"></i> Ya, Hapus Semua
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS JADWAL (PER ITEM) -->
<div id="hapusJadwalModal"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 100000; margin: 0; padding: 0;">
    <div
        style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 20px; box-sizing: border-box;">
        <div
            style="background: white; border-radius: 28px; max-width: 400px; width: 100%; margin: 0 auto; box-shadow: 0 30px 60px rgba(0,0,0,0.4); overflow: hidden;">
            <div
                style="background: linear-gradient(135deg, #dc3545, #c62828); color: white; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; font-size: 1rem; font-weight: 600;">
                    <i class="bi bi-trash3 me-2"></i> Hapus Jadwal
                </h5>
                <button type="button" onclick="closeHapusModal()"
                    style="background: rgba(255,255,255,0.2); border: none; color: white; width: 30px; height: 30px; border-radius: 50%; font-size: 18px; cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 24px; text-align: center;">
                <i class="bi bi-question-circle" style="font-size: 60px; color: #dc3545;"></i>
                <h5 class="mt-3 fw-bold">Hapus Jadwal Produksi</h5>
                <p class="text-muted mb-2" id="hapusModalMessage">Apakah Anda yakin ingin menghapus jadwal ini?</p>
                <div class="d-flex gap-3 justify-content-center mt-4">
                    <button type="button" onclick="closeHapusModal()"
                        class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="button" id="confirmHapusBtn" class="btn btn-danger rounded-pill px-4">
                        <i class="bi bi-trash me-1"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ======================================================
   CSS LAYAR (SCREEN) - SAMA SEPERTI SKRIP BAWAAN
   ====================================================== */
.jadwal-container {
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

.btn-print {
    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    border: none;
    color: white;
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-print:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    color: white;
}

.btn-generate {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border: none;
    color: white;
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-generate:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    color: white;
}

.btn-reset {
    background: linear-gradient(135deg, #ff9800, #e65100);
    border: none;
    color: white;
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-reset:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
    color: white;
}

.card-jadwal {
    overflow: hidden;
    background: white;
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

.order-number {
    font-weight: 700;
    color: #1b5e20;
    font-family: monospace;
}

.card-jadwal .table thead th {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #1b5e20;
    font-weight: 600;
    padding: 14px 16px;
    border: none;
    font-size: 0.85rem;
}

.card-jadwal .table tbody td {
    padding: 14px 16px;
    vertical-align: middle;
    border-color: #f0f0f0;
}

.detail-item-in-card:hover {
    background: #fff8e1;
}

.produk-name {
    font-size: 0.85rem;
    color: #333;
}

.produk-qty {
    font-size: 0.85rem;
    font-weight: 600;
    color: #2e7d32;
}

.badge-snackbox {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #2e7d32;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
}

.badge-snackbox-secondary {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    color: #e65100;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
}

.snackbox-items {
    font-size: 0.7rem;
    color: #666;
    border-left: 2px solid #ffc107;
    padding-left: 8px;
    margin-top: 5px;
}

.waktu-produksi-group {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: nowrap;
}

.waktu-input-wrapper {
    display: flex;
    align-items: center;
    gap: 6px;
    background: #f8f9fa;
    padding: 4px 12px;
    border-radius: 40px;
    border: 1px solid #e5e7eb;
}

.waktu-input-wrapper .form-control {
    width: 85px;
    border-radius: 20px;
    border: 1px solid #ddd;
    padding: 6px 10px;
    font-size: 0.8rem;
    background: white;
    text-align: center;
}

.waktu-input-wrapper .form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    outline: none;
}

.waktu-input-wrapper .separator {
    color: #2e7d32;
    font-weight: bold;
    font-size: 0.9rem;
}

.btn-save-waktu {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border: none;
    color: #2e7d32;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-save-waktu:hover {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(46, 125, 50, 0.3);
}

.badge-waktu {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.badge-waktu-info {
    background: linear-gradient(135deg, #0dcaf0, #0bb5d8);
    color: black;
}

.badge-waktu-success {
    background: linear-gradient(135deg, #198754, #157347);
    color: white;
}

.badge-waktu-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    animation: pulse 1.5s infinite;
}

.badge-waktu-warning {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #1b5e20;
}

.badge-waktu-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    color: white;
}

.badge-waktu-updating {
    opacity: 0.5;
    transform: scale(0.95);
}

.status-select {
    border-radius: 50px;
    border: 1px solid #e5e7eb;
    padding: 5px 10px;
    font-size: 0.75rem;
    background: white;
    cursor: pointer;
}

.status-select:focus {
    border-color: #ffc107;
    outline: none;
}

.action-buttons {
    display: flex;
    gap: 6px;
    justify-content: center;
}

.btn-action {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
}

.btn-action.view {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #2e7d32;
}

.btn-action.delete {
    background: linear-gradient(135deg, #ffebee, #ffcdd2);
    color: #dc3545;
}

.btn-action:hover {
    transform: scale(1.08);
}

.btn-action.view:hover {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
}

.btn-action.delete:hover {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

.empty-state-jadwal {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
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

@media (max-width: 768px) {
    .page-title {
        font-size: 1.3rem;
    }

    .waktu-produksi-group {
        flex-direction: column;
        align-items: flex-start;
    }

    .waktu-input-wrapper {
        flex-wrap: wrap;
        background: transparent;
        border: none;
        padding: 0;
    }

    .waktu-input-wrapper .form-control {
        width: 100%;
        margin-bottom: 5px;
    }

    .waktu-input-wrapper .separator {
        display: none;
    }

    .action-buttons {
        flex-direction: row;
    }
}

.print-header {
    display: none;
}

.waktu-mulai-text,
.waktu-selesai-text,
.status-text {
    display: none;
}

@media screen {

    .waktu-mulai,
    .waktu-selesai {
        display: inline-block !important;
    }
}

/* ======================================================
   CSS CETAK PDF (PRINT)
   ====================================================== */
@media print {
    * {
        margin: 0 !important;
        padding: 0 !important;
        box-sizing: border-box !important;
    }

    .btn,
    .btn-primary,
    .btn-success,
    .btn-outline-primary,
    .btn-save-waktu,
    form,
    nav.navbar,
    .sidebar,
    .card-header .btn,
    .btn-sm,
    .aksi-kolom,
    .btn-hapus-jadwal,
    .btn-print,
    .btn-generate,
    .btn-reset,
    .action-buttons,
    .btn-action,
    .status-select,
    .waktu-input-wrapper .separator,
    .header-icon,
    .d-flex.gap-2,
    .d-flex.flex-wrap,
    .btn-save-waktu,
    .waktu-mulai,
    .waktu-selesai,
    select,
    button:not(.no-print),
    .col-md-2,
    .sidebar,
    .main-sidebar,
    [class*="sidebar"],
    nav,
    .navbar {
        display: none !important;
    }

    .print-header {
        display: block !important;
        text-align: center;
        margin-bottom: 15px !important;
        padding-bottom: 8px !important;
        border-bottom: 2px solid #1b5e20;
    }

    .print-header h2 {
        font-size: 16pt;
        margin-bottom: 3px !important;
        color: #1b5e20;
    }

    .print-header p {
        font-size: 9pt;
        color: #555;
        margin-bottom: 0 !important;
    }

    body,
    .container,
    .container-fluid,
    .row,
    .col-md-10,
    .col-md-12,
    .main-content,
    .content-wrapper,
    .jadwal-container,
    .card,
    .card-body,
    .table-responsive,
    .card-jadwal {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        background: white !important;
        float: none !important;
        display: block !important;
        overflow: visible !important;
    }

    .card-jadwal {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
        margin-bottom: 15px !important;
        page-break-inside: avoid;
        background: white !important;
        width: 100% !important;
    }

    .card-jadwal .card-header {
        background: #f5f5f5 !important;
        border-bottom: 2px solid #1b5e20 !important;
        padding: 8px 12px !important;
        margin-bottom: 10px !important;
        display: block !important;
    }

    .card-jadwal .card-header .d-flex {
        display: flex !important;
    }

    .card-jadwal .card-header h5 {
        font-size: 12pt !important;
        color: #1b5e20 !important;
        font-weight: bold !important;
        margin: 0 !important;
        display: block !important;
    }

    .card-jadwal .card-header .header-icon {
        display: none !important;
    }

    .table-responsive {
        overflow: visible !important;
        width: 100% !important;
    }

    .table {
        width: 100% !important;
        min-width: 100% !important;
        border-collapse: collapse !important;
        font-size: 9pt !important;
        table-layout: fixed !important;
        margin: 0 !important;
    }

    .table th,
    .table td {
        border: 1px solid #ccc !important;
        padding: 8px 6px !important;
        vertical-align: middle !important;
        text-align: center !important;
        word-wrap: break-word !important;
        white-space: normal !important;
    }

    .table td:nth-child(3) {
        text-align: left !important;
    }

    .table thead th:nth-child(3) {
        text-align: center !important;
    }

    .table thead th {
        background: #e8f5e9 !important;
        color: #1b5e20 !important;
        font-weight: bold !important;
        text-align: center !important;
        font-size: 9pt !important;
        padding: 6px 4px !important;
    }

    .table th:nth-child(1),
    .table td:nth-child(1) {
        width: 5% !important;
    }

    .table th:nth-child(2),
    .table td:nth-child(2) {
        width: 12% !important;
    }

    .table th:nth-child(3),
    .table td:nth-child(3) {
        width: 38% !important;
    }

    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 12% !important;
    }

    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 18% !important;
    }

    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 15% !important;
    }

    .table th:nth-child(7),
    .table td:nth-child(7) {
        display: none !important;
    }

    .produk-detail-list {
        font-size: 7.5pt;
        line-height: 1.3;
        text-align: left;
    }

    .produk-item {
        margin-bottom: 3px !important;
        padding-bottom: 2px !important;
        border-bottom: 1px dotted #eee;
    }

    .produk-item:last-child {
        border-bottom: none;
    }

    .waktu-produksi-group {
        display: block !important;
        text-align: center !important;
    }

    .waktu-input-wrapper {
        display: flex !important;
        align-items: center;
        justify-content: center;
        gap: 3px;
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
    }

    .waktu-mulai-text,
    .waktu-selesai-text {
        font-size: 8pt;
        font-weight: normal;
        font-family: monospace;
        display: inline !important;
    }

    .separator {
        display: inline !important;
    }

    .badge-waktu {
        display: inline-block;
        padding: 3px 8px !important;
        border-radius: 12px;
        font-size: 7.5pt;
        font-weight: 500;
        text-align: center;
        margin: 0 auto;
    }

    .badge-waktu-success {
        background: #d4edda !important;
        color: #155724 !important;
        border: 1px solid #c3e6cb !important;
    }

    .badge-waktu-info {
        background: #d1ecf1 !important;
        color: #0c5460 !important;
        border: 1px solid #bee5eb !important;
    }

    .badge-waktu-danger {
        background: #f8d7da !important;
        color: #721c24 !important;
        border: 1px solid #f5c6cb !important;
    }

    .badge-waktu-warning {
        background: #fff3cd !important;
        color: #856404 !important;
        border: 1px solid #ffeeba !important;
    }

    .badge-waktu-secondary {
        background: #e2e3e5 !important;
        color: #383d41 !important;
        border: 1px solid #d6d8db !important;
    }

    .status-text {
        display: inline-block !important;
        padding: 3px 10px !important;
        border-radius: 15px;
        font-size: 7.5pt;
        font-weight: 500;
        text-align: center;
    }

    thead {
        display: table-header-group;
    }

    tr,
    .card-jadwal {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    @page {
        size: landscape;
        margin: 0.5cm;
    }

    @page {
        @bottom-center {
            content: "Halaman "counter(page);
            font-size: 8pt;
            color: #666;
        }
    }

    * {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
@endpush

@push('scripts')
<script>
// ============================================================
// MODAL KONFIRMASI GENERATE - AJAX
// ============================================================
function openGenerateModal() {
    document.getElementById('generateModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeGenerateModal() {
    document.getElementById('generateModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

document.getElementById('confirmGenerateBtn')?.addEventListener('click', function() {
    var confirmBtn = this;
    var originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Memproses...';
    confirmBtn.disabled = true;

    fetch('{{ route("admin.jadwal-produksi.generate") }}', {
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
            closeGenerateModal();
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
            closeGenerateModal();
            showCustomToast('error', 'Kesalahan Server', 'Terjadi kesalahan pada server');
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        });
});

// ============================================================
// MODAL KONFIRMASI RESET - HAPUS JADWAL SELESAI
// ============================================================
function openResetModal() {
    document.getElementById('resetModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeResetModal() {
    document.getElementById('resetModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

document.getElementById('confirmResetBtn')?.addEventListener('click', function() {
    var confirmBtn = this;
    var originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Menghapus...';
    confirmBtn.disabled = true;

    fetch('{{ route("admin.jadwal-produksi.reset") }}', {
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
            closeResetModal();
            if (data.success) {
                showCustomToast('success', 'Berhasil!', data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showCustomToast('error', 'Gagal!', data.message);
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            closeResetModal();
            showCustomToast('error', 'Kesalahan Server', 'Terjadi kesalahan pada server');
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        });
});

// ============================================================
// MODAL KONFIRMASI HAPUS JADWAL (PER ITEM)
// ============================================================
var hapusId = null;
var hapusNomor = '';

function openHapusModal(id, nomorPesanan) {
    hapusId = id;
    hapusNomor = nomorPesanan;
    document.getElementById('hapusModalMessage').innerHTML =
        'Apakah Anda yakin ingin menghapus jadwal produksi <strong>' + nomorPesanan + '</strong>?';
    document.getElementById('hapusJadwalModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeHapusModal() {
    document.getElementById('hapusJadwalModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    hapusId = null;
    hapusNomor = '';
}

document.getElementById('confirmHapusBtn')?.addEventListener('click', function() {
    if (!hapusId) {
        showCustomToast('error', 'Gagal!', 'Jadwal tidak ditemukan');
        closeHapusModal();
        return;
    }

    var confirmBtn = this;
    var originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Menghapus...';
    confirmBtn.disabled = true;

    fetch('{{ url("admin/jadwal-produksi") }}/' + hapusId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            closeHapusModal();
            if (data.success) {
                showCustomToast('success', 'Berhasil!', data.message);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showCustomToast('error', 'Gagal!', data.message);
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            closeHapusModal();
            showCustomToast('error', 'Kesalahan Server', 'Terjadi kesalahan pada server');
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        });
});

// ============================================================
// UPDATE STATUS PRODUKSI DENGAN PERUBAHAN BADGE WARNA
// ============================================================
$(document).ready(function() {
    function updateBadgeWarna($badgeElement, status, tanggalPengambilan, formattedDate) {
        if (!tanggalPengambilan || tanggalPengambilan === '') {
            $badgeElement.removeClass(
                    'badge-waktu-info badge-waktu-success badge-waktu-danger badge-waktu-warning badge-waktu-secondary'
                    )
                .addClass('badge-waktu-secondary')
                .html('<i class="bi bi-question-circle me-1"></i> Belum diatur');
            return;
        }

        var waktuSekarang = new Date();
        var waktuPengambilan = new Date(tanggalPengambilan);
        var selisihMenit = Math.floor((waktuPengambilan - waktuSekarang) / (1000 * 60));

        $badgeElement.removeClass(
            'badge-waktu-info badge-waktu-success badge-waktu-danger badge-waktu-warning badge-waktu-secondary'
            );

        if (status === 'selesai') {
            $badgeElement.addClass('badge-waktu-success');
        } else {
            if (waktuSekarang > waktuPengambilan) {
                $badgeElement.addClass('badge-waktu-danger');
            } else if (status === 'produksi' && selisihMenit <= 30 && selisihMenit >= 0) {
                $badgeElement.addClass('badge-waktu-warning');
            } else {
                $badgeElement.addClass('badge-waktu-info');
            }
        }
        $badgeElement.html('<i class="bi bi-calendar3 me-1"></i> ' + formattedDate + ' WIB');
    }

    $('.status-select').each(function() {
        var $select = $(this);
        var statusValue = $select.val();
        var statusText = $select.find('option:selected').text();
        var statusClass = statusValue === 'menunggu' ? 'badge-waktu-secondary' : (statusValue ===
            'produksi' ? 'badge-waktu-warning' : 'badge-waktu-success');
        if ($select.next('.status-text').length === 0) {
            $select.after('<span class="status-text ' + statusClass + '">' + statusText + '</span>');
        }
    });

    $('.waktu-mulai').each(function() {
        var $input = $(this);
        if ($input.next('.waktu-mulai-text').length === 0) {
            $input.after('<span class="waktu-mulai-text">' + $input.val() + '</span>');
        }
    });

    $('.waktu-selesai').each(function() {
        var $input = $(this);
        if ($input.next('.waktu-selesai-text').length === 0) {
            $input.after('<span class="waktu-selesai-text">' + $input.val() + '</span>');
        }
    });

    $('.status-select').off('change').on('change', function() {
        var select = $(this);
        var id = select.data('id');
        var status = select.val();
        var previousValue = select.data('previous') || select.val();

        select.data('previous', select.val());
        select.prop('disabled', true);
        select.css('opacity', '0.6');

        var $row = select.closest('tr');
        var $badgeElement = $row.find('.badge-waktu');
        var tanggalPengambilan = $badgeElement.data('tanggal-pengambilan');
        var formattedDate = $badgeElement.data('formatted-date');

        $badgeElement.addClass('badge-waktu-updating');

        fetch('/admin/jadwal-produksi/' + id + '/status', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateBadgeWarna($badgeElement, status, tanggalPengambilan, formattedDate);
                    var statusText = select.find('option:selected').text();
                    var statusClass = status === 'menunggu' ? 'badge-waktu-secondary' : (status ===
                        'produksi' ? 'badge-waktu-warning' : 'badge-waktu-success');
                    select.next('.status-text').remove();
                    select.after('<span class="status-text ' + statusClass + '">' + statusText +
                        '</span>');
                    showCustomToast('success', 'Berhasil!', 'Status berhasil diperbarui menjadi ' +
                        (status === 'menunggu' ? 'Menunggu' : status === 'produksi' ?
                            'Produksi' : 'Selesai'));
                    if (status === 'selesai') {
                        $row.css('background-color', '#f0fdf4');
                        setTimeout(function() {
                            $row.css('background-color', '');
                        }, 1000);
                    }
                    select.data('previous', status);
                } else {
                    select.val(previousValue);
                    showCustomToast('error', 'Gagal!', data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                select.val(previousValue);
                showCustomToast('error', 'Kesalahan Server', error.message ||
                    'Terjadi kesalahan pada server');
            })
            .finally(function() {
                select.prop('disabled', false);
                select.css('opacity', '1');
                $badgeElement.removeClass('badge-waktu-updating');
            });
    });
});

// ============================================================
// TUTUP MODAL SAAT KLIK DI LUAR AREA
// ============================================================
window.onclick = function(event) {
    if (event.target == document.getElementById('generateModal')) closeGenerateModal();
    if (event.target == document.getElementById('resetModal')) closeResetModal();
    if (event.target == document.getElementById('hapusJadwalModal')) closeHapusModal();
};

// ============================================================
// SCRIPT JADWAL PRODUKSI (FCFS)
// ============================================================
$(document).ready(function() {
    function hitungDurasi(jamMulai, jamSelesai) {
        if (!jamMulai || !jamSelesai) return 30;
        let mulai = jamMulai.split(':');
        let selesai = jamSelesai.split(':');
        let menitMulai = parseInt(mulai[0]) * 60 + parseInt(mulai[1]);
        let menitSelesai = parseInt(selesai[0]) * 60 + parseInt(selesai[1]);
        let durasi = menitSelesai - menitMulai;
        if (durasi < 0) durasi += 24 * 60;
        return durasi;
    }

    function tambahMenit(jam, menit) {
        if (!jam) return '20:00';
        let parts = jam.split(':');
        if (parts.length < 2) return '20:00';
        let totalMenit = parseInt(parts[0]) * 60 + parseInt(parts[1]) + menit;
        let jamBaru = Math.floor(totalMenit / 60) % 24;
        let menitBaru = totalMenit % 60;
        return `${String(jamBaru).padStart(2, '0')}:${String(menitBaru).padStart(2, '0')}`;
    }

    function updateFCFSFromIndex(card, startIndex) {
        let itemsInCard = card.find('.detail-item-in-card');
        let currentTime = null;
        for (let i = startIndex; i < itemsInCard.length; i++) {
            let itemId = $(itemsInCard[i]).data('id');
            let durasi = $(`.waktu-selesai[data-id="${itemId}"]`).data('durasi') || 30;
            if (i === startIndex) {
                currentTime = $(`.waktu-mulai[data-id="${itemId}"]`).val();
            } else {
                $(`.waktu-mulai[data-id="${itemId}"]`).val(currentTime);
            }
            let jamSelesaiBaru = tambahMenit(currentTime, durasi);
            $(`.waktu-selesai[data-id="${itemId}"]`).val(jamSelesaiBaru);
            currentTime = jamSelesaiBaru;
        }
    }

    $('.waktu-mulai').each(function() {
        let id = $(this).data('id');
        let jamMulaiAwal = $(this).val();
        let jamSelesaiAwal = $(`.waktu-selesai[data-id="${id}"]`).val();
        if (!jamSelesaiAwal || jamSelesaiAwal === '--:--') {
            jamSelesaiAwal = '20:30';
            $(`.waktu-selesai[data-id="${id}"]`).val(jamSelesaiAwal);
        }
        $(this).data('awal', jamMulaiAwal);
        let durasi = hitungDurasi(jamMulaiAwal, jamSelesaiAwal);
        $(`.waktu-selesai[data-id="${id}"]`).data('durasi', durasi);
    });

    $(document).on('change', '.waktu-mulai', function() {
        let id = $(this).data('id');
        let card = $(this).closest('.card-jadwal');
        let itemsInCard = card.find('.detail-item-in-card');
        let currentIndex = -1;
        itemsInCard.each(function(idx) {
            if ($(this).data('id') == id) currentIndex = idx;
        });
        if (currentIndex !== -1) updateFCFSFromIndex(card, currentIndex);
    });

    $('.btn-save-waktu').click(function() {
        let card = $(this).closest('.card-jadwal');
        let itemsInCard = card.find('.detail-item-in-card');
        let promises = [];
        itemsInCard.each(function() {
            let id = $(this).data('id');
            let jamMulai = $(`.waktu-mulai[data-id="${id}"]`).val();
            let jamSelesai = $(`.waktu-selesai[data-id="${id}"]`).val();
            if (!jamMulai || !jamSelesai) return;
            const timeRegex = /^([0-1][0-9]|2[0-3]):[0-5][0-9]$/;
            if (!timeRegex.test(jamMulai) || !timeRegex.test(jamSelesai)) return;
            let promise = fetch(`/admin/jadwal-produksi/${id}/waktu`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    jam_mulai: jamMulai,
                    jam_selesai: jamSelesai
                })
            }).then(response => response.json());
            promises.push(promise);
        });
        if (promises.length === 0) {
            showCustomToast('error', 'Validasi Gagal', 'Tidak ada data yang valid untuk disimpan');
            return;
        }
        let saveBtn = $(this);
        saveBtn.prop('disabled', true);
        saveBtn.html('<i class="bi bi-hourglass-split"></i>');
        Promise.all(promises).then(results => {
            let allSuccess = results.every(r => r.success);
            if (allSuccess) {
                showCustomToast('success', 'Berhasil!',
                    'Semua waktu produksi berhasil diupdate');
                itemsInCard.each(function() {
                    let id = $(this).data('id');
                    let jamMulai = $(`.waktu-mulai[data-id="${id}"]`).val();
                    let jamSelesai = $(`.waktu-selesai[data-id="${id}"]`).val();
                    let durasiBaru = hitungDurasi(jamMulai, jamSelesai);
                    $(`.waktu-selesai[data-id="${id}"]`).data('durasi', durasiBaru);
                    $(`.waktu-mulai[data-id="${id}"]`).data('awal', jamMulai);
                });
            } else {
                showCustomToast('error', 'Gagal!', 'Beberapa update gagal. Silakan coba lagi.');
            }
        }).catch(error => {
            showCustomToast('error', 'Kesalahan Server', error.message);
        }).finally(() => {
            saveBtn.prop('disabled', false);
            saveBtn.html('<i class="bi bi-save"></i>');
        });
    });
});

function showCustomToast(type, title, message) {
    document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());
    var toastDiv = document.createElement('div');
    toastDiv.className = 'custom-toast toast-' + type;
    toastDiv.innerHTML = '<div class="toast-icon"><i class="bi bi-' + (type === 'success' ? 'check-circle-fill' :
            'exclamation-triangle-fill') + '"></i></div><div class="toast-content"><div class="toast-title">' + title +
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