@extends('layouts.admin')

@section('title', 'Edit Custom Snackbox')

@section('content')
<div class="edit-snackbox-container">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold mb-1">
                <i class="bi bi-box-seam me-2" style="color: #ffc107;"></i>
                Edit Custom Snackbox
            </h2>
            <p class="text-muted mb-0">Edit isian dan jumlah snackbox pesanan</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <a href="{{ route('admin.pesanan.edit', $customSnackbox->detailPesanan->first()->pesanan_id ?? '#') }}"
                class="btn-back rounded-pill px-4 py-2">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Edit Pesanan
            </a>
        </div>
    </div>

    <div class="card-snackbox card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
            <div class="d-flex align-items-center gap-2">
                <div class="header-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h5 class="mb-0 fw-bold" style="color: #1b5e20;">Form Edit Custom Snackbox</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.custom-snackbox.update', $customSnackbox->id) }}" id="formEdit"
                novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Ukuran Box -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_kode_ukuran">
                            <i class="bi bi-box text-success me-1"></i> Ukuran Box
                        </label>
                        <div class="select-wrapper">
                            <select name="kode_ukuran" id="kode_ukuran" class="form-select rounded-3 snackbox-select"
                                required>
                                @foreach($ukuran as $kode => $data)
                                <option value="{{ $kode }}" data-kapasitas="{{ $data['jumlah_item'] }}"
                                    {{ $customSnackbox->kode_ukuran == $kode ? 'selected' : '' }}>
                                    {{ $data['nama'] }} (Kapasitas: {{ $data['jumlah_item'] }} item, Harga: Rp
                                    {{ number_format($data['harga'], 0, ',', '.') }})
                                </option>
                                @endforeach
                            </select>
                            <i class="bi bi-chevron-down select-icon"></i>
                        </div>
                    </div>

                    <!-- Jumlah Box -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_jumlah_box">
                            <i class="bi bi-calculator text-info me-1"></i> Jumlah Box
                        </label>
                        <input type="number" name="jumlah_box" id="jumlah_box"
                            class="form-control rounded-3 quantity-input" value="{{ $customSnackbox->jumlah_box }}"
                            required min="1">
                    </div>

                    <!-- Kapasitas Info -->
                    <div class="col-12">
                        <div class="kapasitas-info">
                            <i class="bi bi-info-circle-fill text-warning me-2"></i>
                            Kapasitas box: <strong
                                id="kapasitasBoxDisplay">{{ $ukuran[$customSnackbox->kode_ukuran]['jumlah_item'] }}</strong>
                            item.
                            Silakan pilih tepat <strong
                                id="kapasitasBoxText">{{ $ukuran[$customSnackbox->kode_ukuran]['jumlah_item'] }}</strong>
                            jajanan.
                        </div>
                    </div>

                    <!-- Pilih Jajanan -->
                    <div class="col-12">
                        <label class="form-label fw-semibold mb-2">
                            <i class="bi bi-egg-fried text-success me-1"></i> Pilih Jajanan (Isi Snackbox)
                        </label>
                        <small class="text-muted d-block mb-3">Pilih jajanan yang akan dimasukkan ke dalam snackbox
                            (maksimal sesuai kapasitas box)</small>

                        <div class="jajanan-grid">
                            @foreach($daftarJajanan as $jajanan)
                            <div class="jajanan-card">
                                <input type="checkbox" name="jajanan[]" value="{{ $jajanan->id }}"
                                    class="jajanan-checkbox" id="jajanan_{{ $jajanan->id }}"
                                    data-harga="{{ $jajanan->harga }}"
                                    {{ in_array($jajanan->id, $selectedJajananIds) ? 'checked' : '' }}>
                                <label class="jajanan-label" for="jajanan_{{ $jajanan->id }}">
                                    <div class="jajanan-name">{{ $jajanan->nama_produk }}</div>
                                    <div class="jajanan-price">Rp {{ number_format($jajanan->harga, 0, ',', '.') }}
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <div id="peringatanKapasitas" class="alert-warning-custom mt-3" style="display: none;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <span id="warningMessage"></span>
                        </div>
                    </div>

                    <!-- Ringkasan Harga -->
                    <div class="col-12">
                        <div class="ringkasan-harga">
                            <h6 class="ringkasan-title">
                                <i class="bi bi-calculator-fill me-2"></i> Ringkasan Harga
                            </h6>
                            <div class="ringkasan-list">
                                <div class="ringkasan-item">
                                    <span>Harga Box</span>
                                    <span class="ringkasan-value" id="hargaBox">Rp 0</span>
                                </div>
                                <div class="ringkasan-item">
                                    <span>Total Harga Jajanan</span>
                                    <span class="ringkasan-value" id="totalJajanan">Rp 0</span>
                                </div>
                                <div class="ringkasan-divider"></div>
                                <div class="ringkasan-item">
                                    <span>Harga per Box</span>
                                    <span class="ringkasan-value fw-bold text-success" id="hargaPerBox">Rp 0</span>
                                </div>
                                <div class="ringkasan-item">
                                    <span>Total Keseluruhan (<span
                                            id="jumlahBoxTotal">{{ $customSnackbox->jumlah_box }}</span> box)</span>
                                    <span class="ringkasan-value fw-bold text-primary fs-5" id="hargaTotal">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="col-12">
                        <div class="action-buttons">
                            <a href="{{ route('admin.pesanan.edit', $customSnackbox->detailPesanan->first()->pesanan_id ?? '#') }}"
                                class="btn-cancel">
                                <i class="bi bi-x-circle me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn-update" id="btnSubmit">
                                <i class="bi bi-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS untuk Custom Toast Notification -->
<style>
/* Custom Toast */
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
</style>

@push('styles')
<style>
/* ============================================ */
/* EDIT SNACKBOX CONTAINER */
/* ============================================ */
.edit-snackbox-container {
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
/* CARD SNACKBOX */
/* ============================================ */
.card-snackbox {
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
/* SELECT WRAPPER - DROPDOWN RAPI */
/* ============================================ */
.select-wrapper {
    position: relative;
    width: 100%;
}

.snackbox-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-color: white !important;
    border: 1.5px solid #e5e7eb !important;
    border-radius: 12px !important;
    padding: 10px 35px 10px 14px !important;
    font-size: 0.9rem !important;
    color: #333 !important;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}

.snackbox-select:hover {
    border-color: #ffc107 !important;
    background-color: #fffbeb !important;
}

.snackbox-select:focus {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
    outline: none;
}

.select-icon {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #2e7d32;
    font-size: 0.9rem;
    pointer-events: none;
    transition: transform 0.3s ease;
}

.snackbox-select:focus+.select-icon {
    transform: translateY(-50%) rotate(180deg);
    color: #ffc107;
}

/* ============================================ */
/* QUANTITY INPUT */
/* ============================================ */
.quantity-input {
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.quantity-input:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    outline: none;
}

/* ============================================ */
/* KAPASITAS INFO */
/* ============================================ */
.kapasitas-info {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border-radius: 12px;
    padding: 12px 16px;
    color: #2e7d32;
    font-size: 0.9rem;
    border-left: 4px solid #ffc107;
}

/* ============================================ */
/* JAJANAN GRID */
/* ============================================ */
.jajanan-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 12px;
    margin-bottom: 16px;
}

.jajanan-card {
    position: relative;
}

.jajanan-checkbox {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.jajanan-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 14px;
    background: white;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.jajanan-label:hover {
    border-color: #ffc107;
    background: #fffbeb;
    transform: translateY(-1px);
}

.jajanan-checkbox:checked+.jajanan-label {
    border-color: #2e7d32;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    box-shadow: 0 2px 8px rgba(46, 125, 50, 0.15);
}

.jajanan-name {
    font-weight: 500;
    color: #333;
    font-size: 0.85rem;
}

.jajanan-price {
    font-size: 0.75rem;
    font-weight: 600;
    color: #2e7d32;
}

/* ============================================ */
/* ALERT WARNING */
/* ============================================ */
.alert-warning-custom {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    color: #e65100;
    padding: 12px 16px;
    border-radius: 12px;
    border-left: 4px solid #ffc107;
    font-size: 0.85rem;
}

/* ============================================ */
/* RINGKASAN HARGA */
/* ============================================ */
.ringkasan-harga {
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    border-radius: 16px;
    padding: 20px;
    border: 1px solid #e5e7eb;
}

.ringkasan-title {
    font-weight: 700;
    color: #1b5e20;
    margin-bottom: 16px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ffc107;
}

.ringkasan-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.ringkasan-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
}

.ringkasan-value {
    font-weight: 600;
    color: #333;
}

.ringkasan-divider {
    height: 1px;
    background: linear-gradient(90deg, #e5e7eb, #ffc107, #e5e7eb);
    margin: 8px 0;
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

.btn-update:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* ============================================ */
/* RESPONSIVE */
/* ============================================ */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.3rem;
    }

    .jajanan-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-cancel,
    .btn-update {
        width: 100%;
        text-align: center;
    }

    .ringkasan-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
}
</style>
@endpush

@push('scripts')
<script>
const ukuranData = @json($ukuran);

function hitungHarga() {
    let kodeUkuran = document.getElementById('kode_ukuran').value;
    let jumlahBox = parseInt(document.getElementById('jumlah_box').value) || 1;
    let selectedJajanan = document.querySelectorAll('.jajanan-checkbox:checked');

    let hargaBox = ukuranData[kodeUkuran]?.harga || 0;
    let kapasitas = ukuranData[kodeUkuran]?.jumlah_item || 0;
    let selectedCount = selectedJajanan.length;

    document.getElementById('kapasitasBoxDisplay').innerText = kapasitas;
    document.getElementById('kapasitasBoxText').innerText = kapasitas;
    document.getElementById('jumlahBoxTotal').innerText = jumlahBox;

    let totalHargaJajanan = 0;
    selectedJajanan.forEach(checkbox => {
        let harga = parseInt(checkbox.getAttribute('data-harga')) || 0;
        totalHargaJajanan += harga;
    });

    let hargaPerBox = hargaBox + totalHargaJajanan;
    let hargaTotal = hargaPerBox * jumlahBox;

    document.getElementById('hargaBox').innerText = formatRupiah(hargaBox);
    document.getElementById('totalJajanan').innerText = formatRupiah(totalHargaJajanan);
    document.getElementById('hargaPerBox').innerText = formatRupiah(hargaPerBox);
    document.getElementById('hargaTotal').innerText = formatRupiah(hargaTotal);

    let warningDiv = document.getElementById('peringatanKapasitas');
    let warningMsg = document.getElementById('warningMessage');
    let btnSubmit = document.getElementById('btnSubmit');

    if (selectedCount !== kapasitas) {
        warningDiv.style.display = 'block';
        if (selectedCount < kapasitas) {
            warningMsg.innerHTML =
                `Kapasitas box adalah ${kapasitas} item, tetapi Anda hanya memilih ${selectedCount} item. Silakan pilih ${kapasitas - selectedCount} jajanan lagi.`;
        } else {
            warningMsg.innerHTML =
                `Kapasitas box adalah ${kapasitas} item, tetapi Anda memilih ${selectedCount} item. Silakan hapus ${selectedCount - kapasitas} jajanan.`;
        }
        btnSubmit.disabled = true;
    } else {
        warningDiv.style.display = 'none';
        btnSubmit.disabled = false;
    }
}

function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// ============================================================
// SUBMIT FORM AJAX
// ============================================================
document.getElementById('formEdit')?.addEventListener('submit', function(e) {
    e.preventDefault();

    var btnSubmit = document.getElementById('btnSubmit');
    var originalText = btnSubmit.innerHTML;

    btnSubmit.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Menyimpan...';
    btnSubmit.disabled = true;

    var formData = new FormData(this);

    fetch('{{ route("admin.custom-snackbox.update", $customSnackbox->id) }}', {
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

    return false;
});

// Event listeners untuk hitung harga
document.getElementById('kode_ukuran').addEventListener('change', hitungHarga);
document.getElementById('jumlah_box').addEventListener('input', hitungHarga);
document.querySelectorAll('.jajanan-checkbox').forEach(cb => {
    cb.addEventListener('change', hitungHarga);
});

// ============================================================
// CUSTOM TOAST NOTIFICATION
// ============================================================
function showCustomToast(type, title, message) {
    var existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => toast.remove());

    var toastDiv = document.createElement('div');
    toastDiv.className = 'custom-toast toast-' + type;

    var icon = document.createElement('div');
    icon.className = 'toast-icon';
    icon.innerHTML = type === 'success' ? '<i class="fas fa-check-circle"></i>' :
        '<i class="fas fa-exclamation-triangle"></i>';

    var content = document.createElement('div');
    content.className = 'toast-content';
    content.innerHTML = '<div class="toast-title">' + title + '</div><div class="toast-message">' + message + '</div>';

    var closeBtn = document.createElement('button');
    closeBtn.className = 'toast-close';
    closeBtn.innerHTML = '&times;';
    closeBtn.onclick = () => toastDiv.remove();

    toastDiv.appendChild(icon);
    toastDiv.appendChild(content);
    toastDiv.appendChild(closeBtn);
    document.body.appendChild(toastDiv);

    setTimeout(() => toastDiv.classList.add('show'), 10);
    setTimeout(() => {
        if (toastDiv.parentNode) {
            toastDiv.classList.remove('show');
            setTimeout(() => toastDiv.remove(), 300);
        }
    }, 3000);
}

// Hitung harga awal
hitungHarga();
</script>
@endpush
@endsection