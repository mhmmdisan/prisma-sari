<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Import Controller
|--------------------------------------------------------------------------
*/

// Pelanggan
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboardController;
use App\Http\Controllers\Pelanggan\ProdukController;
use App\Http\Controllers\Pelanggan\CustomSnackboxController;
use App\Http\Controllers\Pelanggan\KeranjangController;
use App\Http\Controllers\Pelanggan\PesananController as PelangganPesananController;
use App\Http\Controllers\Pelanggan\MetodePembayaranController as PelangganMetodePembayaranController;

// Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\PesananController as AdminPesananController;
use App\Http\Controllers\Admin\TanggalController;
use App\Http\Controllers\Admin\JadwalProduksiController;
use App\Http\Controllers\Admin\MetodePembayaranController as AdminMetodePembayaranController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CustomSnackboxController as AdminCustomSnackboxController;

// Pemilik
use App\Http\Controllers\Pemilik\DashboardController as PemilikDashboardController;
use App\Http\Controllers\Pemilik\LaporanController;
use App\Http\Controllers\Pemilik\JadwalProduksiController as PemilikJadwalProduksiController;


/*
|--------------------------------------------------------------------------
| Halaman Awal
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


/*
|--------------------------------------------------------------------------
| Route Redirect setelah Login/Register
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->role === 'pelanggan') {
            return redirect()->route('pelanggan.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pemilik') {
            return redirect()->route('pemilik.dashboard');
        }
        
        return redirect('/');
    })->name('dashboard');

    Route::get('/test', function () {
        return 'Test OK';
    });
});


/*
|--------------------------------------------------------------------------
| ROUTE PELANGGAN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');

    // Produk
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');

    // Custom Snackbox
    Route::get('/custom-snackbox/create', [CustomSnackboxController::class, 'create'])->name('custom-snackbox.create');
    Route::post('/custom-snackbox', [CustomSnackboxController::class, 'store'])->name('custom-snackbox.store');
    Route::delete('/custom-snackbox/{id}', [CustomSnackboxController::class, 'destroy'])->name('custom-snackbox.destroy');
    
    // 🔥 ROUTE EDIT DAN UPDATE CUSTOM SNACKBOX (UNTUK PELANGGAN)
    Route::get('/custom-snackbox/{id}/edit', [CustomSnackboxController::class, 'edit'])->name('custom-snackbox.edit');
    Route::put('/custom-snackbox/{id}', [CustomSnackboxController::class, 'update'])->name('custom-snackbox.update');

    // Keranjang
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/tambah-produk', [KeranjangController::class, 'tambahProduk'])->name('keranjang.tambah-produk');
    Route::put('/keranjang/{id}', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/kosongkan', [KeranjangController::class, 'kosongkan'])->name('keranjang.kosongkan');
    Route::delete('/keranjang/{id}', [KeranjangController::class, 'destroy'])
        ->where('id', '[0-9]+')
        ->name('keranjang.destroy');
    Route::get('/test-json', function () {
        return response()->json(['status' => 'ok']);
    });
    
    Route::get('/cek-tanggal-nonaktif', function (Illuminate\Http\Request $request) {
        $tanggal = $request->get('tanggal');
        $data = Illuminate\Support\Facades\DB::table('tanggal_nonaktif')
            ->where('tanggal', $tanggal)
            ->where('status', 'nonaktif')
            ->select('keterangan')
            ->first();
        
        return response()->json([
            'nonaktif' => !is_null($data),
            'keterangan' => $data->keterangan ?? null
        ]);
    })->name('cek-tanggal-nonaktif');

    // Pesanan
    Route::get('/pesanan', [PelangganPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{id}', [PelangganPesananController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/checkout', [PelangganPesananController::class, 'checkout'])->name('pesanan.checkout');
    Route::get('/pesanan/{id}/edit', [PelangganPesananController::class, 'edit'])->name('pesanan.edit');
    
    Route::put('/pesanan/{id}', [PelangganPesananController::class, 'update'])->name('pesanan.update');
    Route::post('/pesanan/{id}/batalkan', [PelangganPesananController::class, 'batalkan'])->name('pesanan.batalkan');

    // Pembayaran (upload bukti)
    Route::post('/pembayaran/{id}/upload', [PelangganMetodePembayaranController::class, 'upload'])->name('pembayaran.upload');
});


/*
|--------------------------------------------------------------------------
| ROUTE ADMIN 
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Produk
    Route::resource('/produk', AdminProdukController::class);

    // ============================================================
    // KELOLA AKUN (UNTUK SEMUA ROLE: ADMIN, PELANGGAN, PEMILIK)
    // ============================================================
    Route::resource('/kelola-akun', App\Http\Controllers\Admin\KelolaAkunController::class)
        ->except(['show']);
    Route::post('/kelola-akun/{id}/reset-password', [App\Http\Controllers\Admin\KelolaAkunController::class, 'resetPassword'])
        ->name('kelola-akun.reset-password');

    // ============================================================
    // METODE PEMBAYARAN (CRUD)
    // ============================================================
    Route::resource('/metode-pembayaran', AdminMetodePembayaranController::class);
    Route::post('/metode-pembayaran/{id}/toggle', [AdminMetodePembayaranController::class, 'toggleStatus'])
        ->name('metode-pembayaran.toggle');
    
    // ============================================================
    // CUSTOM SNACKBOX (ADMIN EDIT)
    // ============================================================
    Route::get('/custom-snackbox/{id}/edit', [AdminCustomSnackboxController::class, 'edit'])
        ->name('custom-snackbox.edit');
    Route::put('/custom-snackbox/{id}', [AdminCustomSnackboxController::class, 'update'])
        ->name('custom-snackbox.update');

    // ============================================================
    // PESANAN
    // ============================================================
    Route::get('/pesanan/create-manual', [AdminPesananController::class, 'createManual'])->name('pesanan.create-manual');
    Route::post('/pesanan/store-manual', [AdminPesananController::class, 'storeManual'])->name('pesanan.store-manual');
    
    Route::get('/pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{id}', [AdminPesananController::class, 'show'])->name('pesanan.show');
    Route::get('/pesanan/{id}/edit', [AdminPesananController::class, 'edit'])->name('pesanan.edit');
    Route::put('/pesanan/{id}', [AdminPesananController::class, 'update'])->name('pesanan.update');
    Route::post('/pesanan/{id}/verifikasi', [AdminPesananController::class, 'verifikasi'])->name('pesanan.verifikasi');
    Route::put('/pesanan/{id}/status', [AdminPesananController::class, 'updateStatus'])->name('pesanan.update-status');
    
    // Pembayaran (konfirmasi admin)
    Route::post('/pembayaran/{id}/konfirmasi', [App\Http\Controllers\Admin\PembayaranController::class, 'konfirmasi'])->name('pembayaran.konfirmasi');
    
    // ============================================================
    // TANGGAL NONAKTIF
    // ============================================================
    Route::get('/tanggal', [TanggalController::class, 'index'])->name('tanggal.index');
    Route::post('/tanggal', [TanggalController::class, 'store'])->name('tanggal.store');
    Route::post('/tanggal/{id}/aktifkan', [TanggalController::class, 'aktifkan'])->name('tanggal.aktifkan');
    Route::post('/tanggal/{id}/nonaktifkan', [TanggalController::class, 'nonaktifkan'])->name('tanggal.nonaktifkan');
    Route::delete('/tanggal/{id}', [TanggalController::class, 'destroy'])->name('tanggal.destroy');
    
    // ============================================================
    // JADWAL PRODUKSI (DIPERBAIKI)
    // ============================================================
    Route::get('/jadwal-produksi', [JadwalProduksiController::class, 'index'])->name('jadwal-produksi.index');
    Route::post('/jadwal-produksi/generate', [JadwalProduksiController::class, 'generate'])->name('jadwal-produksi.generate');
    Route::put('/jadwal-produksi/{id}/status', [JadwalProduksiController::class, 'updateStatus'])->name('jadwal-produksi.update-status');
    Route::put('/jadwal-produksi/{id}/waktu', [JadwalProduksiController::class, 'updateWaktu'])->name('jadwal-produksi.update-waktu');
    Route::delete('/jadwal-produksi/{id}', [JadwalProduksiController::class, 'destroy'])->name('jadwal-produksi.destroy');
    Route::post('/jadwal-produksi/reset', [JadwalProduksiController::class, 'reset'])->name('jadwal-produksi.reset');
});


/*
|--------------------------------------------------------------------------
| ROUTE PEMILIK (OWNER)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [PemilikDashboardController::class, 'index'])->name('dashboard');
    
    // Laporan Penjualan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    
    // Jadwal Produksi
    Route::get('/jadwal-produksi', [PemilikJadwalProduksiController::class, 'index'])->name('jadwal-produksi.index');
});


/*
|--------------------------------------------------------------------------
| Route Auth Bawaan Breeze (Login, Register, Logout, dll)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';