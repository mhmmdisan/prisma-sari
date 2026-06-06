<?php

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
        
        // Ambil produk terbaru (Jajanan Basah dan Paketan)
        $produkTerbaru = Produk::with('kategori')
            ->where('is_snackbox_only', 0)
            ->whereHas('kategori', function($query) {
                $query->whereIn('nama_kategori', ['Jajanan Basah', 'Paketan']);
            })
        ->orderBy('created_at', 'desc')
        ->take(4)
        ->get();

        // Ambil produk snackbox (Paketan)
        $produkSnackbox = Produk::with('kategori')
            ->whereHas('kategori', function($query) {
                $query->where('nama_kategori', 'Paketan');
            })
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