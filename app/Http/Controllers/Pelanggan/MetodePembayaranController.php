<?php
// app/Http/Controllers/Pelanggan/MetodePembayaranController.php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MetodePembayaranController extends Controller
{
    // Upload bukti pembayaran
    public function upload(Request $request, $id)
    {
        $request->validate([
            'id_metode_pembayaran' => 'required|exists:metode_pembayaran,id',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $pesanan = Pesanan::where('user_id', Auth::id())->findOrFail($id);
        
        if (!$pesanan->bisaUploadBukti()) {
            return back()->with('error', 'Pesanan tidak bisa upload bukti pembayaran!');
        }
        
        // 🔥 PERBAIKAN: Upload file ke disk public 🔥
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Simpan ke storage/app/public/pembayaran/
            $path = $file->storeAs('pembayaran', $filename, 'public');
            
            // Path yang benar untuk diakses via URL
            $pesanan->bukti_pembayaran = 'storage/' . $path;
        }
        
        $pesanan->id_metode_pembayaran = $request->id_metode_pembayaran;
        $pesanan->tanggal_bayar = now();
        $pesanan->status_pembayaran = 'menunggu_konfirmasi';
        $pesanan->save();
        
        return redirect()->route('pelanggan.pesanan.show', $pesanan->id)
            ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu konfirmasi admin.');
    }
}