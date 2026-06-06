<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\KeranjangDetail;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PesananController extends Controller
{
    // Riwayat pesanan
    public function index()
    {
        $pesanan = Pesanan::with(['metodePembayaran', 'detailPesanan'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('pelanggan.pesanan.index', compact('pesanan'));
    }
    
    // Detail pesanan
    public function show($id)
    {
        $pesanan = Pesanan::with([
            'metodePembayaran',
            'detailPesanan.produk',
            'detailPesanan.customSnackbox'
        ])->where('user_id', Auth::id())->findOrFail($id);
        
        $metodePembayaran = MetodePembayaran::where('status_aktif', true)->get();
        
        return view('pelanggan.pesanan.show', compact('pesanan', 'metodePembayaran'));
    }
    
    // Halaman edit pesanan
    public function edit($id)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk.kategori', 'detailPesanan.customSnackbox'])
            ->where('user_id', Auth::id())
            ->where('status', 'menunggu_pembayaran')
            ->where('status_pembayaran', 'belum_bayar')
            ->where('expired_at', '>', now())
            ->findOrFail($id);
        
        $metodePembayaran = MetodePembayaran::where('status_aktif', true)->get();
        
        // Kirim data minimal order ke view
        $minimalOrderData = [];
        foreach ($pesanan->detailPesanan as $detail) {
            if ($detail->custom_snackbox_id) {
                $minimalOrderData[$detail->nama_item] = 35;
            } elseif ($detail->produk && $detail->produk->kategori && $detail->produk->kategori->nama_kategori == 'Paketan') {
                $minimalOrderData[$detail->nama_item] = $detail->produk->min_order ?? 1;
            } else {
                $minimalOrderData[$detail->nama_item] = 50; // Jajanan Basah
            }
        }
        
        return view('pelanggan.pesanan.edit', compact('pesanan', 'metodePembayaran', 'minimalOrderData'));
    }
    
    // Proses update pesanan (termasuk update jumlah produk)
    public function update(Request $request, $id)
    {
        $pesanan = Pesanan::where('user_id', Auth::id())
            ->where('status', 'menunggu_pembayaran')
            ->where('status_pembayaran', 'belum_bayar')
            ->where('expired_at', '>', now())
            ->findOrFail($id);
        
        $request->validate([
            'tanggal_pengambilan' => 'required|date|after_or_equal:' . now()->addDays(2)->format('Y-m-d\TH:i'),
            'alamat_pengiriman' => 'required|string',
            'catatan_pesanan' => 'nullable|string',
            'detail_pesanan' => 'required|array|min:1',
            'detail_pesanan.*.nama_item' => 'required|string',
            'detail_pesanan.*.jumlah' => 'required|integer|min:1',
            'detail_pesanan.*.harga_satuan' => 'required|integer|min:0',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update data utama pesanan
            $pesanan->update([
                'tanggal_pengambilan' => $request->tanggal_pengambilan,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'catatan_pesanan' => $request->catatan_pesanan,
            ]);
            
            // Ambil detail pesanan lama dengan relasi produk (untuk minimal order)
            $detailLama = DetailPesanan::with('produk.kategori')
                ->where('pesanan_id', $pesanan->id)
                ->get()
                ->keyBy('nama_item');
            
            $detailItems = $request->detail_pesanan;
            $totalHarga = 0;
            
            foreach ($detailItems as $item) {
                $namaItem = $item['nama_item'];
                $detailLamaItem = $detailLama[$namaItem] ?? null;
                
                // 🔥 TOLAK ITEM BARU (tidak boleh menambah item)
                if (!$detailLamaItem) {
                    DB::rollBack();
                    return back()->with('error', "Anda tidak diperbolehkan menambah item baru. Item '{$namaItem}' tidak dikenali.")
                        ->withInput();
                }
                
                // Tentukan minimal order berdasarkan jenis item
                $minOrder = 50;
                $satuan = 'pcs';
                
                if ($detailLamaItem->custom_snackbox_id) {
                    $minOrder = 35;
                    $satuan = 'box';
                } elseif ($detailLamaItem->produk && $detailLamaItem->produk->kategori && $detailLamaItem->produk->kategori->nama_kategori == 'Paketan') {
                    $minOrder = $detailLamaItem->produk->min_order ?? 1;
                    $satuan = 'paket';
                }
                
                // Validasi jumlah tidak boleh kurang dari minimal order
                if ($item['jumlah'] < $minOrder) {
                    DB::rollBack();
                    return back()->with('error', "Item \"{$namaItem}\" minimal pesanan {$minOrder} {$satuan}!")
                        ->withInput();
                }
                
                // Update jumlah dan subtotal (harga satuan tetap pakai yang lama)
                $subtotal = (int) $item['jumlah'] * (int) $detailLamaItem->harga_satuan;
                $detailLamaItem->update([
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $subtotal,
                ]);
                $totalHarga += $subtotal;
            }
            
            // Update total harga pesanan
            $pesanan->total_harga = $totalHarga;
            $pesanan->save();
            
            DB::commit();
            
            return redirect()->route('pelanggan.pesanan.show', $pesanan->id)
                ->with('success', 'Pesanan berhasil diperbarui!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update pesanan error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui pesanan: ' . $e->getMessage());
        }
    }
    
    // Checkout
    public function checkout(Request $request)
    {
        \Log::info('Checkout request:', $request->all());
        
        $request->validate([
            'tanggal_pengambilan' => 'required|date|after_or_equal:' . now()->addDays(2)->format('Y-m-d\TH:i'),
            'alamat_pengiriman' => 'required|string',
            'catatan_pesanan' => 'nullable|string'
        ]);

        // VALIDASI CEK TANGGAL NONAKTIF
        $tanggal = date('Y-m-d', strtotime($request->tanggal_pengambilan));
        
        $tanggalNonaktif = DB::table('tanggal_nonaktif')
            ->where('tanggal', $tanggal)
            ->where('status', 'nonaktif')
            ->exists();
    
        if ($tanggalNonaktif) {
            return back()->with('error', 'Maaf, tanggal pengambilan ' . date('d M Y', strtotime($tanggal)) . ' sedang tidak tersedia. Silakan pilih tanggal lain.')
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            
            $keranjang = KeranjangDetail::with(['produk.kategori', 'customSnackbox'])
                ->where('user_id', $user->id)
                ->get();
            
            if ($keranjang->isEmpty()) {
                return back()->with('error', 'Keranjang belanja kosong!');
            }
            
            // VALIDASI MINIMAL ORDER
            foreach ($keranjang as $item) {
                $minOrder = 50;
                $satuan = 'pcs';
                
                if ($item->produk_id && $item->produk && $item->produk->kategori) {
                    $kategori = $item->produk->kategori->nama_kategori;
                    
                    if ($kategori == 'Paketan') {
                        $minOrder = $item->produk->min_order ?? 1;
                        $satuan = 'order';
                    } elseif ($kategori == 'Jajanan Basah') {
                        $minOrder = 50;
                        $satuan = 'pcs';
                    }
                } elseif ($item->custom_snackbox_id) {
                    $minOrder = 35;
                    $satuan = 'box';
                }
                
                if ($item->jumlah < $minOrder) {
                    DB::rollBack();
                    return back()->with('error', "Item \"{$item->nama_item}\" minimal pesanan {$minOrder} {$satuan}");
                }
            }
            
            $total = $keranjang->sum('subtotal');
            $nomorPesanan = 'PS-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            
            $pesanan = Pesanan::create([
                'user_id' => $user->id,
                'nomor_pesanan' => $nomorPesanan,
                'tanggal_pesanan' => now(),
                'expired_at' => now()->addHours(24),
                'tanggal_pengambilan' => $request->tanggal_pengambilan,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'total_harga' => $total,
                'catatan_pesanan' => $request->catatan_pesanan,
                'status' => 'menunggu_pembayaran',
                'status_pembayaran' => 'belum_bayar',
            ]);
            
            foreach ($keranjang as $item) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item->produk_id,
                    'custom_snackbox_id' => $item->custom_snackbox_id,
                    'nama_item' => $item->nama_item,
                    'jumlah' => $item->jumlah,
                    'harga_satuan' => $item->harga,
                    'subtotal' => $item->subtotal,
                ]);
            }
            
            KeranjangDetail::where('user_id', $user->id)->delete();
            
            DB::commit();
            
            return redirect()->route('pelanggan.pesanan.show', $pesanan->id)
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal checkout: ' . $e->getMessage());
        }
    }
    
    /**
     * BATALKAN PESANAN - DENGAN PENGEMBALIAN PRODUK KE KERANJANG
     */
    public function batalkan($id)
    {
        try {
            $pesanan = Pesanan::where('user_id', Auth::id())->where('id', $id)->first();
            
            if (!$pesanan) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Pesanan tidak ditemukan'
                ], 404);
            }
            
            Log::info('Batalkan pesanan - ID: ' . $id . ', Status: ' . $pesanan->status . ', Payment: ' . $pesanan->status_pembayaran);
            
            if ($pesanan->status != 'menunggu_pembayaran') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Pesanan tidak dapat dibatalkan karena status: ' . $pesanan->status
                ], 400);
            }
            
            if ($pesanan->status_pembayaran != 'belum_bayar') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Pesanan tidak dapat dibatalkan karena status pembayaran: ' . $pesanan->status_pembayaran
                ], 400);
            }
            
            // 🔥 Pindahkan detail pesanan ke keranjang (jika belum ada di keranjang)
            $detailPesanan = DetailPesanan::where('pesanan_id', $pesanan->id)->get();
            
            foreach ($detailPesanan as $detail) {
                // Cek apakah item sudah ada di keranjang user (berdasarkan produk_id dan custom_snackbox_id)
                $keranjang = KeranjangDetail::where('user_id', Auth::id())
                    ->where('produk_id', $detail->produk_id)
                    ->where('custom_snackbox_id', $detail->custom_snackbox_id)
                    ->first();
                
                if ($keranjang) {
                    // Jika sudah ada, update jumlah dan subtotal
                    $keranjang->jumlah += $detail->jumlah;
                    $keranjang->subtotal = $keranjang->jumlah * $keranjang->harga;
                    $keranjang->save();
                } else {
                    // Jika belum ada, buat baru
                    KeranjangDetail::create([
                        'user_id' => Auth::id(),
                        'produk_id' => $detail->produk_id,
                        'custom_snackbox_id' => $detail->custom_snackbox_id,
                        'nama_item' => $detail->nama_item,
                        'jumlah' => $detail->jumlah,
                        'harga' => $detail->harga_satuan,
                        'subtotal' => $detail->subtotal,
                    ]);
                }
            }
            
            // Update status pesanan
            $pesanan->status = 'dibatalkan';
            $pesanan->save();
            
            Log::info('Pesanan berhasil dibatalkan - ID: ' . $id . ' - Produk dikembalikan ke keranjang');
            
            return response()->json([
                'success' => true, 
                'message' => 'Pesanan dibatalkan dan produk dikembalikan ke keranjang'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error batalkan pesanan: ' . $e->getMessage());
            
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}