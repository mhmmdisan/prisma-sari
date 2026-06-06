@extends('layouts.admin')

@section('title', 'Kelola Akun')

@section('content')
<div class="kelola-akun-container">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold mb-1">
                <i class="bi bi-people me-2" style="color: #ffc107;"></i>
                Kelola Akun
            </h2>
            <p class="text-muted mb-0">Manajemen akun pengguna sistem</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <a href="{{ route('admin.kelola-akun.create') }}" class="btn-primary-custom rounded-pill px-4 py-2">
                <i class="bi bi-plus-circle me-2"></i> Tambah Akun
            </a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div class="stat-info">
                        <h6 class="stat-label">Total Admin</h6>
                        <h2 class="stat-value">{{ number_format($totalAdmin, 0, ',', '.') }}</h2>
                    </div>
                </div>
                <div class="stat-progress stat-progress-primary"></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-card-success">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-info">
                        <h6 class="stat-label">Total Pelanggan</h6>
                        <h2 class="stat-value">{{ number_format($totalPelanggan, 0, ',', '.') }}</h2>
                    </div>
                </div>
                <div class="stat-progress stat-progress-success"></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-inner">
                    <div class="stat-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="stat-info">
                        <h6 class="stat-label">Total Pemilik</h6>
                        <h2 class="stat-value">{{ number_format($totalPemilik, 0, ',', '.') }}</h2>
                    </div>
                </div>
                <div class="stat-progress stat-progress-warning"></div>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="card-filter card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.kelola-akun.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="bi bi-tag me-1"></i> Filter Role
                    </label>
                    <select name="role" class="form-select rounded-3 filter-select">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pelanggan" {{ request('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan
                        </option>
                        <option value="pemilik" {{ request('role') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-secondary">
                        <i class="bi bi-search me-1"></i> Cari
                    </label>
                    <input type="text" name="search" class="form-control rounded-3 filter-input"
                        placeholder="Nama, Email, atau No Telepon" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-filter w-100 rounded-pill">
                        <i class="bi bi-search me-2"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.kelola-akun.index') }}" class="btn btn-reset w-100 rounded-pill">
                        <i class="bi bi-arrow-repeat me-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Akun -->
    <div class="card-table card border-0 rounded-4 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th style="width: 10%">Role</th>
                            <th>Alamat</th>
                            <th style="width: 10%">Dibuat</th>
                            <th style="width: 10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $key => $user)
                        <tr class="user-row">
                            <td class="align-middle text-center">{{ $users->firstItem() + $key }}</td>
                            <td class="align-middle">
                                <div class="user-name">
                                    <i class="bi bi-person-circle text-success me-1"></i>
                                    {{ $user->name }}
                                </div>
                                @if($user->id == auth()->id())
                                <span class="badge badge-you">
                                    <i class="bi bi-star-fill me-1"></i> Anda
                                </span>
                                @endif
                            </td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">
                                @if($user->no_telepon)
                                <span class="badge-phone">{{ $user->no_telepon }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @php
                                $roleBadge = match($user->role) {
                                'admin' => 'badge-role-admin',
                                'pelanggan' => 'badge-role-pelanggan',
                                'pemilik' => 'badge-role-pemilik',
                                default => 'badge-role-secondary'
                                };
                                @endphp
                                <span class="badge-role {{ $roleBadge }}">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td class="align-middle">{{ Str::limit($user->alamat, 30) ?? '-' }}</td>
                            <td class="align-middle">
                                {{ \Carbon\Carbon::parse($user->created_at)->locale('id')->translatedFormat('d F Y') }}
                            </td>
                            <td class="align-middle">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.kelola-akun.edit', $user->id) }}" class="btn-action edit"
                                        title="Edit Akun">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($user->id != auth()->id())
                                    <button class="btn-action delete"
                                        onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')"
                                        title="Hapus Akun">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="mt-2 text-muted mb-0">Tidak ada data akun</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    </tr>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            <div class="pagination-wrapper">
                {{ $users->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS AKUN -->
<div id="deleteModal"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 100000; margin: 0; padding: 0;">
    <div
        style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 20px; box-sizing: border-box;">
        <div
            style="background: white; border-radius: 28px; max-width: 400px; width: 100%; margin: 0 auto; box-shadow: 0 30px 60px rgba(0,0,0,0.4); overflow: hidden;">
            <div
                style="background: linear-gradient(135deg, #dc3545, #c62828); color: white; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; font-size: 1rem; font-weight: 600;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" onclick="closeDeleteModal()"
                    style="background: rgba(255,255,255,0.2); border: none; color: white; width: 30px; height: 30px; border-radius: 50%; font-size: 18px; cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 24px; text-align; center;">
                <i class="bi bi-question-circle" style="font-size: 60px; color: #dc3545;"></i>
                <h5 class="mt-3">Apakah Anda yakin?</h5>
                <p class="text-muted" id="deleteUserName">Akun akan dihapus secara permanen.</p>
                <div class="d-flex gap-3 justify-content-center mt-4">
                    <button type="button" onclick="closeDeleteModal()"
                        class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="button" id="confirmDeleteBtn" class="btn btn-danger rounded-pill px-4">
                        <i class="bi bi-trash me-1"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS untuk Custom Toast -->
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
.kelola-akun-container {
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

/* Statistik Cards */
.stat-card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.stat-card-inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
}

.stat-info {
    text-align: right;
}

.stat-label {
    font-size: 0.85rem;
    font-weight: 500;
    color: #6c757d;
    margin-bottom: 5px;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    margin: 0;
    line-height: 1.2;
}

.stat-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 4px;
    width: 0%;
    transition: width 0.3s ease;
}

.stat-card:hover .stat-progress {
    width: 100%;
}

.stat-card-primary .stat-icon {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.stat-card-primary .stat-value {
    color: #0d6efd;
}

.stat-progress-primary {
    background: linear-gradient(90deg, #0d6efd, #0dcaf0);
}

.stat-card-success .stat-icon {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.stat-card-success .stat-value {
    color: #198754;
}

.stat-progress-success {
    background: linear-gradient(90deg, #198754, #2e7d32);
}

.stat-card-warning .stat-icon {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

.stat-card-warning .stat-value {
    color: #ff8f00;
}

.stat-progress-warning {
    background: linear-gradient(90deg, #ffc107, #ffb300);
}

/* Buttons */
.btn-primary-custom {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border: none;
    color: white;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    color: white;
}

.btn-filter {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    border: none;
    color: #1b5e20;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

.btn-reset {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border: 1px solid #2e7d32;
    color: #2e7d32;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-reset:hover {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
}

/* Filter Select & Input */
.filter-select,
.filter-input {
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 10px 14px;
    transition: all 0.3s ease;
}

.filter-select:hover,
.filter-input:hover {
    border-color: #ffc107;
    background-color: #fffbeb;
}

.filter-select:focus,
.filter-input:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    outline: none;
}

/* Table */
.card-table {
    overflow: hidden;
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

.user-row {
    transition: all 0.2s ease;
}

.user-row:hover {
    background: #fff8e1;
}

.user-name {
    font-weight: 600;
    color: #1b5e20;
}

.badge-you {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
    padding: 3px 10px;
    border-radius: 50px;
    font-size: 0.65rem;
    font-weight: 500;
    display: inline-block;
    margin-left: 6px;
}

.badge-phone {
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    color: #2e7d32;
    padding: 3px 10px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 500;
    display: inline-block;
}

/* Badge Role */
.badge-role {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-role-admin {
    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    color: white;
}

.badge-role-pelanggan {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
}

.badge-role-pemilik {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #1b5e20;
}

.badge-role-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    color: white;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
}

.btn-action.edit {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    color: #f57c00;
}

.btn-action.delete {
    background: linear-gradient(135deg, #ffebee, #ffcdd2);
    color: #dc3545;
}

.btn-action:hover {
    transform: scale(1.1);
}

.btn-action.edit:hover {
    background: linear-gradient(135deg, #f57c00, #ef6c00);
    color: white;
}

.btn-action.delete:hover {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
}

.pagination-wrapper nav .hidden,
.pagination-wrapper nav .pagination-info,
.pagination-wrapper nav p.small {
    display: none !important;
}

.pagination {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin: 0;
    padding: 0;
}

.pagination .page-item {
    list-style: none;
}

.pagination .page-link {
    padding: 8px 16px;
    border-radius: 50px !important;
    border: 1px solid #dee2e6;
    color: #2e7d32;
    background: white;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
}

.pagination .page-link:hover {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #1b5e20;
    border-color: #ffc107;
    transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border-color: #2e7d32;
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.3rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }

    .stat-value {
        font-size: 1.5rem;
    }

    .btn-action {
        width: 28px;
        height: 28px;
    }
}
</style>
@endpush

@push('scripts')
<script>
var userToDelete = null;
var userNameToDelete = '';

function openDeleteModal(id, nama) {
    userToDelete = id;
    userNameToDelete = nama;
    document.getElementById('deleteUserName').innerText = 'Apakah Anda yakin ingin menghapus akun "' + nama + '"?';
    document.getElementById('deleteModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    userToDelete = null;
}

document.getElementById('confirmDeleteBtn')?.addEventListener('click', function() {
    if (!userToDelete) {
        showCustomToast('error', 'Gagal!', 'Akun tidak ditemukan');
        closeDeleteModal();
        return;
    }

    var confirmBtn = this;
    var originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Menghapus...';
    confirmBtn.disabled = true;

    fetch('/admin/kelola-akun/' + userToDelete, {
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
            closeDeleteModal();
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
            closeDeleteModal();
            showCustomToast('error', 'Kesalahan Server', 'Terjadi kesalahan pada server');
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        });
});

window.onclick = function(event) {
    if (event.target == document.getElementById('deleteModal')) closeDeleteModal();
};

// ============================================================
// CUSTOM TOAST NOTIFICATION - HANYA SATU
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