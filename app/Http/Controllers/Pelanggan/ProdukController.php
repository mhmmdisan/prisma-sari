<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // Daftar semua produk
    public function index(Request $request)
    {
        $kategoriId = $request->get('kategori');
        $cari = $request->get('cari');
        
        $query = Produk::with('kategori')
        ->where('is_snackbox_only', 0)
         ->whereHas('kategori', function($q) {
                $q->where('nama_kategori', '!=', 'Hantaran'); // 🔥 sembunyikan kategori Hantaran
            });
        
        // Filter berdasarkan kategori
        if ($kategoriId) {
            $query->where('kategori_id', $kategoriId);
        }
        
        // Filter berdasarkan pencarian
        if ($cari) {
            $query->where('nama_produk', 'like', "%{$cari}%");
        }
        
        $produk = $query->paginate(12);
        $kategori = KategoriProduk::where('nama_kategori', '!=', 'Hantaran')->get();
        
        return view('pelanggan.produk.index', compact('produk', 'kategori', 'kategoriId', 'cari'));
    }
    
    // Detail produk
    public function show($id)
    {
        $produk = Produk::with('kategori')->findOrFail($id);

        // Jika produk adalah khusus snackbox, arahkan ke halaman custom snackbox atau tampilkan 404
        if ($produk->is_snackbox_only) {
            return redirect()->route('pelanggan.custom-snackbox.create')
                ->with('info', 'Produk ini hanya tersedia melalui Custom Snackbox.');
        }
        
        // Produk terkait (dari kategori yang sama)
        $produkTerkait = Produk::with('kategori')
            ->where('kategori_id', $produk->kategori_id)
            ->where('id', '!=', $produk->id)
            ->take(4)
            ->get();
        
        return view('pelanggan.produk.show', compact('produk', 'produkTerkait'));
    }
}