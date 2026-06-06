<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\CustomSnackbox;
use App\Models\CustomSnackboxDetail;
use App\Models\KeranjangDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomSnackboxController extends Controller
{
    // Halaman buat custom snackbox
    public function create()
    {
        // Ambil semua produk dengan kategori Jajanan Basah (kategori_id = 1)
        // ATAU produk yang is_snackbox_only = 1 (Air Mineral)
        $daftarJajanan = Produk::with('kategori')
            ->where(function ($query) {
                $query->where('kategori_id', 1)
                    ->orWhere('is_snackbox_only', 1);
            })
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

        return view('pelanggan.custom-snackbox', compact('daftarJajanan', 'ukuran'));
    }

    // Halaman edit custom snackbox
    public function edit($id)
    {
        $customSnackbox = CustomSnackbox::with('detail.produk')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $daftarJajanan = Produk::with('kategori')
            ->where(function ($query) {
                $query->where('kategori_id', 1)
                    ->orWhere('is_snackbox_only', 1);
            })
            ->get();

        $ukuran = [
            'A' => ['nama' => 'Ukuran A (3 Item)', 'jumlah_item' => 3, 'harga' => 1500],
            'B' => ['nama' => 'Ukuran B (4 Item)', 'jumlah_item' => 4, 'harga' => 1500],
            'C' => ['nama' => 'Ukuran C (5 Item)', 'jumlah_item' => 5, 'harga' => 1500],
            'D' => ['nama' => 'Ukuran D (5 Item)', 'jumlah_item' => 5, 'harga' => 2000],
            'E' => ['nama' => 'Ukuran E (6 Item)', 'jumlah_item' => 6, 'harga' => 2500],
            'F' => ['nama' => 'Ukuran F (8 Item)', 'jumlah_item' => 8, 'harga' => 3000],
        ];

        // Untuk menandai produk mana yang sudah dipilih
        $selectedDetails = $customSnackbox->detail->keyBy('produk_id');

        return view('pelanggan.custom-snackbox', compact('customSnackbox', 'daftarJajanan', 'ukuran', 'selectedDetails'));
    }

    // Update custom snackbox
    public function update(Request $request, $id)
    {
        Log::info('========== CUSTOM SNACKBOX UPDATE - MULAI ==========');
        Log::info('Request data:', $request->all());

        $customSnackbox = CustomSnackbox::where('user_id', Auth::id())->findOrFail($id);
        $keranjang = KeranjangDetail::where('custom_snackbox_id', $customSnackbox->id)->first();

        if (!$keranjang) {
            return back()->with('error', 'Snackbox tidak ditemukan di keranjang.');
        }

        $request->validate([
            'kode_ukuran' => 'required|in:A,B,C,D,E,F',
            'jumlah_box' => 'required|integer|min:35',
            'jajanan' => 'required|array|min:1',
            'jajanan.*.id' => 'required|exists:produk,id',
            'jajanan.*.selected' => 'nullable|in:0,1',
        ]);

        Log::info('Validasi berhasil');

        DB::beginTransaction();

        try {
            $ukuranData = [
                'A' => ['jumlah_item' => 3, 'harga' => 1500],
                'B' => ['jumlah_item' => 4, 'harga' => 1500],
                'C' => ['jumlah_item' => 5, 'harga' => 1500],
                'D' => ['jumlah_item' => 5, 'harga' => 2000],
                'E' => ['jumlah_item' => 6, 'harga' => 2500],
                'F' => ['jumlah_item' => 8, 'harga' => 3000],
            ];

            $ukuran = $ukuranData[$request->kode_ukuran];
            Log::info('Ukuran dipilih: ' . $request->kode_ukuran, $ukuran);

            $totalItem = 0;
            $totalHargaJajanan = 0;
            $selectedJajanan = [];

            foreach ($request->jajanan as $item) {
                $isSelected = isset($item['selected']) && $item['selected'] == 1;
                if ($isSelected) {
                    $produk = Produk::find($item['id']);
                    if ($produk) {
                        $totalItem++;
                        $totalHargaJajanan += $produk->harga;
                        $selectedJajanan[] = [
                            'id' => $item['id'],
                            'produk' => $produk,
                            'jumlah' => 1
                        ];
                        Log::info("Item dipilih: {$produk->nama_produk} (Rp {$produk->harga})");
                    }
                }
            }

            Log::info("Total item: {$totalItem}, Total harga jajanan: {$totalHargaJajanan}");

            if ($totalItem == 0) {
                DB::rollBack();
                return back()->with('error', 'Pilih minimal 1 jajanan!');
            }

            if ($totalItem > $ukuran['jumlah_item']) {
                DB::rollBack();
                return back()->with('error', 'Jumlah item melebihi kapasitas box (maksimal ' . $ukuran['jumlah_item'] . ' item)!');
            }

            // Nama box: jika kosong, gunakan nama lama atau default
            $namaBox = $request->nama_box;
            if (empty($namaBox)) {
                $namaBox = $customSnackbox->nama_box ?: 'Custom Box ' . date('YmdHis');
            }
            Log::info("Nama box: {$namaBox}");

            $hargaPerBox = $ukuran['harga'] + $totalHargaJajanan;
            $hargaTotal = $hargaPerBox * $request->jumlah_box;

            $customSnackbox->update([
                'kode_ukuran' => $request->kode_ukuran,
                'jumlah_item' => $ukuran['jumlah_item'],
                'nama_box' => $namaBox,
                'total_item' => $totalItem,
                'jumlah_box' => $request->jumlah_box,
                'harga_per_box' => $hargaPerBox,
                'harga_total' => $hargaTotal,
            ]);

            Log::info("CustomSnackbox terupdate dengan ID: {$customSnackbox->id}");

            // Hapus detail lama
            $customSnackbox->detail()->delete();

            foreach ($selectedJajanan as $item) {
                $subtotal = $item['produk']->harga * $item['jumlah'];
                CustomSnackboxDetail::create([
                    'custom_snackbox_id' => $customSnackbox->id,
                    'produk_id' => $item['id'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $subtotal,
                ]);
            }
            Log::info("Detail jajanan terupdate: " . count($selectedJajanan) . " item");

            $keranjang->update([
                'jumlah' => $request->jumlah_box,
                'harga' => $hargaPerBox,
                'subtotal' => $hargaTotal,
            ]);

            DB::commit();
            Log::info('========== UPDATE BERHASIL ==========');

            return redirect()->route('pelanggan.keranjang.index')
                ->with('success', 'Custom snackbox berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('========== ERROR UPDATE ==========');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('Error line: ' . $e->getLine());
            Log::error('Error file: ' . $e->getFile());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Gagal memperbarui custom snackbox: ' . $e->getMessage());
        }
    }

    // Simpan custom snackbox
    public function store(Request $request)
    {
        Log::info('========== CUSTOM SNACKBOX - MULAI ==========');
        Log::info('Request data:', $request->all());

        $request->validate([
            'kode_ukuran' => 'required|in:A,B,C,D,E,F',
            'jumlah_box' => 'required|integer|min:1',
            'jajanan' => 'required|array|min:1',
            'jajanan.*.id' => 'required|exists:produk,id',
            'jajanan.*.selected' => 'nullable|in:0,1',
        ]);

        Log::info('Validasi berhasil');

        if ($request->jumlah_box < 35) {
            Log::info('Jumlah box kurang dari 35: ' . $request->jumlah_box);
            return back()->with('error', 'Minimal pemesanan custom snackbox adalah 35 box!');
        }

        DB::beginTransaction();

        try {
            $ukuranData = [
                'A' => ['jumlah_item' => 3, 'harga' => 1500],
                'B' => ['jumlah_item' => 4, 'harga' => 1500],
                'C' => ['jumlah_item' => 5, 'harga' => 1500],
                'D' => ['jumlah_item' => 5, 'harga' => 2000],
                'E' => ['jumlah_item' => 6, 'harga' => 2500],
                'F' => ['jumlah_item' => 8, 'harga' => 3000],
            ];

            $ukuran = $ukuranData[$request->kode_ukuran];
            Log::info('Ukuran dipilih: ' . $request->kode_ukuran, $ukuran);

            $totalItem = 0;
            $totalHargaJajanan = 0;
            $selectedJajanan = [];

            foreach ($request->jajanan as $key => $item) {
                $isSelected = isset($item['selected']) && $item['selected'] == 1;
                if ($isSelected) {
                    $produk = Produk::find($item['id']);
                    if ($produk) {
                        $jumlah = 1;
                        $totalItem += $jumlah;
                        $totalHargaJajanan += $produk->harga * $jumlah;
                        $selectedJajanan[] = [
                            'id' => $item['id'],
                            'jumlah' => $jumlah,
                            'produk' => $produk
                        ];
                        Log::info("Item dipilih: {$produk->nama_produk} (Rp {$produk->harga})");
                    }
                }
            }

            Log::info("Total item: {$totalItem}, Total harga jajanan: {$totalHargaJajanan}");

            if ($totalItem == 0) {
                DB::rollBack();
                return back()->with('error', 'Pilih minimal 1 jajanan!');
            }

            if ($totalItem > $ukuran['jumlah_item']) {
                DB::rollBack();
                return back()->with('error', 'Jumlah item melebihi kapasitas box (maksimal ' . $ukuran['jumlah_item'] . ' item)!');
            }

            $namaBox = $request->nama_box ?: 'Custom Box ' . date('YmdHis');
            Log::info("Nama box: {$namaBox}");

            $hargaPerBox = $ukuran['harga'] + $totalHargaJajanan;
            $hargaTotal = $hargaPerBox * $request->jumlah_box;
            Log::info("Harga per box: {$hargaPerBox}, Harga total: {$hargaTotal}");

            $customSnackbox = CustomSnackbox::create([
                'user_id' => Auth::id(),
                'kode_ukuran' => $request->kode_ukuran,
                'jumlah_item' => $ukuran['jumlah_item'],
                'nama_box' => $namaBox,
                'total_item' => $totalItem,
                'jumlah_box' => $request->jumlah_box,
                'harga_per_box' => $hargaPerBox,
                'harga_total' => $hargaTotal,
            ]);

            Log::info("CustomSnackbox tersimpan dengan ID: {$customSnackbox->id}");

            foreach ($selectedJajanan as $item) {
                $subtotal = $item['produk']->harga * $item['jumlah'];
                CustomSnackboxDetail::create([
                    'custom_snackbox_id' => $customSnackbox->id,
                    'produk_id' => $item['id'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $subtotal,
                ]);
            }
            Log::info("Detail jajanan tersimpan: " . count($selectedJajanan) . " item");

            KeranjangDetail::create([
                'user_id' => Auth::id(),
                'custom_snackbox_id' => $customSnackbox->id,
                'produk_id' => null,
                'jumlah' => $request->jumlah_box,
                'harga' => $hargaPerBox,
                'subtotal' => $hargaTotal,
            ]);

            DB::commit();
            Log::info('========== TRANSACTION BERHASIL ==========');

            return redirect()->route('pelanggan.keranjang.index')
                ->with('success', 'Custom snackbox berhasil dibuat dan ditambahkan ke keranjang!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('========== ERROR ==========');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('Error line: ' . $e->getLine());
            Log::error('Error file: ' . $e->getFile());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Gagal membuat custom snackbox: ' . $e->getMessage());
        }
    }

    // Hapus custom snackbox
    public function destroy($id)
    {
        try {
            $customSnackbox = CustomSnackbox::where('user_id', Auth::id())->findOrFail($id);
            KeranjangDetail::where('custom_snackbox_id', $customSnackbox->id)->delete();
            $customSnackbox->detail()->delete();
            $customSnackbox->delete();
            return back()->with('success', 'Custom snackbox dihapus!');
        } catch (\Exception $e) {
            Log::error('Error hapus custom snackbox: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus custom snackbox!');
        }
    }
}