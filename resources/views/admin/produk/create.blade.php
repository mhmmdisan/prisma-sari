@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="form-container">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold mb-1">
                <i class="bi bi-plus-circle me-2" style="color: #ffc107;"></i>
                Tambah Produk Baru
            </h2>
            <p class="text-muted mb-0">Isi form berikut untuk menambahkan produk baru</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <a href="{{ route('admin.produk.index') }}" class="btn-back rounded-pill px-4 py-2">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card-form card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white rounded-top-4 py-3 px-4" style="border-bottom: 2px solid #ffc107;">
            <div class="d-flex align-items-center gap-2">
                <div class="header-icon">
                    <i class="bi bi-plus-lg"></i>
                </div>
                <h5 class="mb-0 fw-bold" style="color: #1b5e20;">Form Produk Baru</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data"
                id="formTambahProduk" novalidate>
                @csrf

                <div class="row g-4">
                    <!-- Nama Produk -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2" for="nama_produk">
                            <i class="bi bi-tag text-success me-1"></i> Nama Produk
                        </label>
                        <input type="text" name="nama_produk" id="nama_produk"
                            class="form-control rounded-3 @error('nama_produk') is-invalid @enderror"
                            value="{{ old('nama_produk') }}" placeholder="Masukkan nama produk">
                        <div class="invalid-feedback-custom" id="error_nama_produk"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Nama produk tidak boleh kosong!
                        </div>
                        @error('nama_produk')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2" for="kategori_id">
                            <i class="bi bi-folder text-warning me-1"></i> Kategori
                        </label>
                        <select name="kategori_id" id="kategori_id"
                            class="form-select rounded-3 @error('kategori_id') is-invalid @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback-custom" id="error_kategori_id"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Kategori tidak boleh kosong!
                        </div>
                        @error('kategori_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Harga -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2" for="harga">
                            <i class="bi bi-currency-dollar text-primary me-1"></i> Harga
                        </label>
                        <input type="number" name="harga" id="harga"
                            class="form-control rounded-3 @error('harga') is-invalid @enderror"
                            value="{{ old('harga') }}" placeholder="Masukkan harga produk">
                        <div class="invalid-feedback-custom" id="error_harga"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Harga tidak boleh kosong!
                        </div>
                        @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Minimal Order -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2" for="min_order">
                            <i class="bi bi-calculator text-info me-1"></i> Minimal Order
                        </label>
                        <input type="number" name="min_order" id="min_order"
                            class="form-control rounded-3 @error('min_order') is-invalid @enderror"
                            value="{{ old('min_order', 50) }}" placeholder="Masukkan minimal order">
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle"></i> Untuk Paketan: sesuai kebutuhan (1,2,10,50, dll)
                        </small>
                        <div class="invalid-feedback-custom" id="error_min_order"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Minimal order tidak boleh kosong!
                        </div>
                        @error('min_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Khusus Snackbox (Checkbox) -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2" for="is_snackbox_only">
                            <i class="bi bi-box-seam text-purple me-1"></i> Khusus Snackbox
                        </label>
                        <div class="form-check form-switch mt-2">
                            <input type="checkbox" name="is_snackbox_only" id="is_snackbox_only"
                                class="form-check-input" value="1" style="width: 3rem; height: 1.5rem;">
                            <label class="form-check-label" for="is_snackbox_only">
                                Hanya tampil di halaman Custom Snackbox (tidak di daftar produk biasa)
                            </label>
                        </div>
                        <small class="text-muted d-block mt-1">
                            <i class="bi bi-info-circle"></i> Centang jika produk ini khusus untuk isian snackbox
                            (contoh: Air Mineral)
                        </small>
                    </div>

                    <!-- Deskripsi -->
                    <div class="col-12">
                        <label class="form-label fw-semibold mb-2" for="deskripsi">
                            <i class="bi bi-file-text me-1"></i> Deskripsi
                        </label>
                        <textarea name="deskripsi" id="deskripsi"
                            class="form-control rounded-3 @error('deskripsi') is-invalid @enderror" rows="4"
                            placeholder="Masukkan deskripsi produk">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gambar Produk -->
                    <div class="col-12">
                        <label class="form-label fw-semibold mb-2">
                            <i class="bi bi-image me-1"></i> Gambar Produk
                        </label>
                        <div class="upload-area">
                            <div class="upload-box" id="uploadBox"
                                onclick="document.getElementById('gambarInput').click()">
                                <i class="bi bi-cloud-upload upload-icon"></i>
                                <p class="upload-text">Klik untuk upload gambar</p>
                                <small class="upload-hint">Format: JPG, PNG. Maks: 2MB</small>
                            </div>
                            <input type="file" name="gambar" id="gambarInput"
                                class="form-control rounded-3 @error('gambar') is-invalid @enderror" accept="image/*"
                                style="display: none;">
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <div class="preview-container">
                                    <img id="previewImg" src="#" alt="Preview" class="preview-image">
                                    <button type="button" class="preview-remove" onclick="removeImage()">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('gambar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="action-buttons mt-4 pt-3 border-top">
                    <a href="{{ route('admin.produk.index') }}"
                        class="btn btn-outline-secondary rounded-pill px-4 py-2">
                        <i class="bi bi-x-circle me-2"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-save rounded-pill px-4 py-2" id="btnSubmit">
                        <i class="bi bi-save me-2"></i> Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS Custom untuk Styling -->
<style>
/* Style untuk input error */
.form-control.error,
.form-select.error {
    border-color: #dc3545 !important;
    background-color: #fff5f5 !important;
}

.form-control.error:focus,
.form-select.error:focus {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

/* Tombol Kembali */
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

/* Upload Box Styling */
.upload-box {
    border: 2px dashed #ffc107;
    border-radius: 16px;
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-box:hover {
    background: linear-gradient(135deg, #ffecb3, #ffe082);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
}

.upload-icon {
    font-size: 48px;
    color: #ffc107;
    margin-bottom: 12px;
    display: inline-block;
}

.upload-text {
    font-weight: 600;
    color: #1b5e20;
    margin-bottom: 5px;
}

.upload-hint {
    font-size: 0.75rem;
    color: #6c757d;
}

/* Preview Image */
.preview-container {
    position: relative;
    display: inline-block;
}

.preview-image {
    max-width: 120px;
    max-height: 120px;
    border-radius: 12px;
    border: 2px solid #ffc107;
    padding: 4px;
    background: white;
}

.preview-remove {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #dc3545;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    transition: all 0.2s ease;
}

.preview-remove:hover {
    transform: scale(1.1);
    background: #c82333;
}

/* Custom Toast - Perbaikan warna sukses menjadi hijau */
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

/* Toast error (merah) */
.custom-toast.toast-error {
    border-left-color: #dc3545;
    background: linear-gradient(135deg, #ffffff, #fef2f2);
}

/* Toast success (hijau) */
.custom-toast.toast-success {
    border-left-color: #28a745;
    background: linear-gradient(135deg, #ffffff, #e8f5e9);
}

.custom-toast .toast-icon i {
    font-size: 28px;
}

.custom-toast.toast-error .toast-icon i {
    color: #dc3545;
}

.custom-toast.toast-success .toast-icon i {
    color: #28a745;
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
.form-container {
    animation: fadeInUp 0.5s ease;
}

.page-title {
    background: linear-gradient(135deg, #1b5e20, #2e7d32, #ffc107);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-size: 1.8rem;
}

.card-form {
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

/* Form Control */
.form-control,
.form-select {
    border-radius: 12px;
    border: 1.5px solid #e5e7eb;
    padding: 12px 14px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.form-label {
    font-weight: 600;
    color: #333;
    font-size: 0.85rem;
}

/* Button Save */
.btn-save {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    color: white;
}

.action-buttons {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
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

@media (max-width: 768px) {
    .page-title {
        font-size: 1.3rem;
    }

    .action-buttons {
        flex-direction: column;
    }

    .action-buttons .btn {
        width: 100%;
    }

    .form-control,
    .form-select {
        padding: 10px 12px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Preview gambar
let selectedFile = null;

document.getElementById('gambarInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        selectedFile = file;
        const reader = new FileReader();
        reader.onload = function(event) {
            const preview = document.getElementById('previewImg');
            const container = document.getElementById('imagePreview');
            preview.src = event.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

function removeImage() {
    document.getElementById('gambarInput').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    selectedFile = null;
}

// Click upload box
document.getElementById('uploadBox')?.addEventListener('click', function() {
    document.getElementById('gambarInput').click();
});

// ============================================================
// VALIDASI & SUBMIT FORM AJAX
// ============================================================
document.getElementById('formTambahProduk')?.addEventListener('submit', function(e) {
    e.preventDefault();

    var namaProduk = document.getElementById('nama_produk');
    var kategoriId = document.getElementById('kategori_id');
    var harga = document.getElementById('harga');
    var minOrder = document.getElementById('min_order');

    var errorNama = document.getElementById('error_nama_produk');
    var errorKategori = document.getElementById('error_kategori_id');
    var errorHarga = document.getElementById('error_harga');
    var errorMinOrder = document.getElementById('error_min_order');

    var isValid = true;
    var errorMessages = [];

    // Reset error styling
    [namaProduk, kategoriId, harga, minOrder].forEach(el => el?.classList.remove('error'));
    [errorNama, errorKategori, errorHarga, errorMinOrder].forEach(el => {
        if (el) el.style.display = 'none';
    });

    // Validasi
    if (!namaProduk.value.trim()) {
        namaProduk.classList.add('error');
        if (errorNama) errorNama.style.display = 'block';
        errorMessages.push('Nama produk tidak boleh kosong');
        isValid = false;
    }

    if (!kategoriId.value) {
        kategoriId.classList.add('error');
        if (errorKategori) errorKategori.style.display = 'block';
        errorMessages.push('Kategori tidak boleh kosong');
        isValid = false;
    }

    if (!harga.value || parseFloat(harga.value) <= 0) {
        harga.classList.add('error');
        if (errorHarga) errorHarga.style.display = 'block';
        errorMessages.push('Harga tidak boleh kosong');
        isValid = false;
    }

    if (!minOrder.value || parseInt(minOrder.value) <= 0) {
        minOrder.classList.add('error');
        if (errorMinOrder) errorMinOrder.style.display = 'block';
        errorMessages.push('Minimal order tidak boleh kosong');
        isValid = false;
    }

    if (!isValid) {
        showCustomToast('error', 'Validasi Gagal', errorMessages.join(', '));
        var firstError = document.querySelector('.form-control.error, .form-select.error');
        if (firstError) firstError.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
        return;
    }

    // Submit AJAX
    var formData = new FormData(this);
    var btnSubmit = document.getElementById('btnSubmit');
    var originalText = btnSubmit.innerHTML;

    btnSubmit.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Menyimpan...';
    btnSubmit.disabled = true;

    fetch('{{ route("admin.produk.store") }}', {
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

// Hilangkan error styling saat user mulai mengetik
['nama_produk', 'kategori_id', 'harga', 'min_order'].forEach(id => {
    const el = document.getElementById(id);
    const errorId = `error_${id}`;
    el?.addEventListener('input', function() {
        this.classList.remove('error');
        const errorEl = document.getElementById(errorId);
        if (errorEl) errorEl.style.display = 'none';
    });
    el?.addEventListener('change', function() {
        this.classList.remove('error');
        const errorEl = document.getElementById(errorId);
        if (errorEl) errorEl.style.display = 'none';
    });
});

// ============================================================
// CUSTOM TOAST NOTIFICATION (DENGAN WARNA HIJAU UNTUK SUKSES)
function showCustomToast(type, title, message) {
    var existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => toast.remove());

    var toastDiv = document.createElement('div');
    toastDiv.className = 'custom-toast toast-' + type;

    var icon = document.createElement('div');
    icon.className = 'toast-icon';
    if (type === 'success') {
        icon.innerHTML = '<i class="bi bi-check-circle-fill"></i>';
    } else {
        icon.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i>';
    }

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
</script>
@endpush
@endsection