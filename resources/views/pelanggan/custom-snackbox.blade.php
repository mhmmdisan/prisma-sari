@extends('layouts.app')

@section('title', isset($customSnackbox) ? 'Edit Custom Snackbox' : 'Custom Snackbox')

@section('content')
<div class="batik-bg py-4">
    <div class="container">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-title text-center text-md-start">
                    <h2 class="fw-bold mb-2"
                        style="background: linear-gradient(135deg, #1b5e20, #2e7d32, #ffc107); -webkit-background-clip: text; background-clip: text; color: transparent;">
                        <i class="bi bi-box-seam me-2" style="color: #ffc107;"></i>
                        {{ isset($customSnackbox) ? 'Edit Custom Snackbox' : 'Buat Custom Snackbox' }}
                    </h2>
                    <p class="text-muted">
                        {{ isset($customSnackbox) ? 'Ubah komposisi snackbox Anda' : 'Pilih jajanan favorit Anda untuk dijadikan snackbox custom' }}
                    </p>
                </div>
            </div>
        </div>

        <form method="POST"
            action="{{ isset($customSnackbox) ? route('pelanggan.custom-snackbox.update', $customSnackbox->id) : route('pelanggan.custom-snackbox.store') }}"
            id="formCustomSnackbox">
            @csrf
            @if(isset($customSnackbox))
            @method('PUT')
            @endif

            <div class="row g-4">
                <!-- Kolom Kiri: Pilihan Jajanan -->
                <div class="col-12 col-lg-8">
                    <div class="card border-0 rounded-4 shadow-sm h-100">
                        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                            <h5 class="mb-0 fw-bold" style="color: #1b5e20; line-height: 1.4;">
                                <i class="bi bi-grid-3x3-gap-fill me-2"></i> Pilih Jajanan
                            </h5>
                            <small class="text-muted">Pilih jajanan sesuai selera Anda</small>
                        </div>
                        <div class="card-body">
                            <div class="row g-3" id="daftarJajanan">
                                @foreach($daftarJajanan as $jajanan)
                                @php
                                $isSelected = false;
                                if (isset($customSnackbox) && $customSnackbox->detail) {
                                    $isSelected = $customSnackbox->detail->contains('produk_id', $jajanan->id);
                                }
                                @endphp
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div
                                        class="jajanan-card rounded-4 p-3 text-center h-100 {{ $isSelected ? 'selected-card' : '' }}">
                                        @if($jajanan->gambar)
                                        <img src="{{ asset('storage/produk/' . $jajanan->gambar) }}"
                                            class="img-fluid rounded-3 mb-2"
                                            style="height: 80px; width: 80px; object-fit: cover; margin: 0 auto;"
                                            alt="{{ $jajanan->nama_produk }}">
                                        @else
                                        <div class="mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle bg-light"
                                            style="width: 70px; height: 70px;">
                                            <i class="bi bi-cake2 fs-1 text-warning"></i>
                                        </div>
                                        @endif
                                        <h6 class="fw-bold mb-1" style="color: #1b5e20;">
                                            {{ Str::limit($jajanan->nama_produk, 25) }}
                                        </h6>
                                        <p class="text-success fw-bold mb-2">{{ $jajanan->harga_format }}</p>

                                        <button type="button"
                                            class="btn btn-pilih-jajanan w-100 rounded-pill {{ $isSelected ? 'btn-success' : 'btn-outline-success' }}"
                                            data-id="{{ $jajanan->id }}" data-harga="{{ $jajanan->harga }}"
                                            data-nama="{{ $jajanan->nama_produk }}">
                                            <i
                                                class="bi {{ $isSelected ? 'bi-check-circle' : 'bi-plus-circle' }} me-1"></i>
                                            {{ $isSelected ? 'Terpilih' : 'Pilih' }}
                                        </button>

                                        <input type="hidden" name="jajanan[{{ $jajanan->id }}][id]"
                                            value="{{ $jajanan->id }}">
                                        <input type="hidden" name="jajanan[{{ $jajanan->id }}][selected]"
                                            value="{{ $isSelected ? '1' : '0' }}" class="selected-status">
                                        <input type="hidden" name="jajanan[{{ $jajanan->id }}][jumlah]" value="1"
                                            class="jumlah-item">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Ringkasan Snackbox -->
                <div class="col-12 col-lg-4">
                    <div class="card border-0 rounded-4 shadow-sm sticky-sidebar h-100">
                        <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                            <h5 class="mb-0 fw-bold" style="color: #1b5e20; line-height: 1.4;">
                                <i class="bi bi-calculator me-2"></i> Ringkasan Snackbox
                            </h5>
                            <small class="text-muted">Detail pesanan Anda</small>
                        </div>
                        <div class="card-body scrollable-summary">
                            <div id="alertContainer"></div>

                            <!-- Nama Snackbox -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="bi bi-pencil me-1"></i> Nama Snackbox
                                </label>
                                <input type="text" name="nama_box" class="form-control rounded-3"
                                    placeholder="Contoh: Box Ultah, Box Rapat"
                                    value="{{ old('nama_box', $customSnackbox->nama_box ?? '') }}">
                                <small class="text-muted">Kosongkan jika tidak perlu</small>
                            </div>

                            <!-- Pilih Ukuran -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="bi bi-box-seam me-1"></i> Pilih Ukuran
                                </label>
                                <div class="row g-2">
                                    @foreach($ukuran as $kode => $uk)
                                    @php
                                    $isChecked = (old('kode_ukuran', $customSnackbox->kode_ukuran ?? '') == $kode);
                                    @endphp
                                    <div class="col-6">
                                        <div
                                            class="form-check ukuran-card p-2 rounded-3 {{ $isChecked ? 'ukuran-selected' : '' }}">
                                            <input class="form-check-input" type="radio" name="kode_ukuran"
                                                value="{{ $kode }}" id="ukuran_{{ $kode }}"
                                                data-jumlah-item="{{ $uk['jumlah_item'] }}"
                                                data-harga-box="{{ $uk['harga'] }}" {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="ukuran_{{ $kode }}">
                                                <strong>{{ $uk['nama'] }}</strong><br>
                                                <small>Kapasitas {{ $uk['jumlah_item'] }} item</small>
                                                <br>
                                                <span class="text-success fw-semibold">Rp
                                                    {{ number_format($uk['harga'], 0, ',', '.') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="alert alert-warning rounded-3 mb-3"
                                style="background: linear-gradient(135deg, #fff8e1, #ffecb3); border: none; border-left: 4px solid #ffc107;">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                Minimal pemesanan: <strong>35 box</strong>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary mb-2">
                                    <i class="bi bi-calculator me-1"></i> Jumlah Box
                                </label>
                                <div class="d-flex align-items-center justify-content-between bg-light rounded-3 p-2">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary rounded-circle quantity-btn"
                                        id="btnKurangiJumlahBox" onclick="kurangiJumlahBox()"
                                        style="width: 36px; height: 36px; padding: 0;">
                                        <i class="bi bi-dash-lg"></i>
                                    </button>
                                    <input type="number" name="jumlah_box" id="jumlah_box"
                                        class="form-control text-center fw-semibold border-0 bg-transparent"
                                        value="{{ old('jumlah_box', $customSnackbox->jumlah_box ?? 35) }}" min="35"
                                        max="999" style="width: 80px; font-size: 1.2rem;">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary rounded-circle quantity-btn"
                                        id="btnTambahJumlahBox" onclick="tambahJumlahBox()"
                                        style="width: 36px; height: 36px; padding: 0;">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-1">Minimal 35 box</small>
                            </div>

                            <hr class="my-3">

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="bi bi-check-circle me-1"></i> Item yang Dipilih
                                </label>
                                <div id="daftarItemTerpilih" class="small bg-light rounded-3 p-2 scrollable-items"
                                    style="min-height: 100px; max-height: 180px; overflow-y: auto;">
                                    <span class="text-muted">Belum ada item dipilih</span>
                                </div>
                            </div>

                            <div class="bg-light rounded-3 p-3 mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Item Terpilih:</span>
                                    <strong id="totalItemTerpilih" class="text-success">0</strong> /
                                    <span id="kapasitasBox"
                                        class="text-muted">{{ $customSnackbox->jumlah_item ?? '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Harga Jajanan:</span>
                                    <strong id="totalHargaJajanan" class="text-dark">Rp 0</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Harga Box Kosong:</span>
                                    <strong id="hargaBox" class="text-dark">Rp 0</strong>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-semibold">Harga per Box:</span>
                                    <strong class="text-success fw-bold fs-5" id="hargaPerBox">Rp 0</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">Total Semua:</span>
                                    <strong class="text-warning fw-bold fs-5" id="hargaTotal">Rp 0</strong>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 rounded-3 py-2 fw-bold" id="btnSimpan"
                                {{ !isset($customSnackbox) ? 'disabled' : '' }}>
                                <i class="bi {{ isset($customSnackbox) ? 'bi-save' : 'bi-cart-plus' }} me-2"></i>
                                {{ isset($customSnackbox) ? 'Simpan Perubahan' : 'Simpan ke Keranjang' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// ========== INISIALISASI DATA ==========
let kapasitasBox = {{ isset($customSnackbox) ? ($customSnackbox->jumlah_item ?? 0) : 0 }};
let hargaBox = 0;

// 🔥 Set harga dan kapasitas awal dari ukuran yang dipilih (jika ada)
@if(isset($customSnackbox) && $customSnackbox->kode_ukuran)
    @php
    $selectedUkuran = $ukuran[$customSnackbox->kode_ukuran] ?? null;
    @endphp
    hargaBox = {{ $selectedUkuran['harga'] ?? 0 }};
    kapasitasBox = {{ $selectedUkuran['jumlah_item'] ?? 0 }};
@endif

// Fungsi showAlert
function showAlert(message, type = 'warning') {
    let alertContainer = document.getElementById('alertContainer');
    if (!alertContainer) return;

    let icon = type === 'warning' ? 'exclamation-triangle' : 'check-circle';
    let bgGradient = type === 'warning' ? 'linear-gradient(135deg, #fff8e1, #ffecb3)' :
        'linear-gradient(135deg, #e8f5e9, #c8e6c9)';
    let color = type === 'warning' ? '#e67e22' : '#2e7d32';
    let borderColor = type === 'warning' ? '#ffc107' : '#2e7d32';

    let alertHtml = `
        <div class="alert-custom mb-3" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; background: ${bgGradient}; border-left: 4px solid ${borderColor}; animation: slideIn 0.3s ease;">
            <i class="fas fa-${icon}" style="font-size: 18px; color: ${color};"></i>
            <div class="alert-content" style="flex: 1;">${message}</div>
            <button type="button" class="btn-close" style="font-size: 10px;" onclick="this.parentElement.remove()"></button>
        </div>
    `;
    alertContainer.innerHTML = alertHtml;
    setTimeout(() => {
        let alert = alertContainer.querySelector('.alert-custom');
        if (alert) alert.remove();
    }, 4000);
}

// 🔥 Fungsi untuk menghitung total item terpilih dan total harga dari hidden input
function calculateTotals() {
    let totalItem = 0;
    let totalHarga = 0;
    document.querySelectorAll('.btn-pilih-jajanan').forEach(btn => {
        let id = btn.dataset.id;
        let harga = parseInt(btn.dataset.harga);
        let input = document.querySelector(`input[name="jajanan[${id}][selected]"]`);
        if (input && input.value === '1') {
            totalItem++;
            totalHarga += harga;
        }
    });
    return { totalItem, totalHarga };
}

// 🔥 Fungsi untuk memperbarui semua komponen UI berdasarkan hidden input
function updateAllUI() {
    let { totalItem, totalHarga } = calculateTotals();
    let html = '';

    // Buat daftar item terpilih
    document.querySelectorAll('.btn-pilih-jajanan').forEach(btn => {
        let id = btn.dataset.id;
        let input = document.querySelector(`input[name="jajanan[${id}][selected]"]`);
        if (input && input.value === '1') {
            let nama = btn.dataset.nama;
            let harga = parseInt(btn.dataset.harga);
            html += `
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-white rounded-3 border">
                    <div class="d-flex align-items-center gap-2 flex-grow-1">
                        <i class="bi bi-cake2 text-warning"></i>
                        <span class="small fw-semibold text-dark">${nama}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="small fw-semibold text-success">Rp ${harga.toLocaleString('id-ID')}</span>
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-circle btn-remove-item" data-id="${id}" style="width: 28px; height: 28px; padding: 0;">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>`;
        }
    });

    const daftarItem = document.getElementById('daftarItemTerpilih');
    if (daftarItem) {
        daftarItem.innerHTML = totalItem > 0 ? html :
            '<div class="text-center text-muted py-3"><i class="bi bi-emoji-frown me-1"></i> Belum ada item dipilih</div>';
    }

    document.getElementById('totalItemTerpilih').innerText = totalItem;
    document.getElementById('totalHargaJajanan').innerHTML = 'Rp ' + totalHarga.toLocaleString('id-ID');

    // Update tampilan tombol pilih (warna dan teks) berdasarkan hidden input
    document.querySelectorAll('.btn-pilih-jajanan').forEach(btn => {
        let id = btn.dataset.id;
        let input = document.querySelector(`input[name="jajanan[${id}][selected]"]`);
        let card = btn.closest('.jajanan-card');
        if (input && input.value === '1') {
            btn.classList.remove('btn-outline-success');
            btn.classList.add('btn-success');
            btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Terpilih';
            if (card) card.classList.add('selected-card');
        } else {
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-success');
            btn.innerHTML = '<i class="bi bi-plus-circle me-1"></i> Pilih';
            if (card) card.classList.remove('selected-card');
        }
    });

    // Validasi kapasitas dan tombol simpan
    let btnSimpan = document.getElementById('btnSimpan');
    if (kapasitasBox > 0) {
        if (totalItem === 0) {
            btnSimpan.disabled = true;
            showAlert('Silakan pilih jajanan terlebih dahulu!', 'warning');
        } else if (totalItem < kapasitasBox) {
            btnSimpan.disabled = true;
            showAlert(`Item yang dipilih (${totalItem}) kurang dari kapasitas box (${kapasitasBox})!`, 'warning');
        } else if (totalItem > kapasitasBox) {
            btnSimpan.disabled = true;
            showAlert(`Item yang dipilih (${totalItem}) melebihi kapasitas box (${kapasitasBox})!`, 'warning');
        } else {
            btnSimpan.disabled = false;
            document.getElementById('alertContainer').innerHTML = '';
        }
    } else {
        btnSimpan.disabled = true;
    }

    // Update harga total (box + jajanan)
    let jumlahBox = parseInt(document.getElementById('jumlah_box')?.value) || 35;
    let hargaPerBox = hargaBox + totalHarga;
    let hargaTotal = hargaPerBox * jumlahBox;
    document.getElementById('hargaPerBox').innerHTML = 'Rp ' + hargaPerBox.toLocaleString('id-ID');
    document.getElementById('hargaTotal').innerHTML = 'Rp ' + hargaTotal.toLocaleString('id-ID');
}

// 🔥 Event listener untuk tombol pilih jajanan (toggle hidden input)
document.getElementById('daftarJajanan').addEventListener('click', function(e) {
    let btn = e.target.closest('.btn-pilih-jajanan');
    if (!btn) return;

    let id = btn.dataset.id;
    let input = document.querySelector(`input[name="jajanan[${id}][selected]"]`);
    if (!input) return;

    let currentVal = input.value;
    if (currentVal === '1') {
        // Hapus pilihan
        input.value = '0';
        updateAllUI();
    } else {
        // Tambah pilihan, cek kapasitas dulu
        let { totalItem } = calculateTotals();
        if (kapasitasBox > 0 && totalItem >= kapasitasBox) {
            showAlert(`Kapasitas box sudah penuh! Maksimal ${kapasitasBox} item.`, 'warning');
            return;
        }
        input.value = '1';
        updateAllUI();
    }
});

// 🔥 Hapus item dari daftar ringkasan (ubah hidden input menjadi 0)
document.getElementById('daftarItemTerpilih').addEventListener('click', function(e) {
    let btn = e.target.closest('.btn-remove-item');
    if (btn) {
        let id = btn.dataset.id;
        let input = document.querySelector(`input[name="jajanan[${id}][selected]"]`);
        if (input && input.value === '1') {
            input.value = '0';
            updateAllUI();
        }
    }
});

// 🔥 Radio ukuran
document.querySelectorAll('input[name="kode_ukuran"]').forEach(radio => {
    radio.addEventListener('change', function() {
        kapasitasBox = parseInt(this.dataset.jumlahItem);
        hargaBox = parseInt(this.dataset.hargaBox);

        document.getElementById('kapasitasBox').innerHTML = kapasitasBox;
        document.getElementById('hargaBox').innerHTML = 'Rp ' + hargaBox.toLocaleString('id-ID');

        document.querySelectorAll('.ukuran-card').forEach(card => {
            card.style.border = '1px solid #e0e0e0';
            card.style.background = 'white';
            card.classList.remove('ukuran-selected');
        });
        let parentCard = this.closest('.ukuran-card');
        parentCard.style.border = '2px solid #ffc107';
        parentCard.style.background = '#fef9e6';
        parentCard.classList.add('ukuran-selected');

        let { totalItem } = calculateTotals();
        if (totalItem > kapasitasBox) {
            showAlert('Ukuran box berubah. Item yang dipilih melebihi kapasitas baru. Silakan sesuaikan pilihan Anda.', 'warning');
        }
        updateAllUI();
    });
});

// 🔥 Jumlah Box
function kurangiJumlahBox() {
    let input = document.getElementById('jumlah_box');
    if (input) {
        let val = parseInt(input.value);
        if (val > 35) {
            input.value = val - 1;
            updateAllUI();
        }
    }
}

function tambahJumlahBox() {
    let input = document.getElementById('jumlah_box');
    if (input) {
        let val = parseInt(input.value);
        if (val < 999) {
            input.value = val + 1;
            updateAllUI();
        }
    }
}

document.getElementById('jumlah_box')?.addEventListener('change', updateAllUI);

// 🔥 Inisialisasi setelah halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Set radio yang sudah dipilih sebelumnya
    let checkedRadio = document.querySelector('input[name="kode_ukuran"]:checked');
    if (checkedRadio) {
        hargaBox = parseInt(checkedRadio.dataset.hargaBox);
        kapasitasBox = parseInt(checkedRadio.dataset.jumlahItem);
        document.getElementById('hargaBox').innerHTML = 'Rp ' + hargaBox.toLocaleString('id-ID');
        document.getElementById('kapasitasBox').innerHTML = kapasitasBox;
    }
    updateAllUI();
});
</script>
@endpush

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

/* ========== CARD HEADER SEJAJAR ========== */
.card-header {
    padding: 16px 20px !important;
}

.card-header h5 {
    font-size: 1.1rem;
    margin-bottom: 4px !important;
}

.card-header small {
    font-size: 0.7rem;
    opacity: 0.8;
}

/* ========== STICKY SIDEBAR ========== */
.sticky-sidebar {
    position: sticky;
    top: 90px;
    z-index: 10;
    max-height: calc(100vh - 120px);
    display: flex;
    flex-direction: column;
}

.scrollable-summary {
    flex: 1;
    overflow-y: auto;
    max-height: calc(80vh - 80px);
    padding: 1rem;
}

.scrollable-summary::-webkit-scrollbar {
    width: 6px;
}

.scrollable-summary::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.scrollable-summary::-webkit-scrollbar-thumb {
    background: #ffc107;
    border-radius: 10px;
}

.scrollable-summary::-webkit-scrollbar-thumb:hover {
    background: #2e7d32;
}

.scrollable-items {
    overflow-y: auto;
    max-height: 180px;
}

.scrollable-items::-webkit-scrollbar {
    width: 4px;
}

.scrollable-items::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.scrollable-items::-webkit-scrollbar-thumb {
    background: #ffc107;
    border-radius: 10px;
}

/* ========== ANIMATIONS ========== */
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

/* ========== JAJANAN CARD ========== */
.jajanan-card {
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
    background: white;
    border-radius: 16px;
    cursor: pointer;
}

.jajanan-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(46, 125, 50, 0.1);
}

.jajanan-card.selected-card {
    border: 2px solid #2e7d32;
    background: linear-gradient(135deg, #f0fff0, #e8f5e9);
}

/* ========== UKURAN CARD ========== */
.ukuran-card {
    transition: all 0.2s ease;
    cursor: pointer;
}

.ukuran-card:hover {
    border-color: #ffc107 !important;
    background: #fef9e6;
}

.ukuran-card.ukuran-selected {
    border: 2px solid #ffc107 !important;
    background: #fef9e6 !important;
}

/* ========== QUANTITY BUTTON ========== */
.quantity-btn {
    transition: all 0.2s ease;
    background: white;
    border: 1px solid #dee2e6;
    color: #2e7d32;
}

.quantity-btn:hover {
    background: #2e7d32;
    border-color: #2e7d32;
    color: white;
    transform: scale(1.02);
}

/* ========== RESPONSIVE ========== */
.h-100 {
    height: 100% !important;
}

@media (max-width: 768px) {
    .sticky-sidebar {
        position: relative;
        top: 0;
        margin-top: 20px;
        max-height: none;
    }

    .scrollable-summary {
        max-height: none;
        overflow-y: visible;
    }

    .quantity-btn {
        width: 32px !important;
        height: 32px !important;
    }

    #jumlah_box {
        font-size: 1rem !important;
        width: 70px !important;
    }

    .card-header {
        padding: 12px 16px !important;
    }
}
</style>
@endsection