<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use App\Models\JadwalProduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik penjualan hari ini
        $hariIni = date('Y-m-d');
        $penjualanHariIni = Pesanan::whereDate('created_at', $hariIni)
            ->where('status_pembayaran', 'lunas')
            ->sum('total_harga');
        
        // Statistik penjualan bulan ini
        $bulanIni = date('Y-m');
        $penjualanBulanIni = Pesanan::where('created_at', 'like', $bulanIni . '%')
            ->where('status_pembayaran', 'lunas')
            ->sum('total_harga');
        
        // Statistik pesanan
        $totalPesanan = Pesanan::count();
        $pesananSelesai = Pesanan::where('status', 'selesai')->count();
        $pesananDiproses = Pesanan::where('status', 'diproses')->count();
        $pesananMenunggu = Pesanan::where('status', 'menunggu_pembayaran')->count();
        
        // Total produk & pelanggan
        $totalProduk = Produk::count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        
        // 5 pesanan terbaru
        $pesananTerbaru = Pesanan::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Jadwal produksi hari ini
        $jadwalHariIni = JadwalProduksi::with('pesanan.user')
            ->whereDate('tanggal_produksi', $hariIni)
            ->orderBy('jam_mulai')
            ->get();
        
        return view('pemilik.dashboard', compact(
            'penjualanHariIni',
            'penjualanBulanIni',
            'totalPesanan',
            'pesananSelesai',
            'pesananDiproses',
            'pesananMenunggu',
            'totalProduk',
            'totalPelanggan',
            'pesananTerbaru',
            'jadwalHariIni'
        ));
    }
}