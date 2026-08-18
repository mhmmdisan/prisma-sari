@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $pesanan->nomor_pesanan)

@section('content')
<div class="batik-bg py-4">
    <div class="container position-relative" style="z-index: 1;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white d-inline-flex p-3 rounded-4 shadow-sm">
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.dashboard') }}"
                        class="text-success text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.pesanan.index') }}"
                        class="text-success text-decoration-none">Pesanan</a></li>
                <li class="breadcrumb-item active text-dark fw-bold">{{ $pesanan->nomor_pesanan }}</li>
            </ol>
        </nav>

        <!-- Alert Success/Error dengan Tombol Close -->
        @if(session('success'))
        <div class="alert-custom alert-success-custom mb-4">
            <i class="fas fa-check-circle"></i>
            <div class="alert-content">{{ session('success') }}</div>
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

        <!-- WhatsApp Custom Order Alert dengan Tombol Close -->
        @if($pesanan->is_whatsapp_order ?? false)
        <div class="alert-wa-custom mb-4">
            <button type="button" class="alert-wa-close"
                onclick="this.closest('.alert-wa-custom').remove()">&times;</button>
            <div class="d-flex align-items-center">
                <i class="bi bi-whatsapp fs-2 me-3 text-white"></i>
                <div>
                    <h5 class="mb-1 text-white"><i class="bi bi-whatsapp"></i> Informasi Custom Order</h5>
                    <p class="mb-0 text-white">Pesanan ini dibuat melalui WhatsApp Custom Order.</p>
                    <small class="text-white-50 d-block mt-1">Jika ada pertanyaan, silakan hubungi admin melalui
                        WhatsApp.</small>
                </div>
            </div>
        </div>
        @endif

        <!-- Keterlambatan Alert dengan Tombol Close -->
        @if($pesanan->status == 'terlambat')
        <div class="alert-terlambat-custom mb-4">
            <button type="button" class="alert-terlambat-close"
                onclick="this.closest('.alert-terlambat-custom').remove()">&times;</button>
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-2 me-3 text-danger"></i>
                <div>
                    <h5 class="mb-1 text-danger">⚠️ Pemberitahuan Keterlambatan</h5>
                    <p class="mb-0">Mohon maaf, pesanan Anda mengalami keterlambatan. Kami akan memprosesnya sesegera
                        mungkin.</p>
                    <small class="text-muted d-block mt-1">Info terakhir:
                        {{ \Carbon\Carbon::parse($pesanan->updated_at)->locale('id')->translatedFormat('d F Y H:i') }}
                        WIB</small>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <!-- KOLOM KIRI: Detail Pesanan -->
            <div class="col-md-8">
                <!-- Card Detail Pesanan -->
                <div class="card border-0 rounded-4 shadow-sm mb-4 hover-card">
                    <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                        <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                            <i class="bi bi-receipt me-2"></i> Detail Pesanan
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="text-muted"><i class="bi bi-upc-scan me-1"></i> Nomor Pesanan</small>
                                <p class="fw-bold mb-0 text-success">{{ $pesanan->nomor_pesanan }}</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> Tanggal Pesanan</small>
                                <p class="fw-bold mb-0">
                                    {{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->locale('id')->translatedFormat('d F Y H:i') }}
                                    WIB</p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light"
                                    style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9);">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Harga Satuan</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pesanan->detailPesanan as $detail)
                                    <tr>
                                        <td>
                                            <i class="bi bi-box-seam text-success me-2"></i>
                                            {{ $detail->nama_item }}
                                            
                                            {{-- TAMPILAN CUSTOM SNACKBOX --}}
                                            @if($detail->customSnackbox)
                                                <div class="mt-2 p-2 bg-light rounded-3">
                                                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                                        <span class="badge bg-warning text-dark">
                                                            {{ $detail->customSnackbox->nama_ukuran ?? $detail->customSnackbox->kode_ukuran }}
                                                        </span>
                                                        <span class="badge bg-success">
                                                            {{ $detail->customSnackbox->jumlah_box }} box
                                                        </span>
                                                    </div>
                                                    <div class="small text-muted">
                                                        <strong>Isi Snackbox:</strong>
                                                        <div class="mt-1 ps-2" style="border-left: 2px solid #ffc107;">
                                                            @if($detail->customSnackbox->detail && $detail->customSnackbox->detail->count() > 0)
                                                                @foreach($detail->customSnackbox->detail as $snackDetail)
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <span>• {{ $snackDetail->produk->nama_produk ?? 'Produk' }}</span>
                                                                        <span class="text-success">{{ $snackDetail->jumlah }} pcs</span>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <span class="text-muted">Tidak ada detail produk</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            {{-- TAMPILKAN KATEGORI UNTUK PRODUK BIASA --}}
                                            @if($detail->produk && $detail->produk->kategori)
                                                <small class="text-muted d-block mt-1">
                                                    <i class="bi bi-tag me-1"></i> 
                                                    {{ $detail->produk->kategori->nama_kategori }}
                                                </small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">{{ $detail->jumlah }}</span>
                                        </td>
                                        <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end text-primary fw-bold">Rp
                                            {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-active"
                                    style="background: linear-gradient(135deg, #fff8e1, #ffecb3);">
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th class="text-end text-success fw-bold fs-5">Rp
                                            {{ number_format($pesanan->total_harga, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Card Informasi Pengiriman -->
                <div class="card border-0 rounded-4 shadow-sm mb-4 hover-card">
                    <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                        <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                            <i class="bi bi-truck me-2"></i> Informasi Pengiriman
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <small class="text-muted"><i class="bi bi-calendar-event text-warning me-1"></i> Tanggal
                                    Pengambilan</small>
                                <p class="fw-bold mb-0">
                                    {{ \Carbon\Carbon::parse($pesanan->tanggal_pengambilan)->locale('id')->translatedFormat('d F Y H:i') }}
                                    WIB</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted"><i class="bi bi-tag me-1"></i> Status Pesanan</small>
                                <div>
                                    @php
                                    $statusClass = match($pesanan->status) {
                                        'menunggu_pembayaran' => 'badge-status-warning',
                                        'menunggu_verifikasi' => 'badge-status-info',   
                                        'diproses' => 'badge-status-primary',
                                        'terlambat' => 'badge-status-danger',
                                        'selesai' => 'badge-status-success',
                                        'dibatalkan' => 'badge-status-secondary',
                                        default => 'badge-status-info'
                                    };
                                    $statusLabel = match($pesanan->status) {
                                        'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                        'menunggu_verifikasi' => 'Menunggu Verifikasi', 
                                        'diproses' => 'Diproses',
                                        'terlambat' => 'Terlambat ⚠️',
                                        'selesai' => 'Selesai',
                                        'dibatalkan' => 'Dibatalkan',
                                        default => ucfirst($pesanan->status)
                                    };
                                    @endphp
                                    <span class="{{ $statusClass }} px-3 py-2 fs-6">{{ $statusLabel }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <small class="text-muted"><i class="bi bi-geo-alt text-danger me-1"></i> Alamat
                                    Pengiriman</small>
                                <p class="fw-bold mb-0 bg-light p-2 rounded-3">{{ $pesanan->alamat_pengiriman }}</p>
                            </div>
                        </div>
                        @if($pesanan->catatan_pesanan)
                        <div class="row mt-3">
                            <div class="col-12">
                                <small class="text-muted"><i class="bi bi-pencil-square text-info me-1"></i> Catatan
                                    Pesanan</small>
                                <p class="mb-0 bg-light p-2 rounded-3">{{ $pesanan->catatan_pesanan }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: Status Pembayaran -->
            <div class="col-md-4">
                <div class="card border-0 rounded-4 shadow-sm mb-4 sticky-lg-top hover-card" style="top: 20px;">
                    <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                        <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                            <i class="bi bi-credit-card me-2"></i> Status Pembayaran
                        </h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        <!-- Icon Status -->
                        <div class="mb-3">
                            @if($pesanan->status_pembayaran == 'lunas')
                            <div class="status-icon-success">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <h4 class="mt-2 text-success">Lunas</h4>
                            @elseif($pesanan->status_pembayaran == 'menunggu_konfirmasi')
                            <div class="status-icon-warning">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <h4 class="mt-2 text-warning">Menunggu Konfirmasi</h4>
                            @else
                            <div class="d-flex align-items-center justify-content-center gap-3 mb-2">
                                <i class="bi bi-hourglass-split" style="font-size: 2.5rem; color: #dc3545;"></i>
                                <h4 class="mb-0 text-danger">Belum Dibayar</h4>
                            </div>
                            @endif
                        </div>

                        <!-- PESANAN YANG DIBATALKAN OTOMATIS KARENA EXPIRED -->
                        @if($pesanan->status == 'dibatalkan' && $pesanan->expired_at < now())
                        <div class="alert-expired-custom mb-3 text-start">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                                <strong class="text-danger">Pesanan Dibatalkan Otomatis!</strong>
                            </div>
                            <div class="small">
                                <i class="bi bi-clock-history me-1"></i> 
                                <strong>Batas waktu pembayaran:</strong> 
                                {{ \Carbon\Carbon::parse($pesanan->expired_at)->locale('id')->translatedFormat('d F Y H:i') }} WIB
                            </div>
                            <div class="small mt-2 text-danger">
                                <i class="bi bi-info-circle me-1"></i> 
                                Pesanan dibatalkan karena melewati batas waktu pembayaran. Anda tidak dapat upload bukti pembayaran lagi.
                            </div>
                        </div>

                        <!-- PESANAN YANG DIBATALKAN OLEH USER (BUKAN EXPIRED) -->
                        @elseif($pesanan->status == 'dibatalkan')
                        <div class="cancellation-card d-flex align-items-center justify-content-center gap-3 p-2 bg-success-subtle border border-success-subtle rounded-3 mb-3">
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                                <i class="bi bi-check-lg text-white" style="font-size: 22px;"></i>
                            </div>
                            <div class="text-start">
                                <h6 class="mb-0 text-success fw-bold">Pesanan Berhasil Dibatalkan</h6>
                                <p class="mb-0 small text-secondary">Pesanan dibatalkan atas permintaan Anda.</p>
                            </div>
                        </div>

                        <!-- PESANAN YANG MENUNGGU VERIFIKASI (SUDAH UPLOAD BUKTI) -->
                        @elseif($pesanan->status == 'menunggu_verifikasi')
                        <div class="alert-info-custom mb-3 text-start">
                            <i class="bi bi-clock-history me-2"></i> 
                            <strong>📤 Bukti pembayaran telah diupload!</strong><br>
                            Menunggu verifikasi dari admin. Silakan cek secara berkala.
                            @if(isset($pesanan->expired_at) && $pesanan->expired_at > now())
                            <div class="small mt-1 text-muted">
                                <i class="bi bi-clock me-1"></i> Batas waktu: 
                                {{ \Carbon\Carbon::parse($pesanan->expired_at)->locale('id')->translatedFormat('d F Y H:i') }} WIB
                            </div>
                            @endif
                        </div>

                        <!-- PESANAN YANG MASIH MENUNGGU PEMBAYARAN TAPI SUDAH EXPIRED -->
                        @elseif($pesanan->status_pembayaran == 'belum_bayar' && $pesanan->status != 'dibatalkan')
                            @if(isset($pesanan->expired_at) && now() > $pesanan->expired_at)
                            <div class="alert-expired-custom mb-3 text-start">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                                    <strong class="text-danger">⚠️ Waktu Pembayaran Habis!</strong>
                                </div>
                                <div class="small">
                                    <i class="bi bi-clock-history me-1"></i> 
                                    <strong>Batas waktu:</strong> 
                                    {{ \Carbon\Carbon::parse($pesanan->expired_at)->locale('id')->translatedFormat('d F Y H:i') }} WIB
                                </div>
                                <div class="small mt-2 text-danger">
                                    <i class="bi bi-info-circle me-1"></i> 
                                    Pesanan akan segera dibatalkan otomatis. Silakan buat pesanan baru.
                                </div>
                            </div>
                            @else
                            <div class="alert-warning-custom mb-3 text-start">
                                <i class="bi bi-info-circle me-2"></i> Silakan lakukan pembayaran untuk melanjutkan pesanan.
                            </div>
                            @endif
                        @elseif($pesanan->status_pembayaran == 'menunggu_konfirmasi')
                        <div class="alert-info-custom mb-3 text-start">
                            <i class="bi bi-clock-history me-2"></i> Bukti pembayaran telah diupload. Menunggu
                            konfirmasi admin.
                        </div>
                        @elseif($pesanan->status_pembayaran == 'lunas')
                        <div class="alert-success-custom mb-3 text-start">
                            <i class="bi bi-check-circle me-2"></i> Pembayaran telah dikonfirmasi. Pesanan akan
                            diproses.
                        </div>
                        @endif

                        <!-- Batas Waktu Pembayaran (untuk pesanan yang belum dibayar dan belum expired) -->
                        @if($pesanan->status == 'menunggu_pembayaran' && $pesanan->status_pembayaran == 'belum_bayar' && $pesanan->expired_at > now())
                        <div class="alert-warning-custom mb-3 text-start">
                            <i class="bi bi-clock me-2"></i>
                            <strong>Batas pembayaran:</strong><br>
                            {{ \Carbon\Carbon::parse($pesanan->expired_at)->locale('id')->translatedFormat('d F Y H:i') }}
                            WIB
                        </div>
                        @endif

                        <!-- ========== TOMBOL AKSI (UPLOAD BUKTI & BATALKAN) ========== -->
                        @if($pesanan->status_pembayaran == 'belum_bayar' && $pesanan->status != 'dibatalkan')
                            @if(isset($pesanan->expired_at) && now() <= $pesanan->expired_at)
                                <button type="button" class="btn btn-success rounded-pill w-100" onclick="openUploadModal()">
                                    <i class="bi bi-credit-card me-2"></i> Upload Bukti Pembayaran
                                </button>
                            @else
                                <div class="alert-expired-custom mt-2 mb-0 text-start">
                                    <i class="bi bi-clock-history me-2"></i> 
                                    <strong>Waktu pembayaran sudah habis.</strong> Anda tidak dapat upload bukti lagi.
                                </div>
                            @endif
                        @endif

                        <!-- Tombol Lihat Bukti menggunakan accessor dan pengecekan file dari model -->
                        @if(($pesanan->status_pembayaran == 'menunggu_konfirmasi' || $pesanan->status == 'menunggu_verifikasi') && $pesanan->bukti_pembayaran)
                            @if($pesanan->bukti_pembayaran_exists)
                                <a href="{{ $pesanan->bukti_pembayaran_url }}" target="_blank"
                                    class="btn btn-outline-info rounded-pill w-100 mt-2">
                                    <i class="bi bi-image me-2"></i> Lihat Bukti Pembayaran
                                </a>
                            @else
                                <div class="alert-warning-custom mt-2 text-start">
                                    <i class="bi bi-exclamation-triangle me-2"></i> 
                                    File bukti tidak ditemukan.
                                </div>
                            @endif
                        @endif

                        <!-- Tombol EDIT BUKTI (jika status menunggu_verifikasi atau menunggu_konfirmasi dan belum expired) -->
                        @if(($pesanan->status == 'menunggu_verifikasi' || $pesanan->status_pembayaran == 'menunggu_konfirmasi') && $pesanan->expired_at > now())
                        <button type="button" class="btn btn-warning rounded-pill w-100 mt-2" onclick="openEditBuktiModal()">
                            <i class="bi bi-pencil-square me-2"></i> Edit Bukti Pembayaran
                        </button>
                        @endif

                        <!-- Tombol Batalkan (HANYA UNTUK PESANAN YANG BELUM EXPIRED DAN BELUM DIBATALKAN) -->
                        @if($pesanan->status == 'menunggu_pembayaran' && $pesanan->status_pembayaran == 'belum_bayar' && $pesanan->expired_at > now())
                        <button class="btn btn-outline-danger rounded-pill w-100 mt-3" onclick="batalkanPesanan(this)">
                            <i class="bi bi-x-circle me-2"></i> Batalkan Pesanan
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL UPLOAD PEMBAYARAN - DIPERBAIKI                        -->
<!-- ============================================================ -->
<div id="uploadModal" class="modal-overlay-custom">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h5><i class="bi bi-upload me-2"></i> Upload Bukti Pembayaran</h5>
            <button type="button" onclick="closeUploadModal()" class="modal-close-btn">&times;</button>
        </div>
        <div class="modal-body-custom">
            <form method="POST" action="{{ route('pelanggan.pembayaran.upload', $pesanan->id) }}" enctype="multipart/form-data" id="formUploadBukti">
                @csrf
                <!-- Total -->
                <div style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9); padding: 10px 14px; border-radius: 10px; text-align: center; margin-bottom: 16px; border-left: 4px solid #2e7d32;">
                    <div style="font-size: 0.7rem; color: #2e7d32; margin-bottom: 4px;">Total yang harus dibayar</div>
                    <div style="font-size: 1.1rem; font-weight: 700; color: #1b5e20;">
                        Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Pilih Bank -->
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #555; font-size: 0.8rem;">Pilih Bank Tujuan <span style="color: red;">*</span></label>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @foreach($metodePembayaran as $bank)
                        @if($bank->status_aktif)
                        <div class="bank-card" data-bank-id="{{ $bank->id }}"
                            data-bank-nama="{{ $bank->nama_bank }}" data-bank-norek="{{ $bank->nomor_rekening }}"
                            data-bank-atas-nama="{{ $bank->atas_nama }}"
                            data-bank-logo="{{ $bank->logo_bank ? asset('storage/bank/' . $bank->logo_bank) : '' }}"
                            style="border: 1.5px solid #e5e7eb; border-radius: 12px; padding: 10px 12px; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 10px; background: white;">
                            <div style="flex-shrink: 0; width: 36px; height: 36px; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                @if($bank->logo_bank)
                                <img src="{{ asset('storage/bank/' . $bank->logo_bank) }}" alt="{{ $bank->nama_bank }}" style="width: 28px; height: 28px; object-fit: contain;">
                                @else
                                <i class="bi bi-bank2" style="font-size: 18px; color: #2e7d32;"></i>
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: #1b5e20; font-size: 0.8rem;">{{ $bank->nama_bank }}</div>
                                <div style="font-size: 0.65rem; color: #4b5563;">{{ $bank->nomor_rekening }}</div>
                                <div style="font-size: 0.6rem; color: #4b5563;">a.n. {{ $bank->atas_nama }}</div>
                            </div>
                            <div class="radio-check" style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid #d1d5db; background: white; flex-shrink: 0;"></div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    <input type="hidden" name="id_metode_pembayaran" id="selectedBankId" required>
                </div>

                <!-- Info Bank -->
                <div id="selectedBankInfo" style="display: none; background: #f0fdf4; border-radius: 10px; padding: 8px 12px; margin-bottom: 16px; border-left: 4px solid #ffc107;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img id="selectedBankLogo" src="" alt="" style="width: 28px; height: 28px; object-fit: contain; border-radius: 6px;">
                        <div>
                            <div class="fw-bold text-success" id="selectedBankNama" style="font-size: 0.75rem;"></div>
                            <div class="small" id="selectedBankNoRek" style="font-size: 0.6rem; color: #4b5563;"></div>
                            <div class="small" id="selectedBankAtasNama" style="font-size: 0.55rem; color: #4b5563;"></div>
                        </div>
                    </div>
                </div>

                <!-- Upload File -->
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #555; font-size: 0.8rem;">Upload Bukti Transfer <span style="color: red;">*</span></label>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                        title="Pilih file bukti transfer (JPG/PNG, maks 2MB)"
                        style="width: 100%; padding: 8px 10px; border-radius: 10px; border: 1.5px solid #e5e7eb; font-size: 0.8rem; background: #f8f9fa;"
                        accept="image/*" required>
                    <div style="font-size: 0.6rem; color: #9ca3af; margin-top: 4px;">
                        <i class="bi bi-info-circle"></i> Format: JPG, PNG. Maks: 2MB
                    </div>
                </div>

                <!-- Footer sticky -->
                <div class="modal-footer-sticky">
                    <button type="button" onclick="closeUploadModal()" class="btn-modal-cancel">Batal</button>
                    <button type="submit" class="btn-modal-submit">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL EDIT BUKTI PEMBAYARAN - DIPERBAIKI                    -->
<!-- ============================================================ -->
<div id="editBuktiModal" class="modal-overlay-custom">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h5><i class="bi bi-pencil-square me-2"></i> Edit Bukti Pembayaran</h5>
            <button type="button" onclick="closeEditBuktiModal()" class="modal-close-btn">&times;</button>
        </div>
        <div class="modal-body-custom">
            <form method="POST" action="{{ route('pelanggan.pembayaran.update-bukti', $pesanan->id) }}" enctype="multipart/form-data" id="formEditBukti">
                @csrf
                @method('PUT')
                <!-- Total -->
                <div style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9); padding: 10px 14px; border-radius: 10px; text-align: center; margin-bottom: 16px; border-left: 4px solid #2e7d32;">
                    <div style="font-size: 0.7rem; color: #2e7d32; margin-bottom: 4px;">Total yang harus dibayar</div>
                    <div style="font-size: 1.1rem; font-weight: 700; color: #1b5e20;">
                        Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Pilih Bank -->
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #555; font-size: 0.8rem;">Pilih Bank Tujuan <span style="color: red;">*</span></label>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @foreach($metodePembayaran as $bank)
                        @if($bank->status_aktif)
                        <div class="bank-card-edit" data-bank-id="{{ $bank->id }}"
                            data-bank-nama="{{ $bank->nama_bank }}" data-bank-norek="{{ $bank->nomor_rekening }}"
                            data-bank-atas-nama="{{ $bank->atas_nama }}"
                            data-bank-logo="{{ $bank->logo_bank ? asset('storage/bank/' . $bank->logo_bank) : '' }}"
                            style="border: 1.5px solid #e5e7eb; border-radius: 12px; padding: 10px 12px; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 10px; background: white;">
                            <div style="flex-shrink: 0; width: 36px; height: 36px; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                @if($bank->logo_bank)
                                <img src="{{ asset('storage/bank/' . $bank->logo_bank) }}" alt="{{ $bank->nama_bank }}" style="width: 28px; height: 28px; object-fit: contain;">
                                @else
                                <i class="bi bi-bank2" style="font-size: 18px; color: #2e7d32;"></i>
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: #1b5e20; font-size: 0.8rem;">{{ $bank->nama_bank }}</div>
                                <div style="font-size: 0.65rem; color: #4b5563;">{{ $bank->nomor_rekening }}</div>
                                <div style="font-size: 0.6rem; color: #4b5563;">a.n. {{ $bank->atas_nama }}</div>
                            </div>
                            <div class="radio-check-edit" style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid #d1d5db; background: white; flex-shrink: 0;"></div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    <input type="hidden" name="id_metode_pembayaran" id="editSelectedBankId" required>
                </div>

                <!-- Info Bank -->
                <div id="editSelectedBankInfo" style="display: none; background: #f0fdf4; border-radius: 10px; padding: 8px 12px; margin-bottom: 16px; border-left: 4px solid #ffc107;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img id="editSelectedBankLogo" src="" alt="" style="width: 28px; height: 28px; object-fit: contain; border-radius: 6px;">
                        <div>
                            <div class="fw-bold text-success" id="editSelectedBankNama" style="font-size: 0.75rem;"></div>
                            <div class="small" id="editSelectedBankNoRek" style="font-size: 0.6rem; color: #4b5563;"></div>
                            <div class="small" id="editSelectedBankAtasNama" style="font-size: 0.55rem; color: #4b5563;"></div>
                        </div>
                    </div>
                </div>

                <!-- Upload File -->
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #555; font-size: 0.8rem;">Upload Bukti Transfer Baru <span style="color: red;">*</span></label>
                    <input type="file" name="bukti_pembayaran" id="edit_bukti_pembayaran"
                        title="Pilih file bukti transfer baru (JPG/PNG, maks 2MB)"
                        style="width: 100%; padding: 8px 10px; border-radius: 10px; border: 1.5px solid #e5e7eb; font-size: 0.8rem; background: #f8f9fa;"
                        accept="image/*" required>
                    <div style="font-size: 0.6rem; color: #9ca3af; margin-top: 4px;">
                        <i class="bi bi-info-circle"></i> Format: JPG, PNG. Maks: 2MB
                    </div>
                </div>

                <!-- Footer sticky -->
                <div class="modal-footer-sticky">
                    <button type="button" onclick="closeEditBuktiModal()" class="btn-modal-cancel">Batal</button>
                    <button type="submit" class="btn-modal-submit">Perbarui Bukti</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI BATALKAN PESANAN -->
<div id="confirmCancelModal"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 100000; margin: 0; padding: 0;">
    <div style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 20px; box-sizing: border-box;">
        <div style="background: white; border-radius: 28px; max-width: 400px; width: 100%; margin: 0 auto; box-shadow: 0 30px 60px rgba(0,0,0,0.4); overflow: hidden;">
            <div style="background: linear-gradient(135deg, #dc3545, #c62828); color: white; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; font-size: 1rem; font-weight: 600;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Batalkan Pesanan
                </h5>
                <button type="button" onclick="closeConfirmCancelModal()"
                    style="background: rgba(255,255,255,0.2); border: none; color: white; width: 30px; height: 30px; border-radius: 50%; font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
            </div>
            <div style="padding: 24px; text-align: center;">
                <i class="bi bi-question-circle" style="font-size: 60px; color: #dc3545;"></i>
                <h5 class="mt-3">Apakah Anda yakin?</h5>
                <p class="text-muted">Pesanan ini akan dibatalkan. Tindakan ini tidak dapat dibatalkan.</p>
                <div class="d-flex gap-3 justify-content-center mt-4">
                    <button type="button" onclick="closeConfirmCancelModal()"
                        class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="button" id="confirmCancelBtn" class="btn btn-danger rounded-pill px-4">
                        <i class="bi bi-check-circle me-1"></i> Ya, Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* ========== BACKGROUND BATIK ========== */
.batik-bg {
    background: linear-gradient(135deg, #faf8f0 0%, #f5f0e6 50%, #fef9e6 100%);
    position: relative;
    min-height: 100vh;
}
.batik-bg::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200' opacity='0.04'%3E%3Cpath fill='%232e7d32' d='M50,30 L70,15 L90,30 L70,45 Z M100,60 L120,45 L140,60 L120,75 Z M150,90 L170,75 L190,90 L170,105 Z M60,100 L80,85 L100,100 L80,115 Z M110,130 L130,115 L150,130 L130,145 Z M30,140 L50,125 L70,140 L50,155 Z M80,170 L100,155 L120,170 L100,185 Z'/%3E%3Cpath fill='%23ffc107' d='M130,20 L145,30 L130,40 L115,30 Z M180,50 L195,60 L180,70 L165,60 Z M40,60 L55,70 L40,80 L25,70 Z M90,20 L105,30 L90,40 L75,30 Z'/%3E%3C/svg%3E");
    background-repeat: repeat;
    background-size: 180px;
    pointer-events: none;
}
.container.position-relative { position: relative; z-index: 2; }
.breadcrumb { background: white !important; border-radius: 50px !important; box-shadow: 0 2px 8px rgba(0,0,0,0.05) !important; }
.breadcrumb a { font-weight: 500; }
.breadcrumb a:hover { color: #ffc107 !important; }
.alert-custom { border-radius: 16px; padding: 14px 18px; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 12px; border: none; animation: slideIn 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
.alert-custom .alert-close { margin-left: auto; background: none; border: none; font-size: 20px; cursor: pointer; opacity: 0.7; transition: opacity 0.2s ease; color: inherit; }
.alert-custom .alert-close:hover { opacity: 1; }
.alert-success-custom { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #1b5e20; border-left: 4px solid #2e7d32; }
.alert-error-custom { background: linear-gradient(135deg, #ffebee, #ffcdd2); color: #c62828; border-left: 4px solid #d32f2f; }
.alert-warning-custom { background: linear-gradient(135deg, #fff8e1, #ffecb3); color: #e65100; border-left: 4px solid #ffc107; padding: 12px; border-radius: 12px; }
.alert-info-custom { background: linear-gradient(135deg, #e3f2fd, #bbdef5); color: #01579b; border-left: 4px solid #0288d1; padding: 12px; border-radius: 12px; }
.alert-expired-custom { background: linear-gradient(135deg, #fff8e1, #ffecb3); border-left: 4px solid #dc3545; border-radius: 12px; padding: 12px 16px; color: #856404; }
.alert-wa-custom { position: relative; background: linear-gradient(135deg, #25D366, #128C7E); border-radius: 16px; padding: 15px 20px; border: none; box-shadow: 0 4px 15px rgba(37,211,102,0.3); }
.alert-wa-custom .alert-wa-close { position: absolute; top: 12px; right: 15px; background: rgba(255,255,255,0.2); border: none; color: white; width: 28px; height: 28px; border-radius: 50%; font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
.alert-wa-custom .alert-wa-close:hover { background: rgba(255,255,255,0.3); }
.alert-terlambat-custom { position: relative; background: linear-gradient(135deg, #ffebee, #ffcdd2); border-left: 4px solid #dc3545; border-radius: 16px; padding: 15px 20px; }
.alert-terlambat-custom .alert-terlambat-close { position: absolute; top: 12px; right: 15px; background: rgba(220,53,69,0.1); border: none; color: #dc3545; width: 28px; height: 28px; border-radius: 50%; font-size: 18px; cursor: pointer; display: flex; align-items-center; justify-content: center; transition: all 0.2s; }
.alert-terlambat-custom .alert-terlambat-close:hover { background: rgba(220,53,69,0.2); }
@keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
.hover-card { transition: all 0.3s ease; }
.hover-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }
.badge-status-warning { background: linear-gradient(135deg, #f57c00, #ef6c00); color: white; padding: 8px 16px; border-radius: 50px; font-weight: 600; display: inline-block; }
.badge-status-primary { background: linear-gradient(135deg, #0d6efd, #0b5ed7); color: white; padding: 8px 16px; border-radius: 50px; font-weight: 600; display: inline-block; }
.badge-status-danger { background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 8px 16px; border-radius: 50px; font-weight: 600; display: inline-block; animation: pulse 1.5s infinite; }
.badge-status-success { background: linear-gradient(135deg, #198754, #157347); color: white; padding: 8px 16px; border-radius: 50px; font-weight: 600; display: inline-block; }
.badge-status-secondary { background: linear-gradient(135deg, #6c757d, #5a6268); color: white; padding: 8px 16px; border-radius: 50px; font-weight: 600; display: inline-block; }
.badge-status-info { background: linear-gradient(135deg, #0dcaf0, #0bb5d8); color: black; padding: 8px 16px; border-radius: 50px; font-weight: 600; display: inline-block; }
@keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.7; } 100% { opacity: 1; } }
.status-icon-success i { font-size: 3rem; color: #198754; }
.status-icon-warning i { font-size: 3rem; color: #ffc107; }
.sticky-lg-top { position: sticky; top: 20px; z-index: 10; }
.table { border-radius: 16px; overflow: hidden; }
.table th { font-weight: 600; }
.bg-success-subtle { background: #e8f5e9 !important; }

/* ============================================ */
/* BANK CARD - PERBAIKAN WARNA TEKS TIDAK BIRU */
/* ============================================ */
.bank-card {
    transition: all 0.2s ease;
    color: #1b5e20 !important;
}
.bank-card:hover {
    border-color: #ffc107 !important;
    background: #fffef5 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.bank-card.selected {
    border-color: #2e7d32 !important;
    background: #f0fff0 !important;
}
.bank-card.selected .radio-check {
    border-color: #2e7d32 !important;
    background: #2e7d32 !important;
    color: white;
}
.bank-card.selected .radio-check::after {
    content: "✓";
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.bank-card * {
    color: #1b5e20 !important;
    text-decoration: none !important;
}
.bank-card .fw-bold {
    color: #1b5e20 !important;
}
.bank-card div[style*="color: #6b7280;"],
.bank-card div[style*="color: #9ca3af;"] {
    color: #4b5563 !important;
}

.bank-card-edit {
    transition: all 0.2s ease;
    color: #1b5e20 !important;
}
.bank-card-edit:hover {
    border-color: #ffc107 !important;
    background: #fffef5 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.bank-card-edit.selected {
    border-color: #2e7d32 !important;
    background: #f0fff0 !important;
}
.bank-card-edit.selected .radio-check-edit {
    border-color: #2e7d32 !important;
    background: #2e7d32 !important;
    color: white;
}
.bank-card-edit.selected .radio-check-edit::after {
    content: "✓";
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.bank-card-edit * {
    color: #1b5e20 !important;
    text-decoration: none !important;
}
.bank-card-edit .fw-bold {
    color: #1b5e20 !important;
}
.bank-card-edit div[style*="color: #6b7280;"],
.bank-card-edit div[style*="color: #9ca3af;"] {
    color: #4b5563 !important;
}

/* ============================================ */
/* MODAL OVERLAY & CONTENT - DIPERBAIKI        */
/* ============================================ */
.modal-overlay-custom {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.85);
    z-index: 99999;
    overflow-y: auto;
    padding: 20px 15px;
    box-sizing: border-box;
    align-items: center;
    justify-content: center;
}
.modal-overlay-custom .modal-content-custom {
    background: white;
    border-radius: 24px;
    max-width: 480px;
    width: 100%;
    margin: auto;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    overflow: hidden;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
}
.modal-overlay-custom .modal-header-custom {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
    padding: 14px 20px;
    flex-shrink: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-overlay-custom .modal-header-custom h5 {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
    color: white;
}
.modal-overlay-custom .modal-close-btn {
    background: rgba(255,255,255,0.2);
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
.modal-overlay-custom .modal-close-btn:hover {
    background: rgba(255,255,255,0.3);
}
.modal-overlay-custom .modal-body-custom {
    padding: 16px 20px;
    overflow-y: auto;
    flex: 1;
}
.modal-overlay-custom .modal-footer-sticky {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    padding: 10px 0 2px 0;
    margin-top: 8px;
    border-top: 1px solid #f0f0f0;
    background: white;
    position: sticky;
    bottom: 0;
    z-index: 10;
}
.modal-overlay-custom .btn-modal-cancel {
    padding: 6px 16px;
    border-radius: 50px;
    border: 1px solid #e5e7eb;
    background: white;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.75rem;
}
.modal-overlay-custom .btn-modal-submit {
    padding: 6px 18px;
    border-radius: 50px;
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
    border: none;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.75rem;
}

/* Responsif layar kecil */
@media (max-width: 576px) {
    .modal-overlay-custom {
        padding: 10px 8px;
    }
    .modal-overlay-custom .modal-content-custom {
        max-width: 100%;
        border-radius: 18px;
        max-height: 90vh;
    }
    .modal-overlay-custom .modal-header-custom {
        padding: 12px 16px;
    }
    .modal-overlay-custom .modal-body-custom {
        padding: 12px 16px;
    }
    .modal-overlay-custom .modal-footer-sticky {
        flex-wrap: wrap;
        justify-content: center;
    }
    .modal-overlay-custom .modal-footer-sticky button {
        flex: 1 1 40%;
        text-align: center;
        justify-content: center;
    }
    .bank-card, .bank-card-edit {
        padding: 8px 10px !important;
        gap: 8px !important;
    }
    .bank-card > div:first-child, .bank-card-edit > div:first-child {
        width: 30px !important;
        height: 30px !important;
    }
    .bank-card > div:first-child img, .bank-card-edit > div:first-child img {
        width: 22px !important;
        height: 22px !important;
    }
}

@media (max-width: 768px) {
    .sticky-lg-top { position: relative; top: 0; margin-top: 20px; }
    .breadcrumb { font-size: 0.8rem; flex-wrap: wrap; }
    .table th, .table td { font-size: 0.8rem; padding: 8px; }
}

/* override existing styles untuk modal overlay agar tidak konflik */
#uploadModal, #editBuktiModal {
    display: none;
}

.custom-toast { position: fixed; top: 20px; right: 20px; min-width: 300px; max-width: 450px; background: white; border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); z-index: 100001; transform: translateX(120%); transition: transform 0.3s ease; border-left: 4px solid; }
.custom-toast.show { transform: translateX(0); }
.custom-toast.toast-success { border-left-color: #2e7d32; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); }
.custom-toast.toast-error { border-left-color: #dc3545; background: linear-gradient(135deg, #ffebee, #ffcdd2); }
.custom-toast .toast-icon i { font-size: 24px; }
.custom-toast.toast-success .toast-icon i { color: #2e7d32; }
.custom-toast.toast-error .toast-icon i { color: #dc3545; }
.custom-toast .toast-content { flex: 1; font-size: 14px; font-weight: 500; color: #333; }
.custom-toast .toast-close { background: none; border: none; font-size: 20px; cursor: pointer; opacity: 0.6; transition: opacity 0.2s; color: #333; }
.custom-toast .toast-close:hover { opacity: 1; }
</style>
@endpush

@push('scripts')
<script>
// ============================================================
// MODAL UPLOAD - FUNGSI OPEN & CLOSE
// ============================================================
function openUploadModal() {
    var modal = document.getElementById('uploadModal');
    if (modal) {
        if (modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        resetBankSelection();
        modal.scrollTop = 0;
    }
}

function closeUploadModal() {
    var modal = document.getElementById('uploadModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        resetBankSelection();
    }
}

function resetBankSelection() {
    var allCards = document.querySelectorAll('.bank-card');
    allCards.forEach(function(card) {
        card.classList.remove('selected');
    });
    var bankInfo = document.getElementById('selectedBankInfo');
    if (bankInfo) bankInfo.style.display = 'none';
    var selectedBankId = document.getElementById('selectedBankId');
    if (selectedBankId) selectedBankId.value = '';
}

// ============================================================
// MODAL EDIT BUKTI - FUNGSI OPEN & CLOSE
// ============================================================
function openEditBuktiModal() {
    var modal = document.getElementById('editBuktiModal');
    if (modal) {
        if (modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        resetEditBankSelection();
        modal.scrollTop = 0;
    }
}

function closeEditBuktiModal() {
    var modal = document.getElementById('editBuktiModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        resetEditBankSelection();
    }
}

function resetEditBankSelection() {
    var allCards = document.querySelectorAll('.bank-card-edit');
    allCards.forEach(function(card) {
        card.classList.remove('selected');
    });
    var bankInfo = document.getElementById('editSelectedBankInfo');
    if (bankInfo) bankInfo.style.display = 'none';
    var selectedBankId = document.getElementById('editSelectedBankId');
    if (selectedBankId) selectedBankId.value = '';
}

window.onclick = function(event) {
    var modal = document.getElementById('uploadModal');
    if (event.target == modal) { closeUploadModal(); }
    var cancelModal = document.getElementById('confirmCancelModal');
    if (event.target == cancelModal) { closeConfirmCancelModal(); }
    var editModal = document.getElementById('editBuktiModal');
    if (event.target == editModal) { closeEditBuktiModal(); }
};

// ============================================================
// PILIH BANK DARI KARTU - UPLOAD
// ============================================================
function initBankCardSelection() {
    var bankCards = document.querySelectorAll('.bank-card');
    var selectedBankIdInput = document.getElementById('selectedBankId');
    var selectedBankInfo = document.getElementById('selectedBankInfo');
    var selectedBankLogo = document.getElementById('selectedBankLogo');
    var selectedBankNama = document.getElementById('selectedBankNama');
    var selectedBankNoRek = document.getElementById('selectedBankNoRek');
    var selectedBankAtasNama = document.getElementById('selectedBankAtasNama');

    function removeAllHighlights() {
        bankCards.forEach(function(card) {
            card.classList.remove('selected');
        });
    }

    function showSelectedBankInfo(card) {
        var bankId = card.getAttribute('data-bank-id');
        var bankNama = card.getAttribute('data-bank-nama');
        var bankNoRek = card.getAttribute('data-bank-norek');
        var bankAtasNama = card.getAttribute('data-bank-atas-nama');
        var bankLogo = card.getAttribute('data-bank-logo');
        if (selectedBankIdInput) selectedBankIdInput.value = bankId;
        if (bankLogo && bankLogo !== '') {
            selectedBankLogo.src = bankLogo;
            selectedBankLogo.style.display = 'block';
        } else {
            selectedBankLogo.style.display = 'none';
        }
        selectedBankNama.innerText = bankNama;
        selectedBankNoRek.innerText = bankNoRek;
        selectedBankAtasNama.innerText = 'a.n. ' + bankAtasNama;
        selectedBankInfo.style.display = 'block';
    }

    bankCards.forEach(function(card) {
        card.addEventListener('click', function() {
            removeAllHighlights();
            card.classList.add('selected');
            showSelectedBankInfo(this);
        });
    });
}

// ============================================================
// PILIH BANK DARI KARTU - EDIT
// ============================================================
function initEditBankCardSelection() {
    var bankCards = document.querySelectorAll('.bank-card-edit');
    var selectedBankIdInput = document.getElementById('editSelectedBankId');
    var selectedBankInfo = document.getElementById('editSelectedBankInfo');
    var selectedBankLogo = document.getElementById('editSelectedBankLogo');
    var selectedBankNama = document.getElementById('editSelectedBankNama');
    var selectedBankNoRek = document.getElementById('editSelectedBankNoRek');
    var selectedBankAtasNama = document.getElementById('editSelectedBankAtasNama');

    function removeAllHighlights() {
        bankCards.forEach(function(card) {
            card.classList.remove('selected');
        });
    }

    function showSelectedBankInfo(card) {
        var bankId = card.getAttribute('data-bank-id');
        var bankNama = card.getAttribute('data-bank-nama');
        var bankNoRek = card.getAttribute('data-bank-norek');
        var bankAtasNama = card.getAttribute('data-bank-atas-nama');
        var bankLogo = card.getAttribute('data-bank-logo');
        if (selectedBankIdInput) selectedBankIdInput.value = bankId;
        if (bankLogo && bankLogo !== '') {
            selectedBankLogo.src = bankLogo;
            selectedBankLogo.style.display = 'block';
        } else {
            selectedBankLogo.style.display = 'none';
        }
        selectedBankNama.innerText = bankNama;
        selectedBankNoRek.innerText = bankNoRek;
        selectedBankAtasNama.innerText = 'a.n. ' + bankAtasNama;
        selectedBankInfo.style.display = 'block';
    }

    bankCards.forEach(function(card) {
        card.addEventListener('click', function() {
            removeAllHighlights();
            card.classList.add('selected');
            showSelectedBankInfo(this);
        });
    });
}

// ============================================================
// MODAL KONFIRMASI BATALKAN PESANAN
// ============================================================
var pesananToCancel = null;

function openConfirmCancelModal() {
    var modal = document.getElementById('confirmCancelModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeConfirmCancelModal() {
    var modal = document.getElementById('confirmCancelModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    pesananToCancel = null;
}

function batalkanPesanan(btn) {
    pesananToCancel = btn;
    openConfirmCancelModal();
}

document.getElementById('confirmCancelBtn')?.addEventListener('click', function() {
    if (pesananToCancel) {
        const btn = pesananToCancel;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
        btn.disabled = true;

        fetch('{{ route("pelanggan.pesanan.batalkan", $pesanan->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            closeConfirmCancelModal();
            if (data.success) {
                showCustomToast('success', data.message);
                setTimeout(function() { location.reload(); }, 1500);
            } else {
                showCustomToast('error', data.message || 'Gagal membatalkan pesanan');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            closeConfirmCancelModal();
            showCustomToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
});

// ============================================================
// CUSTOM TOAST NOTIFICATION
// ============================================================
function showCustomToast(type, message) {
    var toastDiv = document.createElement('div');
    toastDiv.className = 'custom-toast ' + (type === 'success' ? 'toast-success' : 'toast-error');
    var icon = document.createElement('div');
    icon.className = 'toast-icon';
    icon.innerHTML = type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-triangle"></i>';
    var content = document.createElement('div');
    content.className = 'toast-content';
    content.innerText = message;
    var closeBtn = document.createElement('button');
    closeBtn.className = 'toast-close';
    closeBtn.innerHTML = '&times;';
    closeBtn.onclick = function() { toastDiv.remove(); };
    toastDiv.appendChild(icon);
    toastDiv.appendChild(content);
    toastDiv.appendChild(closeBtn);
    document.body.appendChild(toastDiv);
    setTimeout(function() { toastDiv.classList.add('show'); }, 10);
    setTimeout(function() {
        if (toastDiv.parentNode) {
            toastDiv.classList.remove('show');
            setTimeout(function() { toastDiv.remove(); }, 300);
        }
    }, 3000);
}

// ============================================================
// VALIDASI FORM UPLOAD
// ============================================================
document.getElementById('formUploadBukti')?.addEventListener('submit', function(e) {
    var selectedBankId = document.getElementById('selectedBankId');
    var fileInput = document.getElementById('bukti_pembayaran');
    if (!selectedBankId || !selectedBankId.value) {
        e.preventDefault();
        showCustomToast('error', 'Silakan pilih bank tujuan terlebih dahulu!');
        return false;
    }
    if (fileInput && fileInput.files.length > 0) {
        var file = fileInput.files[0];
        var validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        var maxSize = 2 * 1024 * 1024;
        if (!validTypes.includes(file.type)) {
            e.preventDefault();
            showCustomToast('error', 'Format file harus JPG, JPEG, atau PNG!');
            return false;
        }
        if (file.size > maxSize) {
            e.preventDefault();
            showCustomToast('error', 'Ukuran file maksimal 2MB!');
            return false;
        }
    } else {
        e.preventDefault();
        showCustomToast('error', 'Silakan pilih file bukti pembayaran!');
        return false;
    }
});

document.getElementById('formEditBukti')?.addEventListener('submit', function(e) {
    var selectedBankId = document.getElementById('editSelectedBankId');
    var fileInput = document.getElementById('edit_bukti_pembayaran');
    if (!selectedBankId || !selectedBankId.value) {
        e.preventDefault();
        showCustomToast('error', 'Silakan pilih bank tujuan terlebih dahulu!');
        return false;
    }
    if (fileInput && fileInput.files.length > 0) {
        var file = fileInput.files[0];
        var validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        var maxSize = 2 * 1024 * 1024;
        if (!validTypes.includes(file.type)) {
            e.preventDefault();
            showCustomToast('error', 'Format file harus JPG, JPEG, atau PNG!');
            return false;
        }
        if (file.size > maxSize) {
            e.preventDefault();
            showCustomToast('error', 'Ukuran file maksimal 2MB!');
            return false;
        }
    } else {
        e.preventDefault();
        showCustomToast('error', 'Silakan pilih file bukti pembayaran!');
        return false;
    }
});

// ============================================================
// INISIALISASI
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    initBankCardSelection();
    initEditBankCardSelection();
});
</script>
@endpush
@endsection