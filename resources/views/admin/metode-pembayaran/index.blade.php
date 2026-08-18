@extends('layouts.admin')

@section('title', 'Kelola Metode Pembayaran')

@section('content')
<div class="bank-container">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold mb-1">
                <i class="bi bi-bank2 me-2" style="color: #ffc107;"></i>
                Kelola Metode Pembayaran
            </h2>
            <p class="text-muted mb-0">Manajemen rekening bank untuk pembayaran pelanggan</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <a href="{{ route('admin.metode-pembayaran.create') }}" class="btn-primary-custom rounded-pill px-4 py-2"
                style="text-decoration: none;">
                <i class="bi bi-plus-circle me-2"></i> Tambah Rekening
            </a>
        </div>
    </div>

    <div class="card-table card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
            <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                <i class="bi bi-credit-card me-2"></i> Daftar Rekening Bank
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 5%" class="text-center">No</th>
                            <th style="width: 10%" class="text-center">Logo</th>
                            <th class="text-center">Nama Bank</th>
                            <th class="text-center">No. Rekening</th>
                            <th class="text-center">Atas Nama</th>
                            <th style="width: 10%" class="text-center">Status</th>
                            <th style="width: 15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($metodePembayaran as $key => $bank)
                        <tr class="bank-row">
                            <td class="align-middle text-center">{{ $key + 1 }}</td>
                            <td class="align-middle text-center">
                                @if($bank->logo_bank)
                                <div class="bank-logo-wrapper">
                                    <img src="{{ asset('storage/bank/' . $bank->logo_bank) }}"
                                        alt="Logo {{ $bank->nama_bank }}" class="bank-logo">
                                </div>
                                @else
                                <div class="bank-logo-placeholder">
                                    <i class="bi bi-bank2"></i>
                                </div>
                                @endif
                            </td>
                            <td class="align-middle fw-semibold" style="color: #1b5e20;">{{ $bank->nama_bank }}</td>
                            <td class="align-middle font-monospace">{{ $bank->nomor_rekening }}</td>
                            <td class="align-middle">{{ $bank->atas_nama }}</td>
                            <td class="align-middle text-center">
                                @if($bank->status_aktif)
                                <span class="badge-status-success">
                                    <i class="bi bi-check-circle me-1"></i> Aktif
                                </span>
                                @else
                                <span class="badge-status-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Nonaktif
                                </span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.metode-pembayaran.edit', $bank->id) }}"
                                        class="btn-action edit" title="Edit Bank">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.metode-pembayaran.toggle', $bank->id) }}"
                                        method="POST" class="toggle-form" style="display: inline-block;">
                                        @csrf
                                        <button type="button" class="btn-action toggle"
                                            onclick="openToggleModal({{ $bank->id }}, '{{ $bank->nama_bank }}', {{ $bank->status_aktif ? 'false' : 'true' }}, this)"
                                            title="{{ $bank->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="bi {{ $bank->status_aktif ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.metode-pembayaran.destroy', $bank->id) }}"
                                        method="POST" class="delete-form" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action delete"
                                            onclick="openDeleteModal({{ $bank->id }}, '{{ $bank->nama_bank }}', this)"
                                            title="Hapus Bank">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="mt-2 text-muted mb-0">Belum ada metode pembayaran</p>
                                <a href="{{ route('admin.metode-pembayaran.create') }}"
                                    class="btn btn-sm btn-primary-custom mt-3 rounded-pill">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Rekening Pertama
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS -->
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
            <div style="padding: 24px; text-align: center;">
                <i class="bi bi-question-circle" style="font-size: 60px; color: #dc3545;"></i>
                <h5 class="mt-3">Apakah Anda yakin?</h5>
                <p class="text-muted" id="deleteBankName">Rekening bank akan dihapus secara permanen.</p>
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

<!-- MODAL KONFIRMASI TOGGLE STATUS (AKTIF/NONAKTIF) -->
<div id="toggleModal"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 100000; margin: 0; padding: 0;">
    <div
        style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 20px; box-sizing: border-box;">
        <div
            style="background: white; border-radius: 28px; max-width: 400px; width: 100%; margin: 0 auto; box-shadow: 0 30px 60px rgba(0,0,0,0.4); overflow: hidden;">
            <div
                style="background: linear-gradient(135deg, #ffc107, #ffb300); color: #1b5e20; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; font-size: 1rem; font-weight: 600;">
                    <i class="bi bi-arrow-repeat me-2"></i> Konfirmasi Ubah Status
                </h5>
                <button type="button" onclick="closeToggleModal()"
                    style="background: rgba(0,0,0,0.1); border: none; color: #1b5e20; width: 30px; height: 30px; border-radius: 50%; font-size: 18px; cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 24px; text-align: center;">
                <i class="bi bi-question-circle" style="font-size: 60px; color: #ffc107;"></i>
                <h5 class="mt-3 fw-bold" id="toggleModalTitle">Ubah Status Bank</h5>
                <p class="text-muted mb-2" id="toggleModalMessage">Apakah Anda yakin ingin mengaktifkan bank ini?</p>
                <div class="d-flex gap-3 justify-content-center mt-4">
                    <button type="button" onclick="closeToggleModal()"
                        class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="button" id="confirmToggleBtn" class="btn btn-warning rounded-pill px-4"
                        style="background: linear-gradient(135deg, #ffc107, #ffb300); border: none; color: #1b5e20; font-weight: 600;">
                        <i class="bi bi-check-circle me-1"></i> Ya, Ubah
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
.bank-container {
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

.btn-primary-custom {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border: none;
    color: white;
    transition: all 0.3s ease;
    text-decoration: none !important;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    color: white;
    text-decoration: none !important;
}

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

.bank-row {
    transition: all 0.2s ease;
}

.bank-row:hover {
    background: #fff8e1;
}

.bank-logo-wrapper {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.bank-logo {
    width: 35px;
    height: 35px;
    object-fit: contain;
}

.bank-logo-placeholder {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2e7d32;
    font-size: 1.5rem;
    margin: 0 auto;
}

.badge-status-success {
    background: linear-gradient(135deg, #198754, #157347);
    color: white;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-block;
}

.badge-status-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    color: white;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-block;
}

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
    text-decoration: none;
    cursor: pointer;
    border: none;
}

.btn-action.edit {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    color: #f57c00;
}

.btn-action.toggle {
    background: linear-gradient(135deg, #e3f2fd, #bbdef5);
    color: #01579b;
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

.btn-action.toggle:hover {
    background: linear-gradient(135deg, #0288d1, #01579b);
    color: white;
}

.btn-action.delete:hover {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

/* Hilangkan garis bawah pada tombol */
.btn-primary-custom,
.btn-primary-custom:hover {
    text-decoration: none !important;
}

@media (max-width: 768px) {
    .page-title {
        font-size: 1.3rem;
    }

    .action-buttons {
        gap: 5px;
    }

    .btn-action {
        width: 28px;
        height: 28px;
    }

    .badge-status-success,
    .badge-status-secondary {
        padding: 3px 8px;
        font-size: 0.65rem;
    }

    .bank-logo-wrapper,
    .bank-logo-placeholder {
        width: 35px;
        height: 35px;
    }

    .bank-logo {
        width: 28px;
        height: 28px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// ============================================================
// VARIABLES
// ============================================================
var bankToDelete = null;
var bankNameToDelete = '';
var deleteForm = null;

var toggleBankId = null;
var toggleBankName = '';
var toggleToActive = false;
var toggleFormElement = null;

// ============================================================
// MODAL KONFIRMASI HAPUS - AJAX
// ============================================================
function openDeleteModal(id, nama, btn) {
    bankToDelete = id;
    bankNameToDelete = nama;
    deleteBtn = btn;
    document.getElementById('deleteBankName').innerText = 'Apakah Anda yakin ingin menghapus rekening bank "' + nama +
        '"?';
    document.getElementById('deleteModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    bankToDelete = null;
    deleteBtn = null;
}

document.getElementById('confirmDeleteBtn')?.addEventListener('click', function() {
    if (!bankToDelete) {
        showCustomToast('error', 'Gagal!', 'Bank tidak ditemukan');
        closeDeleteModal();
        return;
    }

    var confirmBtn = this;
    var originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Menghapus...';
    confirmBtn.disabled = true;

    fetch('{{ url("admin/metode-pembayaran") }}/' + bankToDelete, {
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

// MODAL KONFIRMASI TOGGLE STATUS (AKTIF/NONAKTIF) - AJAX
function openToggleModal(id, nama, toActive, btn) {
    toggleBankId = id;
    toggleBankName = nama;
    toggleToActive = toActive;
    toggleBtn = btn;

    var modalTitle = document.getElementById('toggleModalTitle');
    var modalMessage = document.getElementById('toggleModalMessage');

    if (toActive) {
        modalTitle.innerText = 'Aktifkan Bank';
        modalMessage.innerText = 'Apakah Anda yakin ingin mengaktifkan bank "' + nama +
            '"? Bank akan ditampilkan ke pelanggan.';
    } else {
        modalTitle.innerText = 'Nonaktifkan Bank';
        modalMessage.innerText = 'Apakah Anda yakin ingin menonaktifkan bank "' + nama +
            '"? Bank tidak akan ditampilkan ke pelanggan.';
    }

    document.getElementById('toggleModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeToggleModal() {
    document.getElementById('toggleModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    toggleBankId = null;
    toggleBtn = null;
}

document.getElementById('confirmToggleBtn')?.addEventListener('click', function() {
    if (!toggleBankId) {
        showCustomToast('error', 'Gagal!', 'Bank tidak ditemukan');
        closeToggleModal();
        return;
    }

    var confirmBtn = this;
    var originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Memproses...';
    confirmBtn.disabled = true;

    fetch('{{ url("admin/metode-pembayaran") }}/' + toggleBankId + '/toggle', {
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
            closeToggleModal();
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
            closeToggleModal();
            showCustomToast('error', 'Kesalahan Server', 'Terjadi kesalahan pada server');
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        });
});

// ============================================================
// TUTUP MODAL SAAT KLIK DI LUAR AREA
// ============================================================
window.onclick = function(event) {
    var deleteModal = document.getElementById('deleteModal');
    if (event.target == deleteModal) closeDeleteModal();

    var toggleModal = document.getElementById('toggleModal');
    if (event.target == toggleModal) closeToggleModal();
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