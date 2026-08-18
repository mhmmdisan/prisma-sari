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
    // 🔥 KONSTANTA MINIMAL HARI UNTUK PEMESANAN
    const MIN_DAYS = 5; // Minimal H+5
    
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
    
    // Checkout
    public function checkout(Request $request)
    {
        // 🔥 VALIDASI TANGGAL (H+5) - MENGGUNAKAN CARBON
        $minDate = now()->addDays(self::MIN_DAYS)->startOfDay();
        
        // Validasi format tanggal
        try {
            $tanggalInput = \Carbon\Carbon::parse($request->tanggal_pengambilan);
        } catch (\Exception $e) {
            return back()->with('error', 'Format tanggal tidak valid!')
                ->withInput();
        }
        
        // Cek apakah tanggal >= H+5
        if ($tanggalInput->lt($minDate)) {
            return back()->with('error', 'Tanggal pengambilan minimal H+5 dari sekarang!')
                ->withInput();
        }

        // 🔥 VALIDASI CEK TANGGAL NONAKTIF
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
                DB::rollBack();
                return back()->with('error', 'Keranjang belanja kosong!');
            }
            
            // 🔥 VALIDASI MINIMAL ORDER
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
            Log::error('Checkout error: ' . $e->getMessage());
            return back()->with('error', 'Gagal checkout: ' . $e->getMessage());
        }
    }
    
    /**
     * BATALKAN PESANAN - DENGAN PENGEMBALIAN PRODUK KE KERANJANG
     * Hanya bisa dibatalkan jika status_pembayaran = 'belum_bayar'
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
            
            // 🔥 CEK STATUS PESANAN
            if ($pesanan->status != 'menunggu_pembayaran') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Pesanan tidak dapat dibatalkan karena status: ' . $pesanan->status
                ], 400);
            }
            
            // 🔥 CEK STATUS PEMBAYARAN - HANYA BISA BATALKAN JIKA BELUM BAYAR
            if ($pesanan->status_pembayaran != 'belum_bayar') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Pesanan tidak dapat dibatalkan karena sudah upload bukti pembayaran. Silakan hubungi admin.'
                ], 400);
            }
            
            // 🔥 CEK APAKAH PESANAN SUDAH EXPIRED
            if ($pesanan->expired_at && $pesanan->expired_at < now()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Pesanan sudah melewati batas waktu pembayaran. Silakan buat pesanan baru.'
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