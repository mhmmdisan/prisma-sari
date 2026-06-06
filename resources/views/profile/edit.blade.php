@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="batik-bg py-4">
    <div class="container position-relative" style="z-index: 1;">
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-title">
                    <h2 class="fw-bold mb-2"
                        style="background: linear-gradient(135deg, #1b5e20, #2e7d32, #ffc107); -webkit-background-clip: text; background-clip: text; color: transparent;">
                        <i class="bi bi-person-gear me-2" style="color: #ffc107;"></i>
                        Edit Profil
                    </h2>
                    <p class="text-muted">Kelola informasi akun Anda</p>
                </div>
            </div>
        </div>

        <!-- Tombol Kembali ke Profil -->
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('profile.index') }}" class="btn btn-outline-success rounded-pill px-4 py-2">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Profil
                </a>
            </div>
        </div>

        @if(session('status') == 'profile-updated')
        <div class="alert-custom alert-success-custom mb-4">
            <i class="fas fa-check-circle"></i>
            <div class="alert-content">Profil berhasil diperbarui!</div>
            <button type="button" class="alert-close" onclick="this.closest('.alert-custom').remove()">&times;</button>
        </div>
        @endif

        @if(session('status') == 'password-updated')
        <div class="alert-custom alert-success-custom mb-4">
            <i class="fas fa-check-circle"></i>
            <div class="alert-content">Password berhasil diperbarui!</div>
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
            <div class="col-md-6">
                <!-- Card Informasi Akun -->
                <div class="card border-0 rounded-4 shadow-sm hover-card mb-4">
                    <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                        <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                            <i class="bi bi-person me-2"></i> Informasi Akun
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                            id="formProfile">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="color: #555;">
                                    <i class="bi bi-person me-1 text-success"></i> Nama Lengkap
                                </label>
                                <input type="text" name="name"
                                    class="form-control rounded-3 @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required
                                    style="border: 1.5px solid #e5e7eb; padding: 12px;">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="color: #555;">
                                    <i class="bi bi-envelope me-1 text-success"></i> Email
                                </label>
                                <input type="email" name="email" class="form-control rounded-3"
                                    value="{{ $user->email }}" readonly
                                    style="border: 1.5px solid #e5e7eb; padding: 12px; background: #f8f9fa;">
                                <small class="text-muted">Email tidak dapat diubah</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="color: #555;">
                                    <i class="bi bi-telephone me-1 text-success"></i> No. Telepon
                                </label>
                                <input type="text" name="no_telepon"
                                    class="form-control rounded-3 @error('no_telepon') is-invalid @enderror"
                                    value="{{ old('no_telepon', $user->no_telepon) }}"
                                    style="border: 1.5px solid #e5e7eb; padding: 12px;">
                                @error('no_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="color: #555;">
                                    <i class="bi bi-geo-alt me-1 text-success"></i> Alamat
                                </label>
                                <textarea name="alamat"
                                    class="form-control rounded-3 @error('alamat') is-invalid @enderror" rows="3"
                                    style="border: 1.5px solid #e5e7eb; padding: 12px; resize: vertical;">{{ old('alamat', $user->alamat) }}</textarea>
                                @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold" style="color: #555;">
                                    <i class="bi bi-image me-1 text-success"></i> Foto Profil
                                </label>
                                <input type="file" name="foto_profil"
                                    class="form-control rounded-3 @error('foto_profil') is-invalid @enderror"
                                    accept="image/*" style="border: 1.5px solid #e5e7eb; padding: 10px;">
                                <small class="text-muted">Format: JPG, PNG. Maks: 2MB</small>
                                @error('foto_profil')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($user->foto_profil)
                                <div class="mt-3">
                                    <img src="{{ asset($user->foto_profil) }}" alt="Foto Profil"
                                        class="rounded-circle border"
                                        style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #ffc107 !important;">
                                </div>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-save w-100">
                                <i class="bi bi-save me-2"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Card Ganti Password -->
                <div class="card border-0 rounded-4 shadow-sm hover-card mb-4">
                    <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                        <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                            <i class="bi bi-key me-2"></i> Ganti Password
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('password.update') }}" id="formPassword">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="color: #555;">
                                    <i class="bi bi-lock me-1 text-warning"></i> Password Saat Ini
                                </label>
                                <input type="password" name="current_password"
                                    class="form-control rounded-3 @error('current_password') is-invalid @enderror"
                                    required style="border: 1.5px solid #e5e7eb; padding: 12px;">
                                @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="color: #555;">
                                    <i class="bi bi-key me-1 text-warning"></i> Password Baru
                                </label>
                                <input type="password" name="password"
                                    class="form-control rounded-3 @error('password') is-invalid @enderror" required
                                    style="border: 1.5px solid #e5e7eb; padding: 12px;">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold" style="color: #555;">
                                    <i class="bi bi-check-circle me-1 text-warning"></i> Konfirmasi Password Baru
                                </label>
                                <input type="password" name="password_confirmation" class="form-control rounded-3"
                                    required style="border: 1.5px solid #e5e7eb; padding: 12px;">
                            </div>

                            <button type="submit" class="btn btn-change-password w-100">
                                <i class="bi bi-key me-2"></i> Ganti Password
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Card Informasi Tambahan -->
                <div class="card border-0 rounded-4 shadow-sm hover-card">
                    <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                        <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                            <i class="bi bi-info-circle me-2"></i> Informasi
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="info-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Keamanan Akun</h6>
                                <p class="text-muted small mb-0">Gunakan password yang kuat dan jangan bagikan kepada
                                    siapapun</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="info-icon">
                                <i class="bi bi-image"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Foto Profil</h6>
                                <p class="text-muted small mb-0">Format JPG/PNG, maksimal 2MB</p>
                            </div>
                        </div>
                    </div>
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

.container.position-relative {
    position: relative;
    z-index: 2;
}

/* Hero Title */
.hero-title {
    text-align: center;
    margin-bottom: 1rem;
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

/* Card Hover */
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
}

/* Form Control */
.form-control {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
}

.form-control:read-only {
    background-color: #f8f9fa;
    cursor: not-allowed;
}

/* Button Save */
.btn-save {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border: none;
    color: white;
    padding: 12px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    color: white;
}

/* Button Change Password */
.btn-change-password {
    background: linear-gradient(135deg, #f57c00, #ef6c00);
    border: none;
    color: white;
    padding: 12px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-change-password:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 124, 0, 0.3);
    color: white;
}

/* Button Back */
.btn-outline-success {
    border-width: 1.5px;
    transition: all 0.3s ease;
}

.btn-outline-success:hover {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
}

/* Info Icon */
.info-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-icon i {
    font-size: 1.3rem;
    color: #2e7d32;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title h2 {
        font-size: 1.5rem;
    }

    .card-header {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Validasi form profile
document.getElementById('formProfile')?.addEventListener('submit', function(e) {
    var nameInput = document.querySelector('input[name="name"]');
    if (!nameInput.value.trim()) {
        e.preventDefault();
        showCustomAlert('error', 'Nama lengkap harus diisi!');
        return false;
    }
    return true;
});

// Validasi form password
document.getElementById('formPassword')?.addEventListener('submit', function(e) {
    var currentPassword = document.querySelector('input[name="current_password"]');
    var newPassword = document.querySelector('input[name="password"]');
    var confirmPassword = document.querySelector('input[name="password_confirmation"]');

    if (!currentPassword.value) {
        e.preventDefault();
        showCustomAlert('error', 'Password saat ini harus diisi!');
        return false;
    }

    if (!newPassword.value) {
        e.preventDefault();
        showCustomAlert('error', 'Password baru harus diisi!');
        return false;
    }

    if (newPassword.value.length < 6) {
        e.preventDefault();
        showCustomAlert('error', 'Password baru minimal 6 karakter!');
        return false;
    }

    if (newPassword.value !== confirmPassword.value) {
        e.preventDefault();
        showCustomAlert('error', 'Konfirmasi password tidak cocok!');
        return false;
    }

    return true;
});

// Custom Alert
function showCustomAlert(type, message) {
    var alertDiv = document.createElement('div');
    alertDiv.className = 'alert-custom ' + (type === 'success' ? 'alert-success-custom' : 'alert-error-custom');
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '20px';
    alertDiv.style.left = '50%';
    alertDiv.style.transform = 'translateX(-50%)';
    alertDiv.style.zIndex = '100001';
    alertDiv.style.minWidth = '300px';
    alertDiv.style.maxWidth = '500px';
    alertDiv.style.boxShadow = '0 10px 40px rgba(0,0,0,0.2)';
    alertDiv.style.animation = 'slideIn 0.3s ease';

    var icon = document.createElement('i');
    icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';

    var content = document.createElement('div');
    content.className = 'alert-content';
    content.innerText = message;

    var closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.className = 'alert-close';
    closeBtn.innerHTML = '&times;';
    closeBtn.onclick = function() {
        alertDiv.remove();
    };

    alertDiv.appendChild(icon);
    alertDiv.appendChild(content);
    alertDiv.appendChild(closeBtn);

    document.body.appendChild(alertDiv);

    setTimeout(function() {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}
</script>
@endpush
@endsection