<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MetodePembayaranController extends Controller
{
    /**
     * Upload bukti pembayaran (pertama kali)
     */
    public function upload(Request $request, $id)
    {
        $pesanan = Pesanan::where('user_id', Auth::id())->findOrFail($id);
        
        // Validasi: hanya bisa upload jika status belum bayar dan belum expired
        if ($pesanan->status_pembayaran != 'belum_bayar' || $pesanan->status != 'menunggu_pembayaran') {
            return back()->with('error', 'Status pesanan tidak valid untuk upload bukti.');
        }
        
        if ($pesanan->expired_at < now()) {
            return back()->with('error', 'Waktu pembayaran sudah habis, tidak dapat upload bukti.');
        }
        
        $request->validate([
            'id_metode_pembayaran' => 'required|exists:metode_pembayaran,id',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        try {
            // Simpan file ke storage/app/public/pembayaran/
            $path = $request->file('bukti_pembayaran')->store('pembayaran', 'public');
            
            // Log untuk debugging
            Log::info('Upload bukti - Pesanan ID: ' . $pesanan->id . ', Path: ' . $path);
            Log::info('Full path: ' . storage_path('app/public/' . $path));
            
            // Cek apakah file benar-benar tersimpan
            if (!Storage::disk('public')->exists($path)) {
                Log::error('File gagal tersimpan: ' . $path);
                return back()->with('error', 'Gagal menyimpan file bukti. Silakan coba lagi.');
            }
            
            // Update pesanan
            $pesanan->id_metode_pembayaran = $request->id_metode_pembayaran;
            $pesanan->bukti_pembayaran = $path; // simpan path lengkap
            $pesanan->status_pembayaran = 'menunggu_konfirmasi';
            $pesanan->save();
            
            return redirect()->route('pelanggan.pesanan.show', $pesanan->id)
                ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu konfirmasi admin.');
                
        } catch (\Exception $e) {
            Log::error('Error upload bukti: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Update bukti pembayaran (edit)
     */
    public function updateBukti(Request $request, $id)
    {
        $pesanan = Pesanan::where('user_id', Auth::id())->findOrFail($id);
        
        // Hanya boleh mengedit jika status menunggu konfirmasi dan belum expired
        if ($pesanan->status_pembayaran != 'menunggu_konfirmasi') {
            return back()->with('error', 'Status pembayaran tidak valid untuk diedit.');
        }
        
        if ($pesanan->expired_at < now()) {
            return back()->with('error', 'Waktu pembayaran sudah habis, tidak dapat mengedit bukti.');
        }
        
        $request->validate([
            'id_metode_pembayaran' => 'required|exists:metode_pembayaran,id',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        try {
            // Hapus bukti lama jika ada
            if ($pesanan->bukti_pembayaran && Storage::disk('public')->exists($pesanan->bukti_pembayaran)) {
                Storage::disk('public')->delete($pesanan->bukti_pembayaran);
                Log::info('File lama dihapus: ' . $pesanan->bukti_pembayaran);
            }
            
            // Simpan bukti baru ke storage/app/public/pembayaran/
            $path = $request->file('bukti_pembayaran')->store('pembayaran', 'public');
            
            // Log untuk debugging
            Log::info('Update bukti - Pesanan ID: ' . $pesanan->id . ', Path: ' . $path);
            Log::info('Full path: ' . storage_path('app/public/' . $path));
            
            // Cek apakah file benar-benar tersimpan
            if (!Storage::disk('public')->exists($path)) {
                Log::error('File gagal tersimpan: ' . $path);
                return back()->with('error', 'Gagal menyimpan file bukti. Silakan coba lagi.');
            }
            
            // Update pesanan (status tetap menunggu_konfirmasi)
            $pesanan->id_metode_pembayaran = $request->id_metode_pembayaran;
            $pesanan->bukti_pembayaran = $path;
            $pesanan->save();
            
            return redirect()->route('pelanggan.pesanan.show', $pesanan->id)
                ->with('success', 'Bukti pembayaran berhasil diperbarui! Menunggu konfirmasi admin.');
                
        } catch (\Exception $e) {
            Log::error('Error update bukti: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}