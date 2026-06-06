<?php
// app/Http/Controllers/Pelanggan/DashboardController.php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\KeranjangDetail;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Hitung jumlah item di keranjang
        $jumlahKeranjang = KeranjangDetail::where('user_id', $user->id)->count();
        
        // Ambil produk terbaru (4 produk)
        $produkTerbaru = Produk::with('kategori')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        
        // Ambil produk snackbox (kategori_id = 2)
        $produkSnackbox = Produk::with('kategori')
            ->where('kategori_id', 2)
            ->take(4)
            ->get();
        
        // Ambil semua kategori
        $kategori = KategoriProduk::all();
        
        return view('pelanggan.dashboard', compact(
            'user',
            'jumlahKeranjang',
            'produkTerbaru',
            'produkSnackbox',
            'kategori'
        ));
    }
}