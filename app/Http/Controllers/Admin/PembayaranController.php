<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    /**
     * Konfirmasi pembayaran pesanan
     */
    public function konfirmasi($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        // Cek apakah pesanan masih dalam status menunggu konfirmasi
        if ($pesanan->status_pembayaran !== 'menunggu_konfirmasi') {
            return redirect()->back()->with('error', 'Pesanan tidak dalam status menunggu konfirmasi');
        }
        
        // Update status pembayaran menjadi lunas
        $pesanan->status_pembayaran = 'lunas';
        $pesanan->status = 'diproses';
        $pesanan->save();
        
        return redirect()->route('admin.pesanan.show', $pesanan->id)
            ->with('success', 'Pembayaran berhasil dikonfirmasi! Pesanan akan diproses.');
    }
}