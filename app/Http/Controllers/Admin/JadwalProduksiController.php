<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\JadwalProduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JadwalProduksiController extends Controller
{
    /**
     * Tampilkan jadwal produksi
     */
    public function index()
    {
        // Ambil jadwal produksi dengan eager loading yang LENGKAP termasuk tanggal_pengambilan
        $jadwalProduksi = JadwalProduksi::with([
            'pesanan' => function($query) {
                $query->select('id', 'nomor_pesanan', 'tanggal_pengambilan', 'status', 'total_harga');
            },
            'pesanan.detailPesanan.produk',
            'pesanan.detailPesanan.customSnackbox',
            'pesanan.detailPesanan.customSnackbox.detail',
            'pesanan.detailPesanan.customSnackbox.detail.produk'
        ])
            ->orderBy('tanggal_produksi', 'asc')
            ->orderBy('urutan', 'asc')
            ->get();
        
        // Kelompokkan berdasarkan tanggal
        $jadwalByTanggal = [];
        foreach ($jadwalProduksi as $item) {
            if (!$item->pesanan) {
                continue;
            }
            
            $tanggal = $item->tanggal_produksi->format('Y-m-d');
            if (!isset($jadwalByTanggal[$tanggal])) {
                $jadwalByTanggal[$tanggal] = [];
            }
            
            $jadwalByTanggal[$tanggal][] = (object) [
                'id' => $item->id,
                'urutan' => $item->urutan,
                'nomor_pesanan' => $item->pesanan->nomor_pesanan,
                'pesanan' => $item->pesanan,
                'jam_mulai' => $item->jam_mulai,
                'jam_selesai' => $item->jam_selesai,
                'status' => $item->status,
                'pesanan_id' => $item->pesanan_id,
            ];
        }
        
        return view('admin.jadwalproduksi', compact('jadwalByTanggal'));
    }
    
    /**
     * HITUNG BURST TIME BERDASARKAN KATEGORI PRODUK
     * (mendukung: produk biasa, custom snackbox, dan item manual WA Order)
     * 
     * Aturan:
     * - Jajanan Basah: ≤100=1jam, 101-500=3jam, >500=5jam
     * - Paketan: 
     *      Godogan = 30 menit/item (1 jam/2 tampan)
     *      Snack Mini isi 100 = 90 menit/item (3 jam/2 tampan)
     *      Snack Mini isi 150 = 90 menit/item (3 jam/2 tampan)
     *      Paket Gethuk = 90 menit/item (3 jam/2 tampan)
     *      Lainnya = 30 menit/item (1 jam/2 tampan)
     * - Custom Snackbox: 100 box = 300 menit (5 jam)
     * - Snack Box (WA Order): 100 box = 300 menit (5 jam)
     * - Hantaran: 60 menit/item
     */
    private function hitungBurstTime($pesanan)
    {
        $totalWaktu = 0;

        if (!$pesanan->relationLoaded('detailPesanan')) {
            $pesanan->load(['detailPesanan.produk.kategori', 'detailPesanan.customSnackbox', 'detailPesanan.kategori']);
        }

        foreach ($pesanan->detailPesanan as $detail) {
            // 1. Item dari produk biasa (punya produk_id)
            if ($detail->produk_id && $detail->produk && $detail->produk->kategori) {
                $kategori = $detail->produk->kategori->nama_kategori;
                $namaProduk = $detail->produk->nama_produk;
                $jumlah = $detail->jumlah;

                if ($kategori == 'Jajanan Basah') {
                    if ($jumlah <= 100) {
                        $totalWaktu += 60;          // 1 jam
                    } elseif ($jumlah <= 500) {
                        $totalWaktu += 180;         // 3 jam
                    } else {
                        $totalWaktu += 300;         // 5 jam
                    }
                } 
                elseif ($kategori == 'Paketan') {
                    // PERBAIKAN: Paketan dengan aturan 2 tampan
                    if (str_contains($namaProduk, 'Godogan')) {
                        // Godogan: 1 jam untuk 2 tampan → 30 menit per item
                        $totalWaktu += 30 * $jumlah;
                    }
                    elseif (str_contains($namaProduk, 'Snack Mini isi 100') || str_contains($namaProduk, 'Snack Mini 100 Paket')) {
                        // Snack Mini isi 100: 3 jam untuk 2 tampan → 90 menit per item
                        $totalWaktu += 90 * $jumlah;
                    }
                    elseif (str_contains($namaProduk, 'Snack Mini isi 150')) {
                        // Snack Mini isi 150: 3 jam untuk 2 tampan → 90 menit per item
                        $totalWaktu += 90 * $jumlah;
                    }
                    elseif (str_contains($namaProduk, 'Paket Gethuk')) {
                        // Paket Gethuk: 3 jam untuk 2 tampan → 90 menit per item
                        $totalWaktu += 90 * $jumlah;
                    }
                    else {
                        // Paketan lainnya: 1 jam untuk 2 tampan → 30 menit per item
                        $totalWaktu += 30 * $jumlah;
                    }
                }
                else {
                    $totalWaktu += 30 * $jumlah;
                }
            }
            // 2. Item dari custom snackbox (punya custom_snackbox_id)
            elseif ($detail->custom_snackbox_id && $detail->customSnackbox) {
                // PERBAIKAN: Custom Snackbox - 100 box = 5 jam (300 menit)
                $jumlahBox = $detail->customSnackbox->jumlah_box ?? 0;
                
                if ($jumlahBox <= 0) {
                    continue;
                }
                
                // Rumus: (jumlah_box / 100) × 300 menit
                $waktuPer100Box = 300; // 5 jam dalam menit
                $totalWaktu += ($jumlahBox / 100) * $waktuPer100Box;
            }
            // 3. Item manual (WA Order) yang hanya punya kategori_id
            elseif ($detail->kategori_id && $detail->kategori) {
                $kategori = $detail->kategori->nama_kategori;
                $jumlah = $detail->jumlah;
                $namaItem = $detail->nama_item;

                switch ($kategori) {
                    case 'Jajanan Basah':
                        if ($jumlah <= 100) {
                            $totalWaktu += 60;
                        } elseif ($jumlah <= 500) {
                            $totalWaktu += 180;
                        } else {
                            $totalWaktu += 300;
                        }
                        break;
                        
                    case 'Paketan':
                        // PERBAIKAN: Paketan WA Order - sama dengan produk biasa
                        if (str_contains($namaItem, 'Godogan')) {
                            // Godogan: 1 jam untuk 2 tampan → 30 menit per item
                            $totalWaktu += 30 * $jumlah;
                        }
                        elseif (str_contains($namaItem, 'Snack Mini isi 100') || str_contains($namaItem, 'Snack Mini 100 Paket')) {
                            // Snack Mini isi 100: 3 jam untuk 2 tampan → 90 menit per item
                            $totalWaktu += 90 * $jumlah;
                        }
                        elseif (str_contains($namaItem, 'Snack Mini isi 150')) {
                            // Snack Mini isi 150: 3 jam untuk 2 tampan → 90 menit per item
                            $totalWaktu += 90 * $jumlah;
                        }
                        elseif (str_contains($namaItem, 'Paket Gethuk')) {
                            // Paket Gethuk: 3 jam untuk 2 tampan → 90 menit per item
                            $totalWaktu += 90 * $jumlah;
                        }
                        else {
                            // Paketan lainnya: 1 jam untuk 2 tampan → 30 menit per item
                            $totalWaktu += 30 * $jumlah;
                        }
                        break;
                        
                    case 'Snack Box':
                        // PERBAIKAN: Snack Box WA Order - 100 box = 5 jam (300 menit)
                        if ($jumlah <= 100) {
                            $totalWaktu += 300; // 5 jam untuk 100 box
                        } else {
                            // Lebih dari 100 box: (jumlah/100) × 300 menit
                            $totalWaktu += ($jumlah / 100) * 300;
                        }
                        break;
                        
                    case 'Hantaran':
                        $totalWaktu += 60 * $jumlah;
                        break;
                        
                    default:
                        $totalWaktu += 30 * $jumlah;
                }
            }
        }

        if ($totalWaktu == 0 && $pesanan->detailPesanan->count() > 0) {
            $totalWaktu = 30;
        }

        return (int) ceil($totalWaktu);
    }
    
    /**
     * GENERATE JADWAL PRODUKSI - AJAX RESPONSE JSON
     */
    public function generate()
    {
        DB::beginTransaction();
        
        try {
            // Ambil semua pesanan yang sudah lunas dan diproses, belum dijadwalkan
            $pesanan = Pesanan::with(['detailPesanan.produk.kategori', 'detailPesanan.customSnackbox', 'detailPesanan.kategori'])
                ->where('status_pembayaran', 'lunas')
                ->where('status', 'diproses')
                ->whereNotIn('id', function($query) {
                    $query->select('pesanan_id')->from('jadwal_produksi');
                })
                ->orderBy('tanggal_pesanan', 'asc')
                ->get();
            
            if ($pesanan->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pesanan baru yang perlu dijadwalkan.'
                ], 400);
            }
            
            // Kelompokkan pesanan berdasarkan TANGGAL PRODUKSI (H-1 dari tanggal pengambilan)
            $pesananByTanggalProduksi = [];
            foreach ($pesanan as $item) {
                if (!$item->tanggal_pengambilan) {
                    Log::warning('Pesanan ' . $item->nomor_pesanan . ' tidak memiliki tanggal_pengambilan');
                    continue;
                }
                
                $tanggalProduksi = date('Y-m-d', strtotime($item->tanggal_pengambilan . ' -1 day'));
                
                if (!isset($pesananByTanggalProduksi[$tanggalProduksi])) {
                    $pesananByTanggalProduksi[$tanggalProduksi] = [];
                }
                $pesananByTanggalProduksi[$tanggalProduksi][] = $item;
            }
            
            $totalDijadwalkan = 0;
            $jamMulaiDefault = "20:00:00";
            
            foreach ($pesananByTanggalProduksi as $tanggalProduksi => $items) {
                $urutan = 1;
                $jamSekarang = $jamMulaiDefault;
                
                foreach ($items as $item) {
                    $burstTime = $this->hitungBurstTime($item);
                    
                    $jamMulai = $jamSekarang;
                    $jamSelesai = date('H:i:s', strtotime($jamSekarang . ' + ' . $burstTime . ' minutes'));
                    
                    // Cek apakah melebihi tengah malam
                    $jamSelesaiTimestamp = strtotime($jamSelesai);
                    
                    if (date('H', $jamSelesaiTimestamp) >= 24) {
                        $jamSelesai = date('H:i:s', strtotime($jamSelesai . ' -24 hours'));
                        Log::info("Jadwal untuk pesanan {$item->nomor_pesanan} selesai pada {$jamSelesai} (melewati tengah malam)");
                    }
                    
                    JadwalProduksi::create([
                        'pesanan_id' => $item->id,
                        'tanggal_produksi' => $tanggalProduksi,
                        'jam_mulai' => $jamMulai,
                        'jam_selesai' => $jamSelesai,
                        'urutan' => $urutan,
                        'status' => 'menunggu',
                    ]);
                    
                    $jamSekarang = $jamSelesai;
                    $urutan++;
                    $totalDijadwalkan++;
                }
            }
            
            if ($totalDijadwalkan == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pesanan yang bisa dijadwalkan. Pastikan semua pesanan memiliki tanggal_pengambilan.'
                ], 400);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => $totalDijadwalkan . ' pesanan berhasil dijadwalkan untuk produksi.',
                'redirect' => route('admin.jadwal-produksi.index')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generate jadwal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate jadwal: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * UPDATE STATUS PRODUKSI - AJAX RESPONSE JSON
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:menunggu,produksi,selesai'
            ]);
            
            $jadwal = JadwalProduksi::find($id);
            
            if (!$jadwal) {
                return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
            }
            
            $jadwal->status = $request->status;
            $jadwal->save();
            
            if ($request->status == 'produksi') {
                Pesanan::where('id', $jadwal->pesanan_id)->update(['status' => 'diproses']);
            }
            
            if ($request->status == 'selesai') {
                Pesanan::where('id', $jadwal->pesanan_id)->update(['status' => 'selesai']);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * UPDATE WAKTU PRODUKSI MANUAL (INLINE EDIT) - AJAX RESPONSE JSON
     */
    public function updateWaktu(Request $request, $id)
    {
        try {
            $jamMulai = $request->jam_mulai;
            $jamSelesai = $request->jam_selesai;
            
            if (!$jamMulai || !$jamSelesai) {
                return response()->json(['success' => false, 'message' => 'Jam mulai dan jam selesai harus diisi'], 422);
            }
            
            // Format dan validasi waktu
            $jamMulai = date('H:i', strtotime($jamMulai));
            $jamSelesai = date('H:i', strtotime($jamSelesai));
            
            if (!$jamMulai || !$jamSelesai) {
                return response()->json(['success' => false, 'message' => 'Format waktu tidak valid'], 422);
            }
            
            $jadwal = JadwalProduksi::find($id);
            
            if (!$jadwal) {
                return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
            }
            
            $jadwal->jam_mulai = $jamMulai . ':00';
            $jadwal->jam_selesai = $jamSelesai . ':00';
            $jadwal->save();
            
            return response()->json(['success' => true, 'message' => 'Waktu produksi berhasil diupdate!']);
            
        } catch (\Exception $e) {
            Log::error('Error update waktu: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * HAPUS JADWAL PRODUKSI (PER ITEM) - AJAX RESPONSE JSON
     */
    public function destroy($id)
    {
        try {
            $jadwal = JadwalProduksi::find($id);
            
            if (!$jadwal) {
                return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
            }
            
            $jadwal->delete();
            
            return response()->json(['success' => true, 'message' => 'Jadwal produksi berhasil dihapus!']);
            
        } catch (\Exception $e) {
            Log::error('Error hapus jadwal: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * RESET JADWAL PRODUKSI - Hanya menghapus jadwal dengan status SELESAI
     * Tidak menghapus jadwal menunggu/produksi dan tidak mengenerate ulang
     */
    public function reset(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Hitung jumlah jadwal dengan status selesai
            $deletedCount = JadwalProduksi::where('status', 'selesai')->count();
            
            // Jika tidak ada jadwal selesai, beri notifikasi
            if ($deletedCount == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada jadwal produksi dengan status "Selesai" yang bisa dihapus.'
                ], 400);
            }
            
            // Hanya hapus jadwal dengan status 'selesai'
            JadwalProduksi::where('status', 'selesai')->delete();
            
            DB::commit();
            
            Log::info('Reset jadwal: ' . $deletedCount . ' jadwal dengan status "selesai" telah dihapus');
            
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus ' . $deletedCount . ' jadwal produksi yang sudah selesai.',
                'redirect' => route('admin.jadwal-produksi.index')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error reset jadwal (hapus selesai): ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal selesai: ' . $e->getMessage()
            ], 500);
        }
    }
}