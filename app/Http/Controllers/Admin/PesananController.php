<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\User;
use App\Models\MetodePembayaran;
use App\Models\KategoriProduk;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with('user');
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $pesanan = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.pesanan.index', compact('pesanan'));
    }
    
    public function show($id)
    {
        $pesanan = Pesanan::with([
            'user', 
            'detailPesanan', 
            'detailPesanan.produk',
            'detailPesanan.customSnackbox',
            'detailPesanan.customSnackbox.detail',
            'detailPesanan.customSnackbox.detail.produk'
        ])->findOrFail($id);
        
        return view('admin.pesanan.show', compact('pesanan'));
    }
    
    /**
     * VERIFIKASI PEMBAYARAN - AJAX
     */
    public function verifikasi($id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);
            
            if ($pesanan->status_pembayaran == 'menunggu_konfirmasi') {
                $pesanan->status_pembayaran = 'lunas';
                $pesanan->status = 'diproses';
                $pesanan->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil diverifikasi! Pesanan akan dijadwalkan ke jadwal produksi.',
                    'redirect' => route('admin.pesanan.show', $pesanan->id)
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat diverifikasi'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * UPDATE STATUS PESANAN - AJAX
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:menunggu_pembayaran,diproses,terlambat,selesai,dibatalkan'
            ]);
            
            $pesanan = Pesanan::findOrFail($id);
            $pesanan->status = $request->status;
            $pesanan->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diperbarui!',
                'redirect' => route('admin.pesanan.show', $pesanan->id)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * FORM TAMBAH PESANAN MANUAL (WHATSAPP ORDER)
     */
    public function createManual()
    {
        $pelanggan = User::where('role', 'pelanggan')->orderBy('name')->get();
        $metodePembayaran = MetodePembayaran::where('status_aktif', true)->get();
        $kategoriList = KategoriProduk::all(); // Ambil semua kategori
        
        return view('admin.pesanan.create', compact('pelanggan', 'metodePembayaran', 'kategoriList'));
    }
    
    /**
     * SIMPAN PESANAN MANUAL (WHATSAPP ORDER) - AJAX
     */
    public function storeManual(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'tanggal_pengambilan' => 'required|date',
                'alamat_pengiriman' => 'required|string',
                'total_harga' => 'required|integer|min:0',
                'detail_pesanan' => 'required|array|min:1',
                'detail_pesanan.*.nama_item' => 'required|string',
                'detail_pesanan.*.kategori_id' => 'nullable|exists:kategori_produk,id', 
                'detail_pesanan.*.jumlah' => 'required|integer|min:1',
                'detail_pesanan.*.harga_satuan' => 'required|integer|min:0',
                'id_metode_pembayaran' => 'required|exists:metode_pembayaran,id',
                'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            
            DB::beginTransaction();
            
            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                
                $destinationPath = public_path('storage/pembayaran');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                $file->move($destinationPath, $filename);
                $buktiPath = 'storage/pembayaran/' . $filename;
                
                Log::info('Bukti pembayaran diupload ke: ' . $buktiPath);
            }
            
            $orderNumber = 'PS-WA-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            
            $pesanan = Pesanan::create([
                'user_id' => $request->user_id,
                'nomor_pesanan' => $orderNumber,
                'tanggal_pesanan' => now(),
                'expired_at' => now()->addHours(24),
                'tanggal_pengambilan' => $request->tanggal_pengambilan,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'total_harga' => $request->total_harga,
                'status' => 'diproses',
                'status_pembayaran' => 'lunas',
                'id_metode_pembayaran' => $request->id_metode_pembayaran,
                'bukti_pembayaran' => $buktiPath,
                'tanggal_bayar' => now(),
                'is_whatsapp_order' => true,
            ]);
            
            foreach ($request->detail_pesanan as $item) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'nama_item' => $item['nama_item'],
                    'kategori_id' => $item['kategori_id'] ?? null,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => (int) $item['harga_satuan'],
                    'subtotal' => (int) $item['jumlah'] * (int) $item['harga_satuan'],
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan WhatsApp berhasil ditambahkan! Pesanan sudah masuk antrian produksi.',
                'redirect' => route('admin.pesanan.show', $pesanan->id)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store Manual Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * FORM EDIT PESANAN (ADMIN)
     */
    public function edit($id)
    {
        $pesanan = Pesanan::with('detailPesanan')->findOrFail($id);
        
        $totalHitung = 0;
        foreach ($pesanan->detailPesanan as $item) {
            $totalHitung += $item->jumlah * $item->harga_satuan;
        }
        
        if ($pesanan->total_harga != $totalHitung) {
            $pesanan->total_harga = $totalHitung;
            $pesanan->saveQuietly();
        }
        
        $pelanggan = User::where('role', 'pelanggan')->orderBy('name')->get();
        $metodePembayaran = MetodePembayaran::where('status_aktif', true)->get();
        
        return view('admin.pesanan.edit', compact('pesanan', 'pelanggan', 'metodePembayaran'));
    }
    
    /**
     * UPDATE PESANAN (ADMIN) - AJAX
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'tanggal_pengambilan' => 'required|date',
                'alamat_pengiriman' => 'required|string',
                'total_harga' => 'required|integer|min:0',
                'detail_pesanan' => 'required|array|min:1',
                'detail_pesanan.*.nama_item' => 'required|string',
                'detail_pesanan.*.jumlah' => 'required|integer|min:1',
                'detail_pesanan.*.harga_satuan' => 'required|integer|min:0',
                'id_metode_pembayaran' => 'required|exists:metode_pembayaran,id',
                'status' => 'required|in:menunggu_pembayaran,diproses,terlambat,selesai,dibatalkan',
                'status_pembayaran' => 'required|in:belum_bayar,menunggu_konfirmasi,lunas',
                'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            
            DB::beginTransaction();
            
            $pesanan = Pesanan::findOrFail($id);
            
            $buktiPath = $pesanan->bukti_pembayaran;
            if ($request->hasFile('bukti_pembayaran')) {
                if ($buktiPath && file_exists(public_path($buktiPath))) {
                    @unlink(public_path($buktiPath));
                }
                
                $file = $request->file('bukti_pembayaran');
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $destinationPath = public_path('storage/pembayaran');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                $file->move($destinationPath, $filename);
                $buktiPath = 'storage/pembayaran/' . $filename;
            }
            
            $pesanan->update([
                'user_id' => $request->user_id,
                'tanggal_pengambilan' => $request->tanggal_pengambilan,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'total_harga' => $request->total_harga,
                'status' => $request->status,
                'status_pembayaran' => $request->status_pembayaran,
                'id_metode_pembayaran' => $request->id_metode_pembayaran,
                'bukti_pembayaran' => $buktiPath,
            ]);
            
            DetailPesanan::where('pesanan_id', $pesanan->id)->delete();
            
            foreach ($request->detail_pesanan as $item) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'nama_item' => $item['nama_item'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => (int) $item['harga_satuan'],
                    'subtotal' => (int) $item['jumlah'] * (int) $item['harga_satuan'],
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil diperbarui!',
                'redirect' => route('admin.pesanan.show', $pesanan->id)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update pesanan error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * HAPUS PESANAN - AJAX
     */
    public function destroy($id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);
            $pesanan->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}