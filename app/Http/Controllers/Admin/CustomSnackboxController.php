<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomSnackbox;
use App\Models\CustomSnackboxDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomSnackboxController extends Controller
{
    /**
     * Form edit custom snackbox
     */
    public function edit($id)
    {
        $customSnackbox = CustomSnackbox::with('detail.produk')->findOrFail($id);
        
        // Ambil semua produk dengan kategori Jajanan Basah (kategori_id = 1)
        $daftarJajanan = Produk::with('kategori')
            ->where('kategori_id', 1)
            ->get();
        
        // Data ukuran snackbox
        $ukuran = [
            'A' => ['nama' => 'Ukuran A (3 Item)', 'jumlah_item' => 3, 'harga' => 1500],
            'B' => ['nama' => 'Ukuran B (4 Item)', 'jumlah_item' => 4, 'harga' => 1500],
            'C' => ['nama' => 'Ukuran C (5 Item)', 'jumlah_item' => 5, 'harga' => 1500],
            'D' => ['nama' => 'Ukuran D (5 Item)', 'jumlah_item' => 5, 'harga' => 2000],
            'E' => ['nama' => 'Ukuran E (6 Item)', 'jumlah_item' => 6, 'harga' => 2500],
            'F' => ['nama' => 'Ukuran F (8 Item)', 'jumlah_item' => 8, 'harga' => 3000],
        ];
        
        // ID jajanan yang sudah dipilih
        $selectedJajananIds = $customSnackbox->detail->pluck('produk_id')->toArray();
        
        return view('admin.pesanan.snackboxedit', compact(
            'customSnackbox', 'daftarJajanan', 'ukuran', 'selectedJajananIds'
        ));
    }
    
    /**
     * Update custom snackbox - RESPONSE JSON
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'kode_ukuran' => 'required|in:A,B,C,D,E,F',
                'jumlah_box' => 'required|integer|min:1',
                'jajanan' => 'required|array|min:1',
            ]);
            
            DB::beginTransaction();
            
            $customSnackbox = CustomSnackbox::findOrFail($id);
            
            $ukuranData = [
                'A' => ['jumlah_item' => 3, 'harga' => 1500],
                'B' => ['jumlah_item' => 4, 'harga' => 1500],
                'C' => ['jumlah_item' => 5, 'harga' => 1500],
                'D' => ['jumlah_item' => 5, 'harga' => 2000],
                'E' => ['jumlah_item' => 6, 'harga' => 2500],
                'F' => ['jumlah_item' => 8, 'harga' => 3000],
            ];
            
            $ukuran = $ukuranData[$request->kode_ukuran];
            
            // Hitung total item yang dipilih
            $selectedJajanan = $request->jajanan;
            $totalItem = count($selectedJajanan);
            
            // Validasi kapasitas box
            if ($totalItem > $ukuran['jumlah_item']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah item melebihi kapasitas box (maksimal ' . $ukuran['jumlah_item'] . ' item)!'
                ], 400);
            }
            
            // Hitung total harga jajanan
            $totalHargaJajanan = 0;
            foreach ($selectedJajanan as $produkId) {
                $produk = Produk::find($produkId);
                if ($produk) {
                    $totalHargaJajanan += $produk->harga;
                }
            }
            
            // Hitung harga
            $hargaPerBox = $ukuran['harga'] + $totalHargaJajanan;
            $hargaTotal = $hargaPerBox * $request->jumlah_box;
            
            // Update custom snackbox
            $customSnackbox->update([
                'kode_ukuran' => $request->kode_ukuran,
                'jumlah_item' => $ukuran['jumlah_item'],
                'total_item' => $totalItem,
                'jumlah_box' => $request->jumlah_box,
                'harga_per_box' => $hargaPerBox,
                'harga_total' => $hargaTotal,
            ]);
            
            // Hapus detail lama
            $customSnackbox->detail()->delete();
            
            // Simpan detail baru
            foreach ($selectedJajanan as $produkId) {
                $produk = Produk::find($produkId);
                CustomSnackboxDetail::create([
                    'custom_snackbox_id' => $customSnackbox->id,
                    'produk_id' => $produkId,
                    'jumlah' => 1,
                    'subtotal' => $produk->harga,
                ]);
            }
            
            // Update detail pesanan yang terkait (harga)
            $detailPesanan = $customSnackbox->detailPesanan()->first();
            $redirectUrl = route('admin.pesanan.edit', $detailPesanan->pesanan_id);
            
            if ($detailPesanan) {
                $detailPesanan->update([
                    'nama_item' => 'Custom Snackbox (' . $customSnackbox->nama_ukuran . ')',
                    'jumlah' => $request->jumlah_box,
                    'harga_satuan' => $hargaPerBox,
                    'subtotal' => $hargaTotal,
                ]);
                
                // Update total pesanan
                $pesanan = $detailPesanan->pesanan;
                $newTotal = $pesanan->detailPesanan->sum('subtotal');
                $pesanan->update(['total_harga' => $newTotal]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Custom snackbox berhasil diperbarui!',
                'redirect' => $redirectUrl
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors())
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update custom snackbox error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui: ' . $e->getMessage()
            ], 500);
        }
    }
}