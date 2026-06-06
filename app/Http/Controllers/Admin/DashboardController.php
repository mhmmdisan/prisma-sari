<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik
        $totalPesanan = Pesanan::count();
        $pesananMenunggu = Pesanan::where('status', 'menunggu_pembayaran')->count();
        $pesananDiproses = Pesanan::where('status', 'diproses')->count();
        $pesananSelesai = Pesanan::where('status', 'selesai')->count();
        $pesananDibatalkan = Pesanan::where('status', 'dibatalkan')->count();
        
        $totalProduk = Produk::count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        
        // Pesanan terbaru
        $pesananTerbaru = Pesanan::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalPesanan',
            'pesananMenunggu',
            'pesananDiproses',
            'pesananSelesai',
            'pesananDibatalkan',
            'totalProduk',
            'totalPelanggan',
            'pesananTerbaru'
        ));
    }
}