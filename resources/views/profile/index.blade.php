@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="batik-bg py-4">
    <div class="container position-relative" style="z-index: 1;">
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-title">
                    <h2 class="fw-bold mb-2"
                        style="background: linear-gradient(135deg, #1b5e20, #2e7d32, #ffc107); -webkit-background-clip: text; background-clip: text; color: transparent;">
                        <i class="bi bi-person-circle me-2" style="color: #ffc107;"></i>
                        Profil Saya
                    </h2>
                    <p class="text-muted">Lihat dan kelola informasi akun Anda</p>
                </div>
            </div>
        </div>

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

        <div class="row">
            <!-- Kolom Kiri: Foto Profil & Statistik -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 rounded-4 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <!-- Foto Profil -->
                        <div class="mb-3">
                            @if($user->foto_profil)
                            <img src="{{ asset($user->foto_profil) }}" alt="Foto Profil"
                                class="rounded-circle img-thumbnail"
                                style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #ffc107;">
                            @else
                            <div class="profile-avatar mx-auto">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            @endif
                        </div>

                        <h4 class="mb-1 fw-bold" style="color: #1b5e20;">{{ $user->name }}</h4>
                        <p class="text-muted mb-2">{{ $user->email }}</p>
                        <span class="badge-profile">{{ ucfirst($user->role) }}</span>
                    </div>
                </div>

                <!-- Statistik Singkat -->
                <div class="card border-0 rounded-4 shadow-sm mt-4 hover-card">
                    <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                        <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                            <i class="bi bi-graph-up me-2"></i> Statistik
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Total Pesanan</span>
                            <strong class="text-success fs-5">{{ $totalPesanan ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Pesanan Selesai</span>
                            <strong class="text-success fs-5">{{ $pesananSelesai ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Member Sejak</span>
                            <strong
                                class="text-success">{{ $user->created_at->locale('id')->translatedFormat('d F Y') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Informasi Akun -->
            <div class="col-md-8">
                <div class="card border-0 rounded-4 shadow-sm hover-card">
                    <div class="card-header bg-white rounded-top-4 py-3 d-flex justify-content-between align-items-center flex-wrap"
                        style="border-bottom: 2px solid #ffc107;">
                        <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                            <i class="bi bi-person-badge me-2"></i> Informasi Akun
                        </h5>
                        <a href="{{ route('profile.edit') }}" class="btn btn-edit-profile">
                            <i class="bi bi-pencil-square me-1"></i> Kelola Profil
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <div class="info-table">
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="bi bi-person text-success me-2"></i> Nama Lengkap
                                </div>
                                <div class="info-value">{{ $user->name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="bi bi-envelope text-success me-2"></i> Email
                                </div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="bi bi-telephone text-success me-2"></i> No. Telepon
                                </div>
                                <div class="info-value">{{ $user->no_telepon ?: '-' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="bi bi-geo-alt text-success me-2"></i> Alamat
                                </div>
                                <div class="info-value">{{ $user->alamat ?: '-' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="bi bi-calendar3 text-success me-2"></i> Member Sejak
                                </div>
                                <div class="info-value">{{ $user->created_at->locale('id')->translatedFormat('d F Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menu Navigasi -->
                <div class="card border-0 rounded-4 shadow-sm mt-4 hover-card">
                    <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                        <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                            <i class="bi bi-grid-3x3-gap-fill me-2"></i> Menu Lainnya
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="{{ route('pelanggan.pesanan.index') }}" class="btn-menu">
                                    <i class="bi bi-receipt"></i>
                                    <span>Riwayat Pesanan</span>
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('pelanggan.dashboard') }}" class="btn-menu">
                                    <i class="bi bi-speedometer2"></i>
                                    <span>Dashboard</span>
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                </a>
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

/* Profile Avatar */
.profile-avatar {
    width: 150px;
    height: 150px;
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    border: 3px solid #ffc107;
}

.profile-avatar i {
    font-size: 5rem;
    color: white;
}

/* Badge Profile */
.badge-profile {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
}

/* Info Table */
.info-table {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-row {
    display: flex;
    flex-wrap: wrap;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-label {
    width: 35%;
    font-weight: 600;
    color: #555;
}

.info-value {
    width: 65%;
    color: #333;
    font-weight: 500;
}

/* Button Edit Profile */
.btn-edit-profile {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border: none;
    color: white;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-edit-profile:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    color: white;
}

/* Button Menu */
.btn-menu {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
    color: #333;
}

.btn-menu:hover {
    border-color: #ffc107;
    background: #fffbeb;
    transform: translateX(5px);
    color: #1b5e20;
}

.btn-menu i:first-child {
    font-size: 1.3rem;
    color: #2e7d32;
}

.btn-menu span {
    flex: 1;
    font-weight: 500;
}

.btn-menu i:last-child {
    color: #ffc107;
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title h2 {
        font-size: 1.5rem;
    }

    .info-label,
    .info-value {
        width: 100%;
    }

    .info-label {
        margin-bottom: 5px;
    }

    .btn-edit-profile {
        margin-top: 10px;
    }

    .card-header {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px;
    }

    .btn-menu {
        padding: 12px 16px;
    }
}
</style>
@endpush
@endsection