@extends('layouts.admin')

@section('title', 'Tambah Akun')

@section('content')
<div class="form-container">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold mb-1">
                <i class="bi bi-person-plus me-2" style="color: #ffc107;"></i>
                Tambah Akun
            </h2>
            <p class="text-muted mb-0">Tambah akun pengguna baru</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <a href="{{ route('admin.kelola-akun.index') }}" class="btn-back rounded-pill px-4 py-2">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card-form card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
            <div class="d-flex align-items-center gap-2">
                <div class="header-icon">
                    <i class="bi bi-person-plus"></i>
                </div>
                <h5 class="mb-0 fw-bold" style="color: #1b5e20;">Form Tambah Akun</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.kelola-akun.store') }}" id="formAkun" novalidate>
                @csrf

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_name">
                            <i class="bi bi-person text-success me-1"></i> Nama Lengkap
                        </label>
                        <input type="text" name="name" id="name"
                            class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name') }}"
                            required autofocus placeholder="Masukkan nama lengkap">
                        <div class="invalid-feedback-custom" id="error_name"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Nama lengkap harus diisi!
                        </div>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_email">
                            <i class="bi bi-envelope text-primary me-1"></i> Email
                        </label>
                        <input type="email" name="email" id="email"
                            class="form-control rounded-3 @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required placeholder="contoh@email.com">
                        <div class="invalid-feedback-custom" id="error_email"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Email harus diisi dengan format yang valid!
                        </div>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_role">
                            <i class="bi bi-tag text-warning me-1"></i> Role
                        </label>
                        <select name="role" id="role" class="form-select rounded-3 @error('role') is-invalid @enderror"
                            required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="pelanggan" {{ old('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan
                            </option>
                            <option value="pemilik" {{ old('role') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                        </select>
                        <div class="invalid-feedback-custom" id="error_role"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Role harus dipilih!
                        </div>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_no_telepon">
                            <i class="bi bi-telephone text-info me-1"></i> No. Telepon
                        </label>
                        <input type="text" name="no_telepon" id="no_telepon"
                            class="form-control rounded-3 @error('no_telepon') is-invalid @enderror"
                            value="{{ old('no_telepon') }}" placeholder="Contoh: 081234567890">
                        @error('no_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_password">
                            <i class="bi bi-lock text-danger me-1"></i> Password
                        </label>
                        <input type="password" name="password" id="password"
                            class="form-control rounded-3 @error('password') is-invalid @enderror" required
                            placeholder="Minimal 6 karakter">
                        <small class="text-muted d-block mt-1">Minimal 6 karakter</small>
                        <div class="invalid-feedback-custom" id="error_password"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Password harus diisi (minimal 6 karakter)!
                        </div>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" id="label_password_confirmation">
                            <i class="bi bi-check-circle text-success me-1"></i> Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control rounded-3" required placeholder="Ulangi password">
                        <div class="invalid-feedback-custom" id="error_password_confirmation"
                            style="display: none; color: #dc3545; font-size: 0.8rem; margin-top: 5px;">
                            <i class="bi bi-exclamation-circle me-1"></i> Konfirmasi password tidak cocok!
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-geo-alt me-1"></i> Alamat
                        </label>
                        <textarea name="alamat" id="alamat"
                            class="form-control rounded-3 @error('alamat') is-invalid @enderror" rows="3"
                            placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <div class="action-buttons">
                            <a href="{{ route('admin.kelola-akun.index') }}" class="btn-cancel">
                                <i class="bi bi-x-circle me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn-save">
                                <i class="bi bi-save me-2"></i> Simpan Akun
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

.action-buttons {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 20px;
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
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border: none;
    color: white;
    font-weight: 600;
    padding: 10px 28px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    color: white;
}

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
}
</style>
@endpush

@push('scripts')
<script>
// ============================================================
// VALIDASI & SUBMIT FORM AJAX
// ============================================================
document.getElementById('formAkun')?.addEventListener('submit', function(e) {
    e.preventDefault();

    let isValid = true;
    let errors = [];

    document.querySelectorAll('.form-control, .form-select').forEach(f => f.classList.remove('error'));
    document.querySelectorAll('.invalid-feedback-custom').forEach(el => el.style.display = 'none');

    let name = document.getElementById('name');
    let email = document.getElementById('email');
    let role = document.getElementById('role');
    let password = document.getElementById('password');
    let passwordConfirmation = document.getElementById('password_confirmation');

    if (!name.value.trim()) {
        name.classList.add('error');
        document.getElementById('error_name').style.display = 'block';
        errors.push('Nama lengkap harus diisi');
        isValid = false;
    }
    if (!email.value.trim()) {
        email.classList.add('error');
        document.getElementById('error_email').style.display = 'block';
        errors.push('Email harus diisi');
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        email.classList.add('error');
        document.getElementById('error_email').innerHTML =
            '<i class="bi bi-exclamation-circle me-1"></i> Format email tidak valid!';
        document.getElementById('error_email').style.display = 'block';
        errors.push('Format email tidak valid');
        isValid = false;
    }
    if (!role.value) {
        role.classList.add('error');
        document.getElementById('error_role').style.display = 'block';
        errors.push('Role harus dipilih');
        isValid = false;
    }
    if (!password.value) {
        password.classList.add('error');
        document.getElementById('error_password').style.display = 'block';
        errors.push('Password harus diisi');
        isValid = false;
    } else if (password.value.length < 6) {
        password.classList.add('error');
        document.getElementById('error_password').innerHTML =
            '<i class="bi bi-exclamation-circle me-1"></i> Password minimal 6 karakter!';
        document.getElementById('error_password').style.display = 'block';
        errors.push('Password minimal 6 karakter');
        isValid = false;
    }
    if (password.value !== passwordConfirmation.value) {
        passwordConfirmation.classList.add('error');
        document.getElementById('error_password_confirmation').style.display = 'block';
        errors.push('Konfirmasi password tidak cocok');
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
    var btnSubmit = document.querySelector('.btn-save');
    var originalText = btnSubmit.innerHTML;

    btnSubmit.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Menyimpan...';
    btnSubmit.disabled = true;

    fetch('{{ route("admin.kelola-akun.store") }}', {
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
document.getElementById('name')?.addEventListener('input', function() {
    this.classList.remove('error');
    document.getElementById('error_name').style.display = 'none';
});
document.getElementById('email')?.addEventListener('input', function() {
    this.classList.remove('error');
    document.getElementById('error_email').style.display = 'none';
});
document.getElementById('role')?.addEventListener('change', function() {
    this.classList.remove('error');
    document.getElementById('error_role').style.display = 'none';
});
document.getElementById('password')?.addEventListener('input', function() {
    this.classList.remove('error');
    document.getElementById('error_password').style.display = 'none';
});
document.getElementById('password_confirmation')?.addEventListener('input', function() {
    this.classList.remove('error');
    document.getElementById('error_password_confirmation').style.display = 'none';
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
</script>
@endpush
@endsection