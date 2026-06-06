<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\JadwalProduksi;
use Illuminate\Http\Request;

class JadwalProduksiController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        
        $jadwal = JadwalProduksi::with('pesanan.user')
            ->whereDate('tanggal_produksi', $tanggal)
            ->orderBy('jam_mulai')
            ->get();
        
        // Jadwal untuk 7 hari ke depan
        $jadwalMendatang = JadwalProduksi::with('pesanan.user')
            ->whereDate('tanggal_produksi', '>=', date('Y-m-d'))
            ->orderBy('tanggal_produksi')
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('tanggal_produksi');
        
        return view('pemilik.jadwal-produksi', compact('jadwal', 'tanggal', 'jadwalMendatang'));
    }
}