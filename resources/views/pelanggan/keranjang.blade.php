@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="batik-bg py-4">
    <div class="container position-relative" style="z-index: 1;">
        <div class="row mb-4">
            <div class="col-12">
                <div class="hero-title">
                    <h2 class="fw-bold mb-2"
                        style="background: linear-gradient(135deg, #1b5e20, #2e7d32, #ffc107); -webkit-background-clip: text; background-clip: text; color: transparent;">
                        <i class="bi bi-cart3 me-2" style="color: #ffc107;"></i>
                        Keranjang Belanja
                    </h2>
                    <p class="text-muted">Pastikan alamat pesanan Anda sebelum checkout</p>
                </div>
            </div>
        </div>

        @if($keranjang->isEmpty())
        <div class="empty-cart-modern">
            <div class="empty-cart-modern-icon">
                <div class="icon-wrapper-simple">
                    <i class="bi bi-basket"></i>
                </div>
            </div>
            <h3 class="empty-cart-modern-title">Keranjang Belanja Kosong</h3>
            <p class="empty-cart-modern-message">
                Belum ada produk di keranjang Anda.<br>
                Yuk, mulai belanja snackbox dan jajanan favorit Anda!
            </p>
            <div class="empty-cart-modern-action">
                <a href="{{ route('pelanggan.produk.index') }}" class="btn-shop-now-modern">
                    <i class="bi bi-shop me-2"></i> Mulai Belanja Sekarang
                </a>
            </div>
        </div>
        @else
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                        <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                            <i class="bi bi-receipt me-2"></i> Daftar Pesanan
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="cart-header d-none d-md-flex px-4 py-2 text-muted small fw-semibold"
                            style="background: #f8f9fa; border-bottom: 1px solid #e0e0e0;">
                            <div class="col-md-5">Produk</div>
                            <div class="col-md-2 text-end pe-3">Harga</div>
                            <div class="col-md-3 text-center">Jumlah</div>
                            <div class="col-md-2 text-end pe-3">Subtotal</div>
                        </div>

                        <div class="cart-items-container">
                            @foreach($keranjang as $index => $item)
                            @php
                            $minOrder = 50;
                            $satuan = 'pcs';
                            $kategoriItem = '';
                            $gambarProduk = null;

                            if ($item->produk_id && $item->produk) {
                                $kategoriItem = $item->produk->kategori->nama_kategori ?? '';
                                $gambarProduk = $item->produk->gambar;
                                if ($kategoriItem == 'Hantaran') {
                                    $minOrder = 1;
                                    $satuan = 'pcs';
                                } elseif ($kategoriItem == 'Paketan') {
                                    $minOrder = $item->produk->min_order ?? 1;
                                    $satuan = 'order';
                                } elseif ($kategoriItem == 'Jajanan Basah') {
                                    $minOrder = 50;
                                    $satuan = 'pcs';
                                }
                            } elseif ($item->custom_snackbox_id) {
                                $minOrder = 35;
                                $satuan = 'box';
                                $kategoriItem = 'Custom Snackbox';
                            }

                            $isInvalid = ($item->jumlah < $minOrder);
                            @endphp
                            <div class="cart-item {{ $isInvalid ? 'border-danger' : '' }} {{ !$loop->last ? 'border-bottom' : '' }}"
                                data-id="{{ $item->id }}" data-harga="{{ $item->harga }}">
                                <div class="row align-items-start g-3">
                                    <div class="col-md-5 col-12">
                                        <div class="d-flex gap-3">
                                            <div class="product-icon flex-shrink-0">
                                                @if($item->produk_id && $item->produk && $gambarProduk)
                                                    <img src="{{ asset('storage/produk/' . $gambarProduk) }}" 
                                                         alt="{{ $item->nama_item }}"
                                                         class="cart-product-image"
                                                         onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<i class=\'bi bi-box fs-3 text-success\'></i>';">
                                                @elseif($item->custom_snackbox_id)
                                                    <i class="bi bi-box-seam fs-3 text-success"></i>
                                                @else
                                                    <i class="bi bi-egg-fried fs-3 text-success"></i>
                                                @endif
                                            </div>
                                            <div class="product-info flex-grow-1">
                                                <h6 class="mb-1 fw-bold" style="color: #1b5e20;">{{ $item->nama_item }}</h6>

                                                @if($item->custom_snackbox_id && $item->customSnackbox)
                                                <div class="mt-2 p-2 bg-light rounded-3">
                                                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                                        <span class="badge bg-warning text-dark">{{ $item->customSnackbox->nama_ukuran ?? $item->customSnackbox->kode_ukuran }}</span>
                                                        <span class="badge bg-success">{{ $item->customSnackbox->jumlah_box }} box</span>
                                                    </div>
                                                    <div class="small text-muted">
                                                        <strong>Isi Snackbox:</strong>
                                                        <div class="mt-1 ps-2" style="border-left: 2px solid #ffc107;">
                                                            @foreach($item->customSnackbox->detail as $detail)
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <span>• {{ $detail->produk->nama_produk ?? 'Produk' }}</span>
                                                                <span class="text-success">{{ $detail->jumlah }} pcs</span>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="{{ route('pelanggan.custom-snackbox.edit', $item->custom_snackbox_id) }}"
                                                            class="btn btn-sm btn-outline-warning rounded-pill">
                                                            <i class="bi bi-pencil-square me-1"></i> Edit Snackbox
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif

                                                @if($kategoriItem && $kategoriItem != 'Hantaran' && !$item->custom_snackbox_id)
                                                <small class="text-warning d-block mt-1">
                                                    <i class="bi bi-info-circle me-1"></i> Minimal {{ $minOrder }} {{ $satuan }}
                                                </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-6 text-md-end pe-3">
                                        <div class="price-label d-md-none text-muted small mb-1">Harga</div>
                                        <div class="fw-semibold text-success item-price">{{ $item->harga_format }}</div>
                                    </div>

                                    <div class="col-md-3 col-6">
                                        <div class="quantity-label d-md-none text-muted small mb-1">Jumlah</div>
                                        <div class="d-flex align-items-center justify-content-md-center gap-2 {{ !$isInvalid ? '' : 'justify-content-start' }}">
                                            <button class="btn-quantity btn-quantity-minus" data-id="{{ $item->id }}" data-minorder="{{ $minOrder }}">
                                                <i class="bi bi-dash-lg"></i>
                                            </button>
                                            <input type="number" class="form-control form-control-sm quantity-input text-center"
                                                value="{{ $item->jumlah }}" min="1" style="width: 70px;"
                                                data-id="{{ $item->id }}" data-minorder="{{ $minOrder }}">
                                            <button class="btn-quantity btn-quantity-plus" data-id="{{ $item->id }}" data-minorder="{{ $minOrder }}">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                            @if($isInvalid)
                                            <small class="text-danger d-block d-md-inline-block ms-2">Min {{ $minOrder }}!</small>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12 text-md-end mt-2 mt-md-0 pe-3">
                                        <div class="subtotal-label d-md-none text-muted small mb-1">Subtotal</div>
                                        <div class="fw-bold text-primary fs-5 item-subtotal">{{ $item->subtotal_format }}</div>
                                        <button class="btn-delete mt-1" data-id="{{ $item->id }}">
                                            <i class="bi bi-trash3"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-between flex-wrap gap-3">
                            <a href="{{ route('pelanggan.produk.index') }}" class="btn btn-outline-success rounded-pill px-4">
                                <i class="bi bi-arrow-left me-2"></i> Lanjut Belanja
                            </a>
                            <button class="btn btn-outline-danger rounded-pill px-4" id="clearCartBtn">
                                <i class="bi bi-trash3 me-2"></i> Kosongkan Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm sticky-cart">
                    <div class="card-header bg-white rounded-top-4 py-3" style="border-bottom: 2px solid #ffc107;">
                        <h5 class="mb-0 fw-bold" style="color: #1b5e20;">
                            <i class="bi bi-calculator me-2"></i> Ringkasan Belanja
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-secondary">Total Item:</span>
                            <strong class="text-success" id="totalItems">{{ $keranjang->sum('jumlah') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-secondary">Total Harga:</span>
                            <strong class="text-warning fs-5" id="totalHarga">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        <hr>

                        <div id="cartWarningAlert">
                            @php
                            $hasInvalidItem = false;
                            foreach ($keranjang as $item) {
                            $minCheck = 50;
                            if ($item->produk_id && $item->produk && $item->produk->kategori) {
                            $kategoriCheck = $item->produk->kategori->nama_kategori;
                            if ($kategoriCheck == 'Hantaran') $minCheck = 1;
                            elseif ($kategoriCheck == 'Paketan') $minCheck = $item->produk->min_order ?? 1;
                            elseif ($kategoriCheck == 'Jajanan Basah') $minCheck = 50;
                            } elseif ($item->custom_snackbox_id) {
                            $minCheck = 35;
                            }
                            if ($item->jumlah < $minCheck) $hasInvalidItem=true; } @endphp @if($hasInvalidItem) <div
                                class="alert-warning-modern mb-3">
                                <div class="alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                                <div class="alert-content-modern">
                                    <strong>Perhatian!</strong>
                                    <p class="mb-0">Ada item yang belum memenuhi minimal pesanan. Silakan sesuaikan jumlahnya.</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <button type="button" id="checkoutBtn" class="btn-checkout-now w-100 rounded-3 py-2 fw-bold"
                            onclick="openCheckoutModal()" {{ $hasInvalidItem ? 'disabled' : '' }}>
                            <i class="bi bi-credit-card me-2"></i> Checkout Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS ITEM -->
<div id="confirmDeleteModal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 100000; overflow-y: auto;">
    <div style="display: flex; align-items: center; justify-content: center; min-height: 100%; padding: 20px;">
        <div style="background: white; border-radius: 20px; max-width: 400px; width: 100%; margin: 20px auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div style="background: linear-gradient(135deg, #dc3545, #c62828); color: white; padding: 20px; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0;"><i class="bi bi-trash3 me-2"></i> Konfirmasi Hapus</h5>
                <button type="button" onclick="closeConfirmDeleteModal()"
                    style="background: none; border: none; color: white; font-size: 28px; cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 20px;">
                <div class="text-center mb-4">
                    <i class="bi bi-question-circle" style="font-size: 60px; color: #dc3545;"></i>
                    <h5 class="mt-3">Apakah Anda yakin?</h5>
                    <p class="text-muted">Item ini akan dihapus dari keranjang Anda. Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" onclick="closeConfirmDeleteModal()" class="btn btn-outline-secondary rounded-pill px-4"><i class="bi bi-x-circle me-2"></i> Batal</button>
                    <button type="button" id="confirmDeleteBtn" class="btn btn-danger rounded-pill px-4"><i class="bi bi-trash3 me-2"></i> Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI KOSONGKAN KERANJANG -->
<div id="confirmClearCartModal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 100000; overflow-y: auto;">
    <div style="display: flex; align-items: center; justify-content: center; min-height: 100%; padding: 20px;">
        <div style="background: white; border-radius: 20px; max-width: 400px; width: 100%; margin: 20px auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div style="background: linear-gradient(135deg, #dc3545, #c62828); color: white; padding: 20px; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0;"><i class="bi bi-trash3 me-2"></i> Kosongkan Keranjang</h5>
                <button type="button" onclick="closeConfirmClearCartModal()"
                    style="background: none; border: none; color: white; font-size: 28px; cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 20px;">
                <div class="text-center mb-4">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 60px; color: #dc3545;"></i>
                    <h5 class="mt-3">Apakah Anda yakin?</h5>
                    <p class="text-muted">Semua item di keranjang Anda akan dihapus. Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" onclick="closeConfirmClearCartModal()" class="btn btn-outline-secondary rounded-pill px-4"><i class="bi bi-x-circle me-2"></i> Batal</button>
                    <button type="button" id="confirmClearCartBtn" class="btn btn-danger rounded-pill px-4"><i class="bi bi-trash3 me-2"></i> Ya, Kosongkan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL CHECKOUT - RESPONSIF & FLEKSIBEL                      -->
<!-- ============================================================ -->
<div id="customCheckoutModal"
    style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 99999; margin: 0; padding: 0; overflow-y: auto; transform: none !important; backface-visibility: hidden !important; -webkit-backface-visibility: hidden !important; will-change: auto !important;">
    <div
        style="display: flex; align-items: center; justify-content: center; min-height: 100%; width: 100%; padding: 20px 15px; box-sizing: border-box; margin: 0; transform: none !important; backface-visibility: hidden !important; -webkit-backface-visibility: hidden !important;">
        <div
            style="background: white; border-radius: 24px; max-width: 480px; width: 100%; margin: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3); overflow: hidden; max-height: 85vh; display: flex; flex-direction: column; transform: none !important; backface-visibility: hidden !important; -webkit-backface-visibility: hidden !important;">
            <!-- HEADER -->
            <div
                style="background: linear-gradient(135deg, #2e7d32, #1b5e20); color: white; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <h5 style="margin: 0; font-size: 0.95rem; font-weight: 600;"><i class="bi bi-check-circle me-2"></i> Konfirmasi Pesanan</h5>
                <button type="button" onclick="closeCheckoutModal()"
                    style="background: rgba(255,255,255,0.2); border: none; color: white; width: 28px; height: 28px; border-radius: 50%; font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;"
                    onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.2)'">&times;</button>
            </div>

            <!-- BODY (scrollable) -->
            <div style="padding: 16px 20px; overflow-y: auto; flex: 1;">
                <form method="POST" action="{{ route('pelanggan.pesanan.checkout') }}" id="formCheckoutCustom">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 4px; color: #555; font-size: 0.75rem;">Nama Lengkap</label>
                            <input type="text" value="{{ Auth::user()->name }}" disabled
                                style="width: 100%; padding: 6px 10px; border-radius: 8px; border: 1px solid #e5e7eb; background: #f8f9fa; font-size: 0.8rem;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 4px; color: #555; font-size: 0.75rem;">No. Telepon</label>
                            <input type="text" value="{{ Auth::user()->no_telepon ?? '-' }}" disabled
                                style="width: 100%; padding: 6px 10px; border-radius: 8px; border: 1px solid #e5e7eb; background: #f8f9fa; font-size: 0.8rem;">
                        </div>
                    </div>
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 4px; color: #555; font-size: 0.75rem;">
                            <i class="bi bi-calendar-event me-2 text-warning"></i>Tanggal & Jam Pengambilan <span style="color: red;">*</span>
                        </label>
                        <input type="text" name="tanggal_pengambilan" id="tanggal_pengambilan_modal"
                            style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 2px solid #ffc107; background: #fffbeb; cursor: pointer; font-size: 0.8rem;"
                            placeholder="📅 Klik untuk pilih tanggal dan jam" required>
                        <small style="color: #6b7280; display: block; margin-top: 4px; font-size: 0.6rem;">
                            <i class="bi bi-info-circle"></i> Minimal H+5, jam operasional 05:00 - 17:00 WIB
                        </small>
                        <div id="selectedDateInfoModal"
                            style="margin-top: 6px; padding: 5px 8px; background: #f0fdf4; border-radius: 6px; display: none;">
                            <i class="bi bi-check-circle-fill text-success me-1" style="font-size: 0.7rem;"></i>
                            <span style="font-size: 0.7rem;">Tanggal terpilih: </span>
                            <span id="selectedDateTextModal" style="font-weight: bold; color: #2e7d32; font-size: 0.7rem;"></span>
                        </div>
                    </div>
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 4px; color: #555; font-size: 0.75rem;">
                            <i class="bi bi-geo-alt me-2 text-danger"></i>Alamat Pengiriman <span style="color: red;">*</span>
                        </label>
                        <textarea name="alamat_pengiriman" rows="2" required
                            style="width: 100%; padding: 6px 10px; border-radius: 8px; border: 1px solid #e5e7eb; resize: vertical; font-size: 0.8rem;"
                            placeholder="Masukkan alamat lengkap...">{{ Auth::user()->alamat ?? '' }}</textarea>
                    </div>
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 4px; color: #555; font-size: 0.75rem;">
                            <i class="bi bi-pencil-square me-2 text-info"></i>Catatan Pesanan
                        </label>
                        <textarea name="catatan_pesanan" rows="2"
                            style="width: 100%; padding: 6px 10px; border-radius: 8px; border: 1px solid #e5e7eb; resize: vertical; font-size: 0.8rem;"
                            placeholder=""></textarea>
                    </div>
                    <div style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9); padding: 10px 14px; border-radius: 10px; margin-bottom: 12px; border-left: 4px solid #2e7d32;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 6px;">
                            <span style="font-weight: 600; font-size: 0.8rem;"><i class="bi bi-wallet2 me-2"></i>Total yang harus dibayar:</span>
                            <strong style="font-size: 1rem; color: #2e7d32;">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <!-- FOOTER TOMBOL - STICKY DI BAWAH -->
                    <div style="display: flex; gap: 10px; justify-content: flex-end; padding: 10px 0 2px 0; margin-top: 10px; border-top: 1px solid #f0f0f0; background: white; position: sticky; bottom: 0; z-index: 10;">
                        <button type="button" onclick="closeCheckoutModal()"
                            style="padding: 6px 16px; border-radius: 50px; border: 1px solid #e5e7eb; background: white; cursor: pointer; font-weight: 500; font-size: 0.75rem;">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </button>
                        <button type="submit"
                            style="padding: 6px 18px; border-radius: 50px; background: linear-gradient(135deg, #2e7d32, #1b5e20); color: white; border: none; cursor: pointer; font-weight: 500; font-size: 0.75rem;">
                            <i class="bi bi-check-circle me-1"></i> Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
/* ============================================ */
/* TAMPILAN KERANJANG KOSONG (ELEGAN & SIMPEL) */
/* ============================================ */
.empty-cart-modern {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 60px 20px;
    max-width: 550px;
    margin: 0 auto;
    animation: fadeInUp 0.5s ease-out;
}
.empty-cart-modern-icon {
    margin-bottom: 32px;
}
.icon-wrapper-simple {
    display: inline-block;
}
.icon-wrapper-simple i {
    font-size: 80px;
    color: #2e7d32;
    opacity: 0.5;
}
.empty-cart-modern-title {
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 16px;
    color: #1b5e20;
}
.empty-cart-modern-message {
    color: #6c757d;
    margin-bottom: 36px;
    line-height: 1.6;
    font-size: 0.95rem;
}
.empty-cart-modern-action {
    width: 100%;
}
.btn-shop-now-modern {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #2e7d32;
    color: white;
    padding: 12px 32px;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 500;
    font-size: 1rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}
.btn-shop-now-modern:hover {
    background: #1b5e20;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(46, 125, 50, 0.25);
    color: white;
}
.btn-shop-now-modern i {
    font-size: 1.1rem;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
@media (max-width: 576px) {
    .empty-cart-modern { padding: 40px 16px; }
    .icon-wrapper-simple i { font-size: 60px; }
    .empty-cart-modern-title { font-size: 1.4rem; }
    .btn-shop-now-modern { padding: 10px 24px; font-size: 0.9rem; width: 100%; }
}

/* ============================================ */
/* GAMBAR PRODUK DI KERANJANG */
/* ============================================ */
.cart-product-image {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid #e8f5e9;
}
.product-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
}
.product-icon i { font-size: 1.8rem; color: #2e7d32; }

/* ============================================ */
/* CUSTOM TOAST NOTIFICATION */
/* ============================================ */
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
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    z-index: 100001;
    transform: translateX(120%);
    transition: transform 0.3s cubic-bezier(0.68,-0.55,0.265,1.55);
    border-left: 5px solid;
    cursor: pointer;
}
.custom-toast.show { transform: translateX(0); }
.custom-toast.toast-success { border-left-color: #2e7d32; background: linear-gradient(135deg, #ffffff, #f0fdf4); }
.custom-toast.toast-error { border-left-color: #dc3545; background: linear-gradient(135deg, #ffffff, #fef2f2); }
.custom-toast.toast-warning { border-left-color: #ffc107; background: linear-gradient(135deg, #ffffff, #fffbeb); }
.custom-toast .toast-icon i { font-size: 28px; }
.custom-toast.toast-success .toast-icon i { color: #2e7d32; }
.custom-toast.toast-error .toast-icon i { color: #dc3545; }
.custom-toast.toast-warning .toast-icon i { color: #ffc107; }
.custom-toast .toast-content { flex: 1; }
.custom-toast .toast-title { font-weight: 700; font-size: 0.9rem; color: #1a1a1a; margin-bottom: 4px; }
.custom-toast .toast-message { font-size: 0.8rem; color: #666; }
.custom-toast .toast-close { background: none; border: none; font-size: 20px; cursor: pointer; opacity: 0.5; transition: opacity 0.2s; color: #333; padding: 0; line-height: 1; }
.custom-toast .toast-close:hover { opacity: 1; }
@media (max-width: 480px) {
    .custom-toast { left: 20px; right: 20px; min-width: auto; max-width: none; }
}

/* ============================================ */
/* FLATPICKR - TEMA */
/* ============================================ */
.flatpickr-calendar {
    border-radius: 20px !important;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2) !important;
    border: none !important;
    background: linear-gradient(135deg, #fff8e1 0%, #fef9e6 100%) !important;
    overflow: hidden !important;
    width: 320px !important;
}
.flatpickr-calendar.open {
    position: fixed !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    z-index: 1000000 !important;
}
.flatpickr-month {
    background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%) !important;
    margin: 0 !important;
    padding: 0 !important;
    height: 65px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 18px 18px 0 0 !important;
}
.flatpickr-current-month {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
    height: auto !important;
    position: relative !important;
    top: 0 !important;
    left: 0 !important;
    transform: none !important;
    width: 100% !important;
}
.flatpickr-current-month .flatpickr-monthDropdown-months,
.flatpickr-current-month .numInputWrapper {
    color: white !important;
    font-weight: bold !important;
    font-size: 1rem !important;
    background: transparent !important;
}
.flatpickr-monthDropdown-months {
    background: transparent !important;
    color: white !important;
    border: none !important;
}
.flatpickr-monthDropdown-months option { color: #1b5e20 !important; background: white !important; }
.numInputWrapper .numInput { color: white !important; background: transparent !important; }
.flatpickr-prev-month, .flatpickr-next-month { top: 20px !important; padding: 5px !important; }
.flatpickr-prev-month svg, .flatpickr-next-month svg { fill: white !important; stroke: white !important; }
.flatpickr-prev-month:hover svg, .flatpickr-next-month:hover svg { fill: #ffc107 !important; stroke: #ffc107 !important; transform: scale(1.1); }
.flatpickr-weekdays { background: #fff8e1 !important; padding: 10px 0 8px 0 !important; margin: 0 !important; }
.flatpickr-weekday { color: #2e7d32 !important; font-weight: bold !important; font-size: 0.8rem !important; background: transparent !important; }
.flatpickr-days { background: #fff8e1 !important; padding: 0 5px 10px 5px !important; }
.flatpickr-day {
    color: #b8860b !important;
    font-weight: 500 !important;
    border-radius: 12px !important;
    margin: 2px !important;
    transition: all 0.2s ease !important;
    background: transparent !important;
    border: none !important;
}
.flatpickr-day:hover { background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%) !important; color: #1b5e20 !important; border: none !important; transform: scale(1.05); }
.flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange { background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%) !important; color: white !important; border: none !important; box-shadow: 0 2px 8px rgba(46,125,50,0.3); }
.flatpickr-day.today { border: 2px solid #ffc107 !important; background: #fff8e1 !important; color: #1b5e20 !important; font-weight: bold !important; }
.flatpickr-day.prevMonthDay, .flatpickr-day.nextMonthDay { color: #d4c5a0 !important; opacity: 0.6 !important; }
.flatpickr-day.inRange, .flatpickr-day.week.selected { background: #e8f5e9 !important; color: #1b5e20 !important; }
.flatpickr-time {
    background: #fef9e6 !important;
    border-top: 1px solid #e0e0e0 !important;
    border-radius: 0 0 18px 18px !important;
}
.flatpickr-time input, .flatpickr-time .flatpickr-time-separator, .flatpickr-time .numInput {
    color: #1b5e20 !important;
    font-weight: bold !important;
    font-size: 1rem !important;
}
.flatpickr-time .numInputWrapper { background: #fff8e1 !important; border-radius: 8px !important; }
.flatpickr-time .numInputWrapper:hover { background: #ffecb3 !important; }
.flatpickr-time .numInputWrapper .arrowUp, .flatpickr-time .numInputWrapper .arrowDown { background-color: #ffc107 !important; border-radius: 4px !important; }
.flatpickr-time .numInputWrapper .arrowUp:hover, .flatpickr-time .numInputWrapper .arrowDown:hover { background-color: #2e7d32 !important; }
.flatpickr-day.disabled, .flatpickr-day.disabled:hover, .flatpickr-day.flatpickr-disabled, .flatpickr-day.flatpickr-disabled:hover {
    background-color: #ffebee !important;
    color: #dc3545 !important;
    text-decoration: line-through !important;
    opacity: 0.7 !important;
    cursor: not-allowed !important;
}
.flatpickr-day-nonaktif-with-tooltip {
    position: relative;
    cursor: not-allowed !important;
}
.flatpickr-day-nonaktif-with-tooltip:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 110%;
    left: 50%;
    transform: translateX(-50%);
    background: #dc3545;
    color: white;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.65rem;
    white-space: nowrap;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
.flatpickr-day-nonaktif-with-tooltip:hover::before {
    content: '';
    position: absolute;
    bottom: 105%;
    left: 50%;
    transform: translateX(-50%);
    border: 6px solid transparent;
    border-top-color: #dc3545;
    z-index: 1000;
}

/* ============================================ */
/* EXISTING STYLES (PERTAHANKAN) */
/* ============================================ */
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
.container.position-relative { position: relative; z-index: 2; }
.hero-title { text-align: center; margin-bottom: 1rem; }
.cart-header { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; color: #6c757d; }
.cart-items-container { padding: 0; }
.cart-item { padding: 20px; transition: all 0.3s ease; background: white; }
.cart-item:hover { background: #fef9e6; }
.cart-item.border-bottom { border-bottom: 1px solid #f0f0f0 !important; }
.cart-item.border-danger { border-left: 4px solid #dc3545; }
.btn-quantity {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid #dee2e6;
    background: white;
    color: #2e7d32;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    cursor: pointer;
}
.btn-quantity:hover { background: #2e7d32; border-color: #2e7d32; color: white; transform: scale(1.05); }
.quantity-input { text-align: center; -moz-appearance: textfield; }
.quantity-input::-webkit-outer-spin-button, .quantity-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.btn-delete { background: none; border: none; color: #dc3545; font-size: 0.75rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; }
.btn-delete:hover { color: #b71c1c; text-decoration: underline; }
.sticky-cart { position: sticky; top: 20px; }
.btn-checkout-now {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    border: none;
    color: white;
    transition: all 0.3s ease;
}
.btn-checkout-now:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(46,125,50,0.4); }
.btn-checkout-now:disabled { background: #6c757d; cursor: not-allowed; opacity: 0.65; }
.alert-warning-modern {
    background: linear-gradient(135deg, #fff8e1, #ffecb3);
    border-left: 4px solid #ffc107;
    border-radius: 12px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.alert-warning-modern .alert-icon i { font-size: 1.5rem; color: #f57c00; }
.alert-warning-modern .alert-content-modern { flex: 1; }
.alert-warning-modern .alert-content-modern strong { color: #e65100; display: block; margin-bottom: 4px; }
@media (max-width: 768px) {
    .cart-item .col-md-3 .d-flex { justify-content: center !important; }
}
@media (max-width: 480px) {
    .quantity-input { width: 55px !important; }
    .flatpickr-calendar.open { width: 90% !important; max-width: 320px !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%) !important; }
    .flatpickr-calendar { width: 280px !important; }
    .flatpickr-day { width: 32px !important; line-height: 32px !important; margin: 2px !important; }
}

/* ============================================ */
/* 🔥 PERBAIKAN MODAL CHECKOUT - RESPONSIF */
/* ============================================ */
#customCheckoutModal {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background: rgba(0,0,0,0.85) !important;
    z-index: 99999 !important;
    overflow-y: auto !important;
    display: none;
    margin: 0 !important;
    padding: 0 !important;
    transform: none !important;
    backface-visibility: hidden !important;
    -webkit-backface-visibility: hidden !important;
    will-change: auto !important;
}
#customCheckoutModal > div {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-height: 100% !important;
    width: 100% !important;
    padding: 20px 15px !important;
    box-sizing: border-box !important;
    margin: 0 !important;
    transform: none !important;
    backface-visibility: hidden !important;
    -webkit-backface-visibility: hidden !important;
}
#customCheckoutModal > div > div {
    background: white !important;
    border-radius: 24px !important;
    max-width: 480px !important;
    width: 100% !important;
    margin: auto !important;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3) !important;
    overflow: hidden !important;
    max-height: 85vh !important;
    display: flex !important;
    flex-direction: column !important;
    transform: none !important;
    backface-visibility: hidden !important;
    -webkit-backface-visibility: hidden !important;
}
#customCheckoutModal > div > div > div:first-child {
    background: linear-gradient(135deg, #2e7d32, #1b5e20) !important;
    color: white !important;
    padding: 14px 20px !important;
    flex-shrink: 0 !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
}
#customCheckoutModal > div > div > div:last-child {
    padding: 16px 20px !important;
    overflow-y: auto !important;
    flex: 1 !important;
}
/* Sticky footer di dalam form */
#customCheckoutModal > div > div > div:last-child form > div:last-child {
    position: sticky !important;
    bottom: 0 !important;
    background: white !important;
    padding: 10px 0 2px 0 !important;
    margin-top: 10px !important;
    border-top: 1px solid #f0f0f0 !important;
    z-index: 10 !important;
}
body.modal-open { overflow: hidden !important; }
.container { overflow-x: hidden !important; }

/* Responsive untuk layar kecil */
@media (max-width: 576px) {
    #customCheckoutModal > div {
        padding: 10px 8px !important;
    }
    #customCheckoutModal > div > div {
        max-width: 100% !important;
        border-radius: 18px !important;
        max-height: 90vh !important;
    }
    #customCheckoutModal > div > div > div:first-child {
        padding: 12px 16px !important;
    }
    #customCheckoutModal > div > div > div:last-child {
        padding: 12px 16px !important;
    }
    #customCheckoutModal > div > div > div:last-child form > div:first-child {
        grid-template-columns: 1fr !important;
        gap: 8px !important;
    }
    #customCheckoutModal > div > div > div:last-child form > div:last-child {
        flex-wrap: wrap !important;
        justify-content: center !important;
    }
    #customCheckoutModal > div > div > div:last-child form > div:last-child button {
        flex: 1 1 40% !important;
        text-align: center !important;
        justify-content: center !important;
    }
}

/* Perbaikan horizontal untuk panel ringkasan */
@media (max-width: 992px) {
    .sticky-cart { position: relative !important; top: 0 !important; }
    .cart-item .row > div { padding-left: 10px !important; padding-right: 10px !important; }
    .cart-item .col-md-2.text-end { text-align: left !important; }
    .cart-item .col-md-3 { flex: 0 0 100% !important; max-width: 100% !important; }
    .cart-item .col-md-2 { flex: 0 0 50% !important; max-width: 50% !important; }
    .cart-item .col-md-5 { flex: 0 0 100% !important; max-width: 100% !important; }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
// ========== ENHANCED CUSTOM TOAST NOTIFICATION ==========
function showToast(message, type = 'success') {
    const existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => toast.remove());

    const toastDiv = document.createElement('div');
    toastDiv.className = `custom-toast toast-${type}`;

    let iconHtml = '';
    let title = '';
    switch(type) {
        case 'success': iconHtml = '<i class="fas fa-check-circle"></i>'; title = 'Berhasil!'; break;
        case 'error': iconHtml = '<i class="fas fa-exclamation-triangle"></i>'; title = 'Gagal!'; break;
        case 'warning': iconHtml = '<i class="fas fa-exclamation-circle"></i>'; title = 'Perhatian!'; break;
        default: iconHtml = '<i class="fas fa-check-circle"></i>'; title = 'Berhasil!';
    }

    const icon = document.createElement('div');
    icon.className = 'toast-icon';
    icon.innerHTML = iconHtml;

    const content = document.createElement('div');
    content.className = 'toast-content';
    content.innerHTML = `<div class="toast-title">${title}</div><div class="toast-message">${message}</div>`;

    const closeBtn = document.createElement('button');
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
    }, 3500);
}

// ========== TAMPILKAN SESSION MESSAGES MELALUI TOAST ==========
document.addEventListener('DOMContentLoaded', function() {
    const errorMessage = '{{ session('error') }}';
    const successMessage = '{{ session('success') }}';
    const warningMessage = '{{ session('warning') }}';
    if (errorMessage && errorMessage !== '') { setTimeout(function() { showToast(errorMessage, 'error'); }, 100); }
    if (successMessage && successMessage !== '') { setTimeout(function() { showToast(successMessage, 'success'); }, 100); }
    if (warningMessage && warningMessage !== '') { setTimeout(function() { showToast(warningMessage, 'warning'); }, 100); }
    console.log('🔍 Debug: Tanggal Nonaktif Data:', @json($tanggalNonaktifList ?? []));
});

// ========== UPDATE CART SUMMARY ==========
function updateCartSummary() {
    let totalItems = 0;
    let totalHarga = 0;
    let hasInvalid = false;

    document.querySelectorAll('.cart-item').forEach(item => {
        const jumlahInput = item.querySelector('.quantity-input');
        const jumlah = jumlahInput ? parseInt(jumlahInput.value) || 0 : 0;
        const hargaElem = item.querySelector('.item-price');
        let harga = 0;
        if (hargaElem) {
            const hargaText = hargaElem.innerText;
            harga = parseInt(hargaText.replace(/[^0-9]/g, '')) || 0;
        }
        const subtotal = jumlah * harga;
        const subtotalElem = item.querySelector('.item-subtotal');
        if (subtotalElem) subtotalElem.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        totalItems += jumlah;
        totalHarga += subtotal;

        const minOrder = item.querySelector('.btn-quantity-minus')?.dataset.minorder;
        if (minOrder && jumlah < parseInt(minOrder)) hasInvalid = true;
    });

    document.getElementById('totalItems').innerText = totalItems;
    document.getElementById('totalHarga').innerText = 'Rp ' + totalHarga.toLocaleString('id-ID');

    const checkoutBtn = document.getElementById('checkoutBtn');
    const warningDiv = document.getElementById('cartWarningAlert');
    if (hasInvalid) {
        if (checkoutBtn) checkoutBtn.disabled = true;
        if (warningDiv && !warningDiv.querySelector('.alert-warning-modern')) {
            warningDiv.innerHTML = `<div class="alert-warning-modern mb-3">
                <div class="alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <div class="alert-content-modern">
                    <strong>Perhatian!</strong>
                    <p class="mb-0">Ada item yang belum memenuhi minimal pesanan. Silakan sesuaikan jumlahnya.</p>
                </div>
            </div>`;
        }
    } else {
        if (checkoutBtn) checkoutBtn.disabled = false;
        if (warningDiv && warningDiv.querySelector('.alert-warning-modern')) {
            warningDiv.innerHTML = '';
        }
    }
}

// ========== UPDATE JUMLAH (AJAX) ==========
function updateJumlah(id, newJumlah, minOrder) {
    if (newJumlah < 1) return;
    if (newJumlah < minOrder) {
        showToast(`Minimal pesanan ${minOrder} untuk item ini!`, 'warning');
        const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
        if (input) input.value = minOrder;
        return;
    }
    fetch(`/pelanggan/keranjang/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ jumlah: newJumlah })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartSummary();
            if (data.message) showToast(data.message, 'success');
        } else {
            showToast(data.message || 'Gagal update jumlah', 'error');
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            if (input && input.dataset.oldvalue) input.value = input.dataset.oldvalue;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan pada server', 'error');
    });
}

// ========== HAPUS ITEM ==========
function deleteItem(id) {
    fetch(`/pelanggan/keranjang/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const itemElement = document.querySelector(`.cart-item[data-id="${id}"]`);
            if (itemElement) itemElement.remove();
            updateCartSummary();
            if (data.message) showToast(data.message, 'success');
            if (document.querySelectorAll('.cart-item').length === 0) setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message || 'Gagal hapus item', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan pada server', 'error');
    });
}

// ========== KOSONGKAN KERANJANG ==========
function clearCart() {
    fetch('{{ route("pelanggan.keranjang.kosongkan") }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.message) showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message || 'Gagal kosongkan keranjang', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan pada server', 'error');
    });
}

// ========== MODAL HANDLERS ==========
let deleteItemId = null;
window.openDeleteModal = function(id) {
    deleteItemId = id;
    document.getElementById('confirmDeleteModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
};
window.closeConfirmDeleteModal = function() {
    document.getElementById('confirmDeleteModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    deleteItemId = null;
};
document.getElementById('confirmDeleteBtn')?.addEventListener('click', function() {
    if (deleteItemId) {
        deleteItem(deleteItemId);
        closeConfirmDeleteModal();
    }
});

window.openClearCartModal = function() {
    document.getElementById('confirmClearCartModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
};
window.closeConfirmClearCartModal = function() {
    document.getElementById('confirmClearCartModal').style.display = 'none';
    document.body.style.overflow = 'auto';
};
document.getElementById('confirmClearCartBtn')?.addEventListener('click', function() {
    clearCart();
    closeConfirmClearCartModal();
});
document.getElementById('clearCartBtn')?.addEventListener('click', openClearCartModal);

// ========== TOMBOL +/-, HAPUS ==========
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const id = this.dataset.id;
        if (id) openDeleteModal(id);
    });
});

document.querySelectorAll('.btn-quantity-minus').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const minOrder = parseInt(this.dataset.minorder);
        const input = this.closest('.d-flex').querySelector('.quantity-input');
        let currentVal = parseInt(input.value) || 1;
        let newVal = currentVal - 1;
        if (newVal >= minOrder) {
            input.value = newVal;
            updateJumlah(id, newVal, minOrder);
        } else {
            showToast(`Minimal pesanan ${minOrder} untuk item ini!`, 'warning');
        }
    });
});

document.querySelectorAll('.btn-quantity-plus').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const minOrder = parseInt(this.dataset.minorder);
        const input = this.closest('.d-flex').querySelector('.quantity-input');
        let currentVal = parseInt(input.value) || 1;
        let newVal = currentVal + 1;
        input.value = newVal;
        updateJumlah(id, newVal, minOrder);
    });
});

// ========== INPUT MANUAL ==========
document.querySelectorAll('.quantity-input').forEach(input => {
    input.dataset.oldvalue = input.value;

    const handleUpdate = function() {
        let newVal = parseInt(this.value);
        const minOrder = parseInt(this.dataset.minorder);
        if (isNaN(newVal) || newVal < 1) newVal = 1;
        if (newVal < minOrder) {
            showToast(`Minimal pesanan ${minOrder} untuk item ini!`, 'warning');
            this.value = minOrder;
            newVal = minOrder;
        }
        const id = this.dataset.id;
        if (newVal != this.dataset.oldvalue) {
            updateJumlah(id, newVal, minOrder);
            this.dataset.oldvalue = this.value;
        }
    };

    input.removeEventListener('blur', handleUpdate);
    input.addEventListener('blur', handleUpdate);

    input.removeEventListener('keypress', function(e) {
        if (e.key === 'Enter') handleUpdate.call(this);
    });
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            handleUpdate.call(this);
        }
    });
});

// ========== FLATPICKR & CHECKOUT MODAL ==========
let tanggalNonaktifData = @json($tanggalNonaktifList ?? []);
let tanggalNonaktifMap = new Map();
tanggalNonaktifData.forEach(item => {
    if (item.tanggal) {
        tanggalNonaktifMap.set(item.tanggal, item.keterangan || 'Kapasitas penuh / Libur');
    }
});

let tanggalNonaktifDates = tanggalNonaktifData.map(item => {
    if (item.tanggal) {
        let parts = item.tanggal.split('-');
        if (parts.length === 3) {
            return `${parts[0]}-${String(parts[1]).padStart(2, '0')}-${String(parts[2]).padStart(2, '0')}`;
        }
    }
    return null;
}).filter(t => t !== null).map(t => new Date(t));

function getKeteranganFromDate(date) {
    if (!date) return null;
    let d = new Date(date);
    if (isNaN(d.getTime())) return null;
    let year = d.getFullYear();
    let month = String(d.getMonth() + 1).padStart(2, '0');
    let day = String(d.getDate()).padStart(2, '0');
    let dateStr = `${year}-${month}-${day}`;
    return tanggalNonaktifMap.get(dateStr) || null;
}

function isTanggalNonaktif(date) {
    if (!date) return false;
    let d = new Date(date);
    if (isNaN(d.getTime())) return false;
    return tanggalNonaktifDates.some(nd => 
        nd.getFullYear() === d.getFullYear() && 
        nd.getMonth() === d.getMonth() && 
        nd.getDate() === d.getDate()
    );
}

function getDefaultDate() {
    let defaultDate = new Date();
    defaultDate.setDate(defaultDate.getDate() + 5);
    defaultDate.setHours(5, 0, 0, 0);
    while (isTanggalNonaktif(defaultDate)) defaultDate.setDate(defaultDate.getDate() + 1);
    return defaultDate;
}

function updateDateInfoModal(date) {
    if (date && !isNaN(new Date(date).getTime())) {
        let formattedDate = new Date(date).toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        const textElement = document.getElementById('selectedDateTextModal');
        const infoElement = document.getElementById('selectedDateInfoModal');
        if (textElement && infoElement) {
            textElement.innerText = formattedDate;
            infoElement.style.display = 'block';
        }
    } else {
        const infoElement = document.getElementById('selectedDateInfoModal');
        if (infoElement) infoElement.style.display = 'none';
    }
}

// 🔥 PERBAIKAN FUNGSI MODAL CHECKOUT
window.openCheckoutModal = function() {
    const modal = document.getElementById('customCheckoutModal');
    if (modal) {
        // Pindahkan modal ke body jika masih di dalam container
        if (modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }
        modal.style.display = 'block';
        document.body.classList.add('modal-open');
        modal.scrollTop = 0;
    }
};

window.closeCheckoutModal = function() {
    const modal = document.getElementById('customCheckoutModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
    }
};

document.addEventListener('DOMContentLoaded', function() {
    if (typeof flatpickr !== 'undefined') {
        window.datePickerModal = flatpickr("#tanggal_pengambilan_modal", {
            enableTime: true,
            dateFormat: "Y-m-d H:i:S",
            locale: "id",
            minDate: new Date().fp_incr(5),
            maxDate: new Date().fp_incr(90),
            minTime: "05:00",
            maxTime: "17:00",
            defaultDate: getDefaultDate(),
            disable: tanggalNonaktifDates,
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                let dateObj = dayElem.dateObj;
                if (dateObj && isTanggalNonaktif(dateObj)) {
                    let keterangan = getKeteranganFromDate(dateObj) || 'Tanggal tidak tersedia';
                    dayElem.title = `⚠️ Tidak tersedia: ${keterangan}`;
                    dayElem.classList.add('flatpickr-day-nonaktif-with-tooltip');
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    let selectedDate = selectedDates[0];
                    updateDateInfoModal(selectedDate);
                    if (isTanggalNonaktif(selectedDate)) {
                        let dateStrFormatted = selectedDate.toISOString().split('T')[0];
                        const alasan = getKeteranganFromDate(selectedDate) || 'Kapasitas penuh / Libur';
                        showToast(`Tanggal ${dateStrFormatted} tidak tersedia.\nAlasan: ${alasan}`, 'error');
                        instance.clear();
                        instance.setDate(getDefaultDate());
                        updateDateInfoModal(getDefaultDate());
                    }
                }
            },
            onReady: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) updateDateInfoModal(selectedDates[0]);
                else if (instance.config.defaultDate) updateDateInfoModal(instance.config.defaultDate);
            }
        });
        updateDateInfoModal(getDefaultDate());
    }

    const formCheckout = document.getElementById('formCheckoutCustom');
    if (formCheckout) {
        formCheckout.addEventListener('submit', function(e) {
            console.log('🔍 Form checkout disubmit');
            const tanggalInput = document.getElementById('tanggal_pengambilan_modal');
            const tanggalValue = tanggalInput ? tanggalInput.value : '';
            if (tanggalValue) {
                let tanggalDate = tanggalValue.split(' ')[0];
                let isNonaktif = tanggalNonaktifMap.has(tanggalDate);
                if (isNonaktif) {
                    e.preventDefault();
                    const alasan = tanggalNonaktifMap.get(tanggalDate) || 'Kapasitas penuh / Libur';
                    showToast(`Tanggal ${tanggalDate} tidak tersedia.\nAlasan: ${alasan}`, 'error');
                    return false;
                }
            }
            let items = @json($keranjang);
            for (let i = 0; i < items.length; i++) {
                let item = items[i];
                let minOrder = 50;
                let satuan = 'pcs';
                if (item.produk_id && item.produk && item.produk.kategori) {
                    let kategori = item.produk.kategori.nama_kategori;
                    if (kategori == 'Hantaran') minOrder = 1;
                    else if (kategori == 'Paketan') minOrder = item.produk.min_order ?? 1;
                    else if (kategori == 'Jajanan Basah') minOrder = 50;
                } else if (item.custom_snackbox_id) {
                    minOrder = 35;
                    satuan = 'box';
                }
                if (item.jumlah < minOrder) {
                    e.preventDefault();
                    showToast(`"${item.nama_item}" minimal pesanan ${minOrder} ${satuan}`, 'warning');
                    return false;
                }
            }
            return true;
        });
    }

    updateCartSummary();
});

window.onclick = function(event) {
    const deleteModal = document.getElementById('confirmDeleteModal');
    if (event.target === deleteModal) closeConfirmDeleteModal();
    const clearCartModal = document.getElementById('confirmClearCartModal');
    if (event.target === clearCartModal) closeConfirmClearCartModal();
    const checkoutModal = document.getElementById('customCheckoutModal');
    if (event.target === checkoutModal) closeCheckoutModal();
};
</script>
@endpush
@endsection