@extends('layouts.admin')

@section('title', 'Edit Metode Pembayaran')

@section('content')
<div class="form-container">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold mb-1">
                <i class="bi bi-pencil-square me-2" style="color: #ffc107;"></i>
                Edit Metode Pembayaran
            </h2>
            <p class="text-muted mb-0">Edit informasi rekening bank</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <a href="{{ route('admin.metode-pembayaran.index') }}" class="btn-back rounded-pill px-4 py-2">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card-form card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
            <div class="d-flex align-items-center gap-2">
                <div class="header-icon">
                    <i class="bi bi-pencil"></i>
                </div>
                <h5 class="mb-0 fw-bold" style="color: #1b5e20;">Form Edit Rekening Bank</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.metode-pembayaran.update', $metode->id) }}"
                enctype="multipart/form-data" id="formBank" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_nama_bank">
                            <i class="bi bi-bank2 text-success me-1"></i> Nama Bank
                        </label>
                        <input type="text" name="nama_bank" id="nama_bank"
                            class="form-control rounded-3 @error('nama_bank') is-invalid @enderror"
                            value="{{ old('nama_bank', $metode->nama_bank) }}" required placeholder="Contoh: Bank BCA">
                        <div class="invalid-feedback-custom" id="error_nama_bank"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Nama bank harus diisi!
                        </div>
                        @error('nama_bank')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_nomor_rekening">
                            <i class="bi bi-credit-card text-primary me-1"></i> Nomor Rekening
                        </label>
                        <input type="text" name="nomor_rekening" id="nomor_rekening"
                            class="form-control rounded-3 @error('nomor_rekening') is-invalid @enderror"
                            value="{{ old('nomor_rekening', $metode->nomor_rekening) }}" required
                            placeholder="Contoh: 1234567890">
                        <div class="invalid-feedback-custom" id="error_nomor_rekening"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Nomor rekening harus diisi!
                        </div>
                        @error('nomor_rekening')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_atas_nama">
                            <i class="bi bi-person text-info me-1"></i> Atas Nama
                        </label>
                        <input type="text" name="atas_nama" id="atas_nama"
                            class="form-control rounded-3 @error('atas_nama') is-invalid @enderror"
                            value="{{ old('atas_nama', $metode->atas_nama) }}" required
                            placeholder="Contoh: PT Prisma Sari">
                        <div class="invalid-feedback-custom" id="error_atas_nama"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Atas nama harus diisi!
                        </div>
                        @error('atas_nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-image me-1"></i> Logo Bank
                        </label>
                        <div class="upload-area">
                            @if($metode->logo_bank)
                            <div class="current-logo mb-2">
                                <img src="{{ asset('storage/bank/' . $metode->logo_bank) }}" alt="Logo"
                                    class="current-logo-img">
                                <p class="small text-muted mt-1">Logo saat ini</p>
                            </div>
                            @endif
                            <input type="file" name="logo_bank" id="logo_bank"
                                class="form-control rounded-3 @error('logo_bank') is-invalid @enderror"
                                accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, SVG. Maks: 1MB. Kosongkan jika tidak ingin
                                mengubah logo.</small>
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img id="previewImg" src="#" alt="Preview" style="max-width: 80px; border-radius: 8px;">
                            </div>
                        </div>
                        @error('logo_bank')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="status_aktif" class="form-check-input" id="status_aktif"
                                value="1" {{ $metode->status_aktif ? 'checked' : '' }}
                                style="width: 18px; height: 18px; cursor: pointer;">
                            <label class="form-check-label fw-semibold" for="status_aktif" style="color: #333;">
                                Aktif (ditampilkan ke pelanggan)
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="action-buttons">
                            <a href="{{ route('admin.metode-pembayaran.index') }}" class="btn-cancel">
                                <i class="bi bi-x-circle me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn-update">
                                <i class="bi bi-save me-2"></i> Update
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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

.card-form {
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

.current-logo-img {
    max-width: 80px;
    max-height: 80px;
    border-radius: 12px;
    border: 2px solid #ffc107;
    padding: 4px;
    background: white;
}

.form-check-input:checked {
    background-color: #2e7d32;
    border-color: #2e7d32;
}

.form-check-input:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

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
}

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
}
</style>
@endpush

@push('scripts')
<script>
document.getElementById('logo_bank')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('previewImg').src = event.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// ============================================================
// VALIDASI & SUBMIT FORM AJAX
// ============================================================
document.getElementById('formBank')?.addEventListener('submit', function(e) {
    e.preventDefault();

    let isValid = true;
    let errors = [];

    document.querySelectorAll('.form-control').forEach(f => f.classList.remove('error'));
    document.querySelectorAll('.invalid-feedback-custom').forEach(el => el.style.display = 'none');

    if (!document.getElementById('nama_bank').value.trim()) {
        document.getElementById('nama_bank').classList.add('error');
        document.getElementById('error_nama_bank').style.display = 'block';
        errors.push('Nama bank harus diisi');
        isValid = false;
    }
    if (!document.getElementById('nomor_rekening').value.trim()) {
        document.getElementById('nomor_rekening').classList.add('error');
        document.getElementById('error_nomor_rekening').style.display = 'block';
        errors.push('Nomor rekening harus diisi');
        isValid = false;
    }
    if (!document.getElementById('atas_nama').value.trim()) {
        document.getElementById('atas_nama').classList.add('error');
        document.getElementById('error_atas_nama').style.display = 'block';
        errors.push('Atas nama harus diisi');
        isValid = false;
    }

    if (!isValid) {
        showCustomToast('error', 'Validasi Gagal', errors.join(', '));
        let firstError = document.querySelector('.error');
        if (firstError) firstError.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
        return false;
    }

    // Submit AJAX
    var formData = new FormData(this);
    var btnSubmit = document.querySelector('.btn-update');
    var originalText = btnSubmit.innerHTML;

    btnSubmit.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Menyimpan...';
    btnSubmit.disabled = true;

    fetch('{{ route("admin.metode-pembayaran.update", $metode->id) }}', {
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
// CUSTOM TOAST NOTIFICATION
// ============================================================
function showCustomToast(type, title, message) {
    document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());
    var toastDiv = document.createElement('div');
    toastDiv.className = 'custom-toast toast-' + type;
    toastDiv.innerHTML = `<div class="toast-icon"><i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i></div>
        <div class="toast-content"><div class="toast-title">${title}</div><div class="toast-message">${message}</div></div>
        <button class="toast-close">&times;</button>`;
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

document.getElementById('nama_bank')?.addEventListener('input', function() {
    this.classList.remove('error');
    document.getElementById('error_nama_bank').style.display = 'none';
});
document.getElementById('nomor_rekening')?.addEventListener('input', function() {
    this.classList.remove('error');
    document.getElementById('error_nomor_rekening').style.display = 'none';
});
document.getElementById('atas_nama')?.addEventListener('input', function() {
    this.classList.remove('error');
    document.getElementById('error_atas_nama').style.display = 'none';
});
</script>
@endpush
@endsection