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

        $jadwalByTanggal = [];
        foreach ($jadwalProduksi as $item) {
            if (!$item->pesanan) continue;
            $tanggal = $item->tanggal_produksi->format('Y-m-d');
            $jadwalByTanggal[$tanggal][] = (object) [
                'id'            => $item->id,
                'urutan'        => $item->urutan,
                'nomor_pesanan' => $item->pesanan->nomor_pesanan,
                'pesanan'       => $item->pesanan,
                'jam_mulai'     => $item->jam_mulai,
                'jam_selesai'   => $item->jam_selesai,
                'status'        => $item->status,
                'pesanan_id'    => $item->pesanan_id,
            ];
        }
        return view('admin.jadwalproduksi', compact('jadwalByTanggal'));
    }

    /**
     * HITUNG BURST TIME – PROPORSIONAL SESUAI DATA TERBARU
     * 
     * Data:
     * - Jajanan Basah       : 150 produk → 180 menit
     * - Paket Gethuk        : 4 tampan  → 180 menit
     * - Godogan Besar       : 2 tampan  → 120 menit
     * - Godogan Kecil       : 10 tampan → 120 menit
     * - Snack Mini Isi 150  : 1 tampan  → 210 menit
     * - Snack Mini Isi 100  : 2 tampan  → 210 menit
     * - Snack Mini Kecil    : 10 tampan → 180 menit
     * - Snack Mini Besar    : 10 tampan → 180 menit
     * - Snackbox / Custom   : 50 box    → 180 menit
     * - Hantaran            : 4 item    → 180 menit
     */
    private function hitungBurstTime($pesanan)
    {
        $totalWaktu = 0;

        if (!$pesanan->relationLoaded('detailPesanan')) {
            $pesanan->load(['detailPesanan.produk.kategori', 'detailPesanan.customSnackbox', 'detailPesanan.kategori']);
        }

        foreach ($pesanan->detailPesanan as $detail) {
            // 1. Produk biasa (punya produk_id)
            if ($detail->produk_id && $detail->produk && $detail->produk->kategori) {
                $kategori   = $detail->produk->kategori->nama_kategori;
                $namaProduk = $detail->produk->nama_produk;
                $jumlah     = $detail->jumlah;

                if ($kategori == 'Jajanan Basah') {
                    // 150 pcs → 180 menit
                    $totalWaktu += ($jumlah / 150) * 180;
                } 
                elseif ($kategori == 'Paketan') {
                    // Urutan pengecekan: dari yang paling spesifik ke umum
                    if (strpos($namaProduk, 'Godogan Kecil') !== false) {
                        // 10 tampan → 120 menit
                        $totalWaktu += ($jumlah / 10) * 120;
                    } 
                    elseif (strpos($namaProduk, 'Godogan Besar') !== false) {
                        // 2 tampan → 120 menit
                        $totalWaktu += ($jumlah / 2) * 120;
                    } 
                    elseif (strpos($namaProduk, 'Godogan') !== false) {
                        // Default Godogan (jika tidak ada keterangan) → anggap Besar
                        $totalWaktu += ($jumlah / 2) * 120;
                    } 
                    elseif (strpos($namaProduk, 'Paket Gethuk') !== false) {
                        // 4 tampan → 180 menit
                        $totalWaktu += ($jumlah / 4) * 180;
                    } 
                    elseif (strpos($namaProduk, 'Snack Mini Kecil') !== false) {
                        // 10 tampan → 180 menit
                        $totalWaktu += ($jumlah / 10) * 120;
                    } 
                    elseif (strpos($namaProduk, 'Snack Mini Besar') !== false) {
                        // 10 tampan → 180 menit
                        $totalWaktu += ($jumlah / 10) * 180;
                    } 
                    elseif (strpos($namaProduk, 'Snack Mini isi 150') !== false) {
                        // 1 tampan → 210 menit
                        $totalWaktu += ($jumlah / 1) * 210;
                    } 
                    elseif (strpos($namaProduk, 'Snack Mini isi 100') !== false || strpos($namaProduk, 'Snack Mini 100 Paket') !== false) {
                        // 2 tampan → 210 menit
                        $totalWaktu += ($jumlah / 2) * 210;
                    } 
                    else {
                        // Paketan lainnya (default ke Godogan Besar)
                        $totalWaktu += ($jumlah / 2) * 120;
                    }
                } 
                elseif ($kategori == 'Hantaran') {
                    // 4 item → 180 menit
                    $totalWaktu += ($jumlah / 4) * 180;
                } 
                else {
                    // Kategori lain default
                    $totalWaktu += 0;
                }
            }
            // 2. Custom Snackbox
            elseif ($detail->custom_snackbox_id && $detail->customSnackbox) {
                $jumlahBox = $detail->customSnackbox->jumlah_box ?? 0;
                if ($jumlahBox > 0) {
                    // 50 box → 180 menit
                    $totalWaktu += ($jumlahBox / 50) * 180;
                }
            }
            // 3. WA Order manual (hanya punya kategori_id)
            elseif ($detail->kategori_id && $detail->kategori) {
                $kategori = $detail->kategori->nama_kategori;
                $jumlah   = $detail->jumlah;
                $namaItem = $detail->nama_item;

                switch ($kategori) {
                    case 'Jajanan Basah':
                        $totalWaktu += ($jumlah / 150) * 180;
                        break;
                    case 'Paketan':
                        if (strpos($namaItem, 'Godogan Kecil') !== false) {
                            $totalWaktu += ($jumlah / 10) * 120;
                        } elseif (strpos($namaItem, 'Godogan Besar') !== false) {
                            $totalWaktu += ($jumlah / 2) * 120;
                        } elseif (strpos($namaItem, 'Godogan') !== false) {
                            $totalWaktu += ($jumlah / 2) * 120;
                        } elseif (strpos($namaItem, 'Paket Gethuk') !== false) {
                            $totalWaktu += ($jumlah / 4) * 180;
                        } elseif (strpos($namaItem, 'Snack Mini Kecil') !== false) {
                            $totalWaktu += ($jumlah / 10) * 120;
                        } elseif (strpos($namaItem, 'Snack Mini Besar') !== false) {
                            $totalWaktu += ($jumlah / 10) * 180;
                        } elseif (strpos($namaItem, 'Snack Mini isi 150') !== false) {
                            $totalWaktu += ($jumlah / 1) * 210;
                        } elseif (strpos($namaItem, 'Snack Mini isi 100') !== false || strpos($namaItem, 'Snack Mini 100 Paket') !== false) {
                            $totalWaktu += ($jumlah / 2) * 210;
                        } else {
                            $totalWaktu += 0;
                        }
                        break;
                    case 'Snack Box':
                        // 50 box → 180 menit
                        $totalWaktu += ($jumlah / 50) * 180;
                        break;
                    case 'Hantaran':
                        $totalWaktu += ($jumlah / 4) * 180;
                        break;
                    default:
                        $totalWaktu += 0;
                }
            }
        }

        // Minimal 30 menit jika ada detail tapi perhitungan menghasilkan 0
        if ($totalWaktu == 0 && $pesanan->detailPesanan->count() > 0) {
            $totalWaktu = 30;
        }

        return (int) ceil($totalWaktu);
    }

    /**
     * GENERATE JADWAL PRODUKSI
     */
    public function generate(Request $request)
    {
        DB::beginTransaction();

        try {
            $jamMulaiGlobal = '20:00:00';

            // Ambil pesanan yang belum dijadwalkan
            $pesanan = Pesanan::with([
                'detailPesanan.produk.kategori',
                'detailPesanan.customSnackbox',
                'detailPesanan.kategori',
            ])
                ->where('status_pembayaran', 'lunas')
                ->where('status', 'diproses')
                ->whereNotIn('id', function ($query) {
                    $query->select('pesanan_id')->from('jadwal_produksi');
                })
                ->get();

            if ($pesanan->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pesanan baru yang perlu dijadwalkan.',
                ], 400);
            }

            // Kelompokkan berdasarkan tanggal produksi (H-1 dari pengambilan)
            $pesananByTanggalProduksi = [];
            foreach ($pesanan as $item) {
                if (!$item->tanggal_pengambilan) {
                    Log::warning('Pesanan ' . $item->nomor_pesanan . ' tidak memiliki tanggal_pengambilan');
                    continue;
                }
                $tanggalProduksi = date('Y-m-d', strtotime($item->tanggal_pengambilan . ' -1 day'));
                $pesananByTanggalProduksi[$tanggalProduksi][] = $item;
            }

            $totalDijadwalkan = 0;

            foreach ($pesananByTanggalProduksi as $tanggalProduksi => $items) {

                usort($items, function ($a, $b) {
                    $jamA = strtotime(date('H:i:s', strtotime($a->tanggal_pengambilan)));
                    $jamB = strtotime(date('H:i:s', strtotime($b->tanggal_pengambilan)));
                    return $jamA - $jamB;
                });

                $urutan        = 1;
                $waitingTime   = 0; 

                // Base timestamp menggunakan TANGGAL PRODUKSI (bukan hari ini)
                $baseTimestamp = strtotime($tanggalProduksi . ' ' . $jamMulaiGlobal);

                foreach ($items as $item) {

                    $burstTime = $this->hitungBurstTime($item);

                    $wt = $waitingTime;

                    $jamMulaiTS = $baseTimestamp + ($wt * 60);
                    $jamMulai   = date('H:i:s', $jamMulaiTS);

                    $jamSelesaiTS = $jamMulaiTS + ($burstTime * 60);
                    $jamSelesai   = date('H:i:s', $jamSelesaiTS);

                    // Log jika melewati tengah malam (informasi tambahan)
                    if (($wt + $burstTime) > (24 * 60)) {
                        Log::info("Jadwal pesanan {$item->nomor_pesanan} pada {$tanggalProduksi} selesai pukul {$jamSelesai} (melewati tengah malam)");
                    }

                    // Simpan jadwal produksi
                    JadwalProduksi::create([
                        'pesanan_id'       => $item->id,
                        'tanggal_produksi' => $tanggalProduksi,
                        'jam_mulai'        => $jamMulai,
                        'jam_selesai'      => $jamSelesai,
                        'urutan'           => $urutan,
                        'status'           => 'menunggu',
                    ]);

                    $waitingTime += $burstTime;

                    $urutan++;
                    $totalDijadwalkan++;
                }
            }

            if ($totalDijadwalkan == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pesanan yang bisa dijadwalkan. Pastikan semua pesanan memiliki tanggal_pengambilan.',
                ], 400);
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => $totalDijadwalkan . ' pesanan berhasil dijadwalkan untuk produksi.',
                'redirect' => route('admin.jadwal-produksi.index'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generate jadwal: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal generate jadwal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * UPDATE STATUS PRODUKSI
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:menunggu,produksi,selesai',
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

            return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status: ' . $e->getMessage()], 500);
        }
    }

    /**
     * UPDATE WAKTU PRODUKSI MANUAL (INLINE EDIT)
     * Note: Kolom jam_mulai dan jam_selesai di tabel bisa diedit bebas
     */
    public function updateWaktu(Request $request, $id)
    {
        try {
            $jamMulai   = $request->jam_mulai;
            $jamSelesai = $request->jam_selesai;

            if (!$jamMulai || !$jamSelesai) {
                return response()->json(['success' => false, 'message' => 'Jam mulai dan jam selesai harus diisi'], 422);
            }

            $jamMulai   = date('H:i', strtotime($jamMulai));
            $jamSelesai = date('H:i', strtotime($jamSelesai));

            if (!$jamMulai || !$jamSelesai) {
                return response()->json(['success' => false, 'message' => 'Format waktu tidak valid'], 422);
            }

            $jadwal = JadwalProduksi::find($id);
            if (!$jadwal) {
                return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
            }

            $jadwal->jam_mulai   = $jamMulai . ':00';
            $jadwal->jam_selesai = $jamSelesai . ':00';
            $jadwal->save();

            return response()->json(['success' => true, 'message' => 'Waktu produksi berhasil diupdate!']);
        } catch (\Exception $e) {
            Log::error('Error update waktu: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * HAPUS JADWAL PRODUKSI (PER ITEM)
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
     * RESET JADWAL PRODUKSI (Hanya hapus status SELESAI)
     */
    public function reset(Request $request)
    {
        DB::beginTransaction();
        try {
            $deletedCount = JadwalProduksi::where('status', 'selesai')->count();
            if ($deletedCount == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada jadwal produksi dengan status "Selesai" yang bisa dihapus.',
                ], 400);
            }

            JadwalProduksi::where('status', 'selesai')->delete();
            DB::commit();

            Log::info('Reset jadwal: ' . $deletedCount . ' jadwal dengan status "selesai" telah dihapus');

            return response()->json([
                'success'  => true,
                'message'  => 'Berhasil menghapus ' . $deletedCount . ' jadwal produksi yang sudah selesai.',
                'redirect' => route('admin.jadwal-produksi.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error reset jadwal (hapus selesai): ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus jadwal selesai: ' . $e->getMessage()], 500);
        }
    }
}