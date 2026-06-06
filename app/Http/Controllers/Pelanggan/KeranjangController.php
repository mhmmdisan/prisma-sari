<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\KeranjangDetail;
use App\Models\CustomSnackbox;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KeranjangController extends Controller
{
    // Lihat keranjang
    public function index()
    {
        try {
            $user = Auth::user();
            
            $keranjang = KeranjangDetail::with(['produk', 'customSnackbox'])
                ->where('user_id', $user->id)
                ->get();
            
            $total = $keranjang->sum('subtotal');

            $tanggalNonaktifList = DB::table('tanggal_nonaktif')
                ->where('status', 'nonaktif')
                ->where('tanggal', '>=', now()->subDays(1)->format('Y-m-d'))
                ->pluck('tanggal')
                ->toArray();   
        
            \Log::info('Tanggal Nonaktif:', $tanggalNonaktifList);
             
            return view('pelanggan.keranjang', compact('keranjang', 'total', 'tanggalNonaktifList'));
            
        } catch (\Exception $e) {
            Log::error('Error index keranjang: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * TAMBAH PRODUK BIASA KE KERANJANG
     * Dengan validasi minimal pesanan berdasarkan kategori
     */
    public function tambahProduk(Request $request)
    {
        try {
            if (!$request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Request harus AJAX'
                ], 400);
            }
            
            $request->validate([
                'produk_id' => 'required|exists:produk,id',
                'jumlah' => 'required|integer|min:1',
            ]);
            
            Log::info('Tambah produk ke keranjang', $request->all());
            
            $produk = Produk::with('kategori')->findOrFail($request->produk_id);
            $kategori = $produk->kategori->nama_kategori ?? 'Unknown';
            $user = Auth::user();
            
            $minOrder = 50;
            $satuan = 'pcs';
            
            if ($kategori == 'Paketan') {
                $minOrder = $produk->min_order ?? 1; 
                $satuan = 'order';
            } elseif ($kategori == 'Jajanan Basah') {
                $minOrder = 50;
                $satuan = 'pcs';
            }
            
            if ($request->jumlah < $minOrder) {
                $errorMessage = "Minimal pesanan {$minOrder} {$satuan} untuk {$produk->nama_produk}";
                return response()->json([
                    'success' => false, 
                    'message' => $errorMessage
                ], 422);
            }
            
            $keranjang = KeranjangDetail::where('user_id', $user->id)
                ->where('produk_id', $request->produk_id)
                ->whereNull('custom_snackbox_id')
                ->first();
            
            if ($keranjang) {
                $keranjang->jumlah += $request->jumlah;
                $keranjang->subtotal = $keranjang->jumlah * $produk->harga;
                $keranjang->save();
                $message = 'Jumlah produk diperbarui di keranjang!';
            } else {
                KeranjangDetail::create([
                    'user_id' => $user->id,
                    'produk_id' => $request->produk_id,
                    'jumlah' => $request->jumlah,
                    'harga' => $produk->harga,
                    'subtotal' => $produk->harga * $request->jumlah,
                ]);
                $message = 'Produk ditambahkan ke keranjang!';
            }
            
            return response()->json([
                'success' => true, 
                'message' => $message
            ]);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessage = 'Validasi gagal: ';
            if ($e->errors()) {
                $errorMessage .= implode(', ', array_merge(...array_values($e->errors())));
            }
            return response()->json([
                'success' => false, 
                'message' => $errorMessage
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error tambahProduk: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Helper: Mendapatkan minimal order untuk item keranjang
     */
    private function getMinOrder($keranjangItem)
    {
        // Default untuk Jajanan Basah
        $minOrder = 50;
        
        if ($keranjangItem->produk_id && $keranjangItem->produk && $keranjangItem->produk->kategori) {
            $kategori = $keranjangItem->produk->kategori->nama_kategori;
            if ($kategori == 'Hantaran') {
                $minOrder = 1;
            } elseif ($kategori == 'Paketan') {
                $minOrder = $keranjangItem->produk->min_order ?? 1;
            } elseif ($kategori == 'Jajanan Basah') {
                $minOrder = 50;
            }
        } elseif ($keranjangItem->custom_snackbox_id) {
            $minOrder = 35; // Custom Snackbox minimal 35 box
        }
        
        return $minOrder;
    }
    
    /**
     * Update jumlah item di keranjang
     * Sekarang dengan validasi minimal order
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'jumlah' => 'required|integer|min:1',
            ]);
            
            // Load relasi produk dan kategori untuk mendapatkan min order
            $keranjang = KeranjangDetail::with(['produk.kategori', 'customSnackbox'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);
            
            $jumlahBaru = $request->jumlah;
            $minOrder = $this->getMinOrder($keranjang);
            
            // Validasi minimal order
            if ($jumlahBaru < $minOrder) {
                return response()->json([
                    'success' => false, 
                    'message' => "Minimal pesanan untuk item \"{$keranjang->nama_item}\" adalah {$minOrder}."
                ], 400);
            }
            
            $keranjang->jumlah = $jumlahBaru;
            $keranjang->subtotal = $keranjang->harga * $jumlahBaru;
            $keranjang->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Jumlah item berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error update keranjang: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Hapus item dari keranjang
    public function destroy($id)
    {
        try {
            $keranjang = KeranjangDetail::where('user_id', Auth::id())
                ->where('id', $id)
                ->first();
            
            if (!$keranjang) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Item tidak ditemukan di keranjang Anda'
                ], 404);
            }
            
            $namaItem = $keranjang->nama_item;
            
            if ($keranjang->custom_snackbox_id) {
                $customSnackbox = CustomSnackbox::find($keranjang->custom_snackbox_id);
                if ($customSnackbox) {
                    $customSnackbox->detail()->delete();
                    $customSnackbox->delete();
                }
            }
            
            $keranjang->delete();
            
            return response()->json([
                'success' => true,
                'message' => "{$namaItem} telah dihapus dari keranjang"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error hapus item keranjang: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Kosongkan keranjang
    public function kosongkan()
    {
        try {
            $keranjang = KeranjangDetail::where('user_id', Auth::id())->get();
            
            if ($keranjang->isEmpty()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Keranjang sudah kosong'
                ]);
            }
            
            foreach ($keranjang as $item) {
                if ($item->custom_snackbox_id) {
                    $customSnackbox = CustomSnackbox::find($item->custom_snackbox_id);
                    if ($customSnackbox) {
                        $customSnackbox->detail()->delete();
                        $customSnackbox->delete();
                    }
                }
            }
            
            KeranjangDetail::where('user_id', Auth::id())->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error kosongkan keranjang: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}