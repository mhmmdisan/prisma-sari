@extends('layouts.app')

@section('title', 'Edit Pesanan #' . $pesanan->nomor_pesanan)

@section('content')
<div class="batik-bg py-4">
    <div class="container position-relative" style="z-index: 1;">
        <!-- Alert Success/Error -->
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

        @if($errors->any())
        <div class="alert-custom alert-error-custom mb-4">
            <i class="fas fa-exclamation-triangle"></i>
            <div class="alert-content">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="alert-close" onclick="this.closest('.alert-custom').remove()">&times;</button>
        </div>
        @endif

        <div class="row">
            <div class="col-md-8 mx-auto">
                <!-- SATU FORM UNTUK SEMUA DATA -->
                <form method="POST" action="{{ route('pelanggan.pesanan.update', $pesanan->id) }}" id="formEditPesanan">
                    @csrf
                    @method('PUT')

                    <div class="card border-0 rounded-4 shadow-sm hover-card">
                        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                            <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                                <i class="bi bi-pencil-square me-2"></i> Form Edit Pesanan
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Informasi Batas Waktu -->
                            <div class="alert-info-custom mb-4"
                                style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-radius: 12px; padding: 12px 16px; border-left: 4px solid #ffc107;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-info-circle-fill text-success fs-5"></i>
                                    <div>
                                        <span class="fw-semibold">Batas waktu pembayaran:</span>
                                        <strong
                                            class="text-success">{{ \Carbon\Carbon::parse($pesanan->expired_at)->locale('id')->translatedFormat('d F Y H:i') }}
                                            WIB</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal Pengambilan -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold" style="color: #555;" id="label_tanggal">
                                    <i class="bi bi-calendar-event me-2 text-warning"></i>Tanggal & Jam Pengambilan
                                </label>
                                <input type="datetime-local" name="tanggal_pengambilan" id="tanggal_pengambilan"
                                    class="form-control rounded-3" required
                                    min="{{ date('Y-m-d\TH:i', strtotime('+2 days 05:00')) }}"
                                    max="{{ date('Y-m-d\TH:i', strtotime('+30 days 17:00')) }}"
                                    value="{{ date('Y-m-d\TH:i', strtotime($pesanan->tanggal_pengambilan)) }}"
                                    style="border: 2px solid #ffc107; padding: 12px;">
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle"></i> Minimal H+2, jam operasional 05:00 - 17:00 WIB
                                </small>
                                <div class="invalid-feedback-custom" id="error_tanggal"
                                    style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                                    <i class="bi bi-exclamation-circle me-1"></i> Tanggal pengambilan harus diisi!
                                </div>
                            </div>

                            <!-- Alamat Pengiriman -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold" style="color: #555;" id="label_alamat">
                                    <i class="bi bi-geo-alt me-2 text-danger"></i>Alamat Pengiriman
                                </label>
                                <textarea name="alamat_pengiriman" id="alamat_pengiriman" class="form-control rounded-3"
                                    rows="3" style="border: 1px solid #ddd; resize: vertical; padding: 12px;"
                                    placeholder="Masukkan alamat lengkap pengiriman...">{{ $pesanan->alamat_pengiriman }}</textarea>
                                <div class="invalid-feedback-custom" id="error_alamat"
                                    style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                                    <i class="bi bi-exclamation-circle me-1"></i> Alamat pengiriman harus diisi!
                                </div>
                            </div>

                            <!-- Catatan Pesanan -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold" style="color: #555;">
                                    <i class="bi bi-pencil-square me-2 text-info"></i>Catatan Pesanan
                                </label>
                                <textarea name="catatan_pesanan" class="form-control rounded-3" rows="2"
                                    style="border: 1px solid #ddd; resize: vertical; padding: 12px;"
                                    placeholder="Contoh: Tolong tambah sambal, packaging warna merah, dll">{{ $pesanan->catatan_pesanan }}</textarea>
                            </div>

                            <!-- Informasi Perubahan -->
                            <div class="alert-warning-custom mb-4"
                                style="background: linear-gradient(135deg, #fff8e1, #ffecb3); border-radius: 12px; padding: 12px 16px; border-left: 4px solid #ffc107;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                                    <div>
                                        <span class="fw-semibold">Informasi:</span>
                                        <span class="text-muted">Perubahan hanya dapat dilakukan sebelum melakukan
                                            pembayaran.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Pesanan dengan Edit Jumlah Produk -->
                    <div class="card border-0 rounded-4 shadow-sm mt-4 hover-card">
                        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                            <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                                <i class="bi bi-receipt me-2"></i> Ringkasan Pesanan
                            </h5>
                            <small class="text-muted">Anda dapat mengubah jumlah pesanan (tidak bisa menambah/hapus
                                item)</small>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted"><i class="bi bi-upc-scan me-1"></i> Nomor Pesanan</small>
                                    <p class="fw-bold text-success mb-0">{{ $pesanan->nomor_pesanan }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> Tanggal
                                        Pesanan</small>
                                    <p class="fw-bold mb-0">
                                        {{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->locale('id')->translatedFormat('d F Y H:i') }}
                                        WIB</p>
                                </div>
                            </div>

                            <!-- Detail Produk dengan Edit Jumlah -->
                            <div class="mb-3">
                                <small class="text-muted"><i class="bi bi-box-seam me-1 text-success"></i> Detail
                                    Produk</small>
                                <div id="detail-produk-container">
                                    @foreach($pesanan->detailPesanan as $index => $detail)
                                    <div class="detail-item mb-3 p-3 bg-light rounded-3" id="item-{{ $index }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-5">
                                                <div class="fw-semibold" data-nama="{{ $detail->nama_item }}">
                                                    {{ $detail->nama_item }}</div>
                                                <small class="text-muted">Harga: Rp
                                                    {{ number_format($detail->harga_satuan, 0, ',', '.') }}</small>
                                                <input type="hidden" name="detail_pesanan[{{ $index }}][nama_item]"
                                                    value="{{ $detail->nama_item }}">
                                                <input type="hidden" name="detail_pesanan[{{ $index }}][harga_satuan]"
                                                    value="{{ $detail->harga_satuan }}">
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary rounded-circle btn-kurang"
                                                        data-index="{{ $index }}" data-nama="{{ $detail->nama_item }}"
                                                        style="width: 32px; height: 32px;">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <input type="number" name="detail_pesanan[{{ $index }}][jumlah]"
                                                        class="form-control form-control-sm text-center jumlah-item"
                                                        value="{{ $detail->jumlah }}" min="1" style="width: 70px;"
                                                        required>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary rounded-circle btn-tambah"
                                                        data-index="{{ $index }}" style="width: 32px; height: 32px;">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-end">
                                                    <span class="fw-bold text-success subtotal-item"
                                                        data-subtotal="{{ $detail->subtotal }}">
                                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="total_harga" id="total_harga"
                                    value="{{ $pesanan->total_harga }}">
                            </div>

                            <div class="row mt-3 pt-2 border-top">
                                <div class="col-12 text-end">
                                    <strong class="text-success fs-5">Total: <span id="total_harga_display">Rp
                                            {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span></strong>
                                </div>
                            </div>

                            <!-- Tombol Aksi (Simpan) -->
                            <div class="d-flex justify-content-between gap-3 mt-4 pt-2">
                                <a href="{{ route('pelanggan.pesanan.show', $pesanan->id) }}"
                                    class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                    <i class="bi bi-arrow-left me-2"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-success rounded-pill px-4 py-2" id="btnSubmit"
                                    style="background: linear-gradient(135deg, #2e7d32, #1b5e20); border: none;">
                                    <i class="bi bi-save me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
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

.container.position-relative {
    position: relative;
    z-index: 2;
}

/* Alert Custom */
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

.alert-success-custom {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #1b5e20;
    border-left: 4px solid #2e7d32;
}

.alert-error-custom {
    background: linear-gradient(135deg, #ffebee, #ffcdd2);
    color: #c62828;
    border-left: 4px solid #d32f2f;
}

.alert-warning-custom {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    color: #e65100;
    border-left: 4px solid #ffc107;
    padding: 12px;
    border-radius: 12px;
}

.alert-info-custom {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #1b5e20;
    border-left: 4px solid #ffc107;
    padding: 12px;
    border-radius: 12px;
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

.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
}

.form-control {
    border-radius: 12px;
    border: 1.5px solid #e5e7eb;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
}

.form-control.error {
    border-color: #dc3545 !important;
    background-color: #fff5f5 !important;
}

.detail-item {
    transition: all 0.2s ease;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
}

.detail-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.btn-kurang,
.btn-tambah {
    transition: all 0.2s ease;
}

.btn-kurang:hover,
.btn-tambah:hover {
    background: #2e7d32;
    border-color: #2e7d32;
    color: white;
}

.custom-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    min-width: 280px;
    animation: slideIn 0.3s ease;
}

@media (max-width: 768px) {
    .breadcrumb {
        font-size: 0.8rem;
        flex-wrap: wrap;
    }

    .card-body {
        padding: 20px !important;
    }

    .custom-toast {
        left: 20px;
        right: 20px;
        min-width: auto;
    }
}
</style>
@endpush

@push('scripts')
<script>
// ============================================================
// DATA MINIMAL ORDER DARI CONTROLLER
// ============================================================
let minimalOrderData = @json($minimalOrderData ?? []);

function getMinimalOrder(namaItem) {
    return minimalOrderData[namaItem] || 50;
}

// ============================================================
// FUNGSI HITUNG TOTAL
// ============================================================
function hitungTotal() {
    let total = 0;
    document.querySelectorAll('.detail-item').forEach(item => {
        let jumlah = parseInt(item.querySelector('.jumlah-item').value) || 0;
        let hargaInput = item.querySelector('input[name*="[harga_satuan]"]');
        let harga = hargaInput ? parseInt(hargaInput.value) || 0 : 0;
        let subtotal = jumlah * harga;
        item.querySelector('.subtotal-item').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        total += subtotal;
    });
    document.getElementById('total_harga').value = total;
    document.getElementById('total_harga_display').innerText = 'Rp ' + total.toLocaleString('id-ID');
}

// ============================================================
// EVENT UNTUK PERUBAHAN JUMLAH
// ============================================================
$(document).on('input', '.jumlah-item', function() {
    hitungTotal();
});

// ============================================================
// TOMBOL KURANG (-) DENGAN VALIDASI MINIMAL ORDER
// ============================================================
$(document).on('click', '.btn-kurang', function() {
    let itemDiv = $(this).closest('.detail-item');
    let input = itemDiv.find('.jumlah-item');
    let currentVal = parseInt(input.val()) || 1;
    let namaItem = $(this).data('nama') || itemDiv.find('.fw-semibold').first().text().trim();
    let minOrder = getMinimalOrder(namaItem);

    if (currentVal > minOrder) {
        input.val(currentVal - 1);
        hitungTotal();
    } else {
        showCustomToast('warning', 'Perhatian!',
            `Minimal pesanan untuk "${namaItem}" adalah ${minOrder} item!`);
        input.val(minOrder);
    }
});

// ============================================================
// TOMBOL TAMBAH (+)
// ============================================================
$(document).on('click', '.btn-tambah', function() {
    let input = $(this).closest('.detail-item').find('.jumlah-item');
    let currentVal = parseInt(input.val()) || 1;
    if (currentVal < 999) {
        input.val(currentVal + 1);
        hitungTotal();
    } else {
        showCustomToast('warning', 'Perhatian!', 'Maksimal pesanan adalah 999 item!');
    }
});

// ============================================================
// VALIDASI INPUT MANUAL
// ============================================================
$(document).on('change', '.jumlah-item', function() {
    let itemDiv = $(this).closest('.detail-item');
    let input = $(this);
    let currentVal = parseInt(input.val()) || 1;
    let namaItem = itemDiv.find('.fw-semibold').first().text().trim();
    let minOrder = getMinimalOrder(namaItem);

    if (currentVal < minOrder) {
        showCustomToast('warning', 'Perhatian!',
            `Minimal pesanan untuk "${namaItem}" adalah ${minOrder} item!`);
        input.val(minOrder);
        hitungTotal();
    } else if (currentVal > 999) {
        input.val(999);
        hitungTotal();
    }
});

// ============================================================
// CUSTOM TOAST NOTIFICATION
// ============================================================
function showCustomToast(type, title, message) {
    $('.custom-toast').remove();
    let bgColor = type === 'warning' ? '#fff8e1' : '#ffebee';
    let borderColor = type === 'warning' ? '#ffc107' : '#dc3545';
    let icon = type === 'warning' ? 'bi-exclamation-triangle-fill text-warning' : 'bi-x-circle-fill text-danger';

    let toastHtml = `
        <div class="custom-toast" style="background: ${bgColor}; border-left: 4px solid ${borderColor}; border-radius: 12px; padding: 12px 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="bi ${icon}" style="font-size: 1.2rem;"></i>
                <div>
                    <strong style="font-size: 0.85rem; display: block;">${title}</strong>
                    <span style="font-size: 0.75rem;">${message}</span>
                </div>
            </div>
        </div>
    `;

    $('body').append(toastHtml);
    setTimeout(() => $('.custom-toast').fadeOut(300, function() {
        $(this).remove();
    }), 3000);
}

// ============================================================
// VALIDASI FORM UTAMA (Tanggal & Alamat) + Loading
// ============================================================
document.getElementById('formEditPesanan')?.addEventListener('submit', function(e) {
    var tanggalInput = document.getElementById('tanggal_pengambilan');
    var alamatInput = document.getElementById('alamat_pengiriman');
    var errorTanggal = document.getElementById('error_tanggal');
    var errorAlamat = document.getElementById('error_alamat');
    var isValid = true;

    tanggalInput.classList.remove('error');
    alamatInput.classList.remove('error');
    if (errorTanggal) errorTanggal.style.display = 'none';
    if (errorAlamat) errorAlamat.style.display = 'none';

    if (!tanggalInput.value) {
        e.preventDefault();
        tanggalInput.classList.add('error');
        if (errorTanggal) errorTanggal.style.display = 'block';
        isValid = false;
    }

    if (!alamatInput.value.trim()) {
        e.preventDefault();
        alamatInput.classList.add('error');
        if (errorAlamat) errorAlamat.style.display = 'block';
        isValid = false;
    }

    if (!isValid) {
        var firstError = document.querySelector('.form-control.error');
        if (firstError) firstError.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
        return false;
    }

    let btnSubmit = document.getElementById('btnSubmit');
    btnSubmit.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Menyimpan...';
    btnSubmit.disabled = true;
    return true;
});

// Hilangkan error styling saat user mulai mengetik
document.getElementById('tanggal_pengambilan')?.addEventListener('input', function() {
    this.classList.remove('error');
    var errorEl = document.getElementById('error_tanggal');
    if (errorEl) errorEl.style.display = 'none';
});

document.getElementById('alamat_pengiriman')?.addEventListener('input', function() {
    this.classList.remove('error');
    var errorEl = document.getElementById('error_alamat');
    if (errorEl) errorEl.style.display = 'none';
});

// Hitung total awal
hitungTotal();
</script>
@endpush
@endsection