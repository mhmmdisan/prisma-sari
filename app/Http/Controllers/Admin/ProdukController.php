<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProdukController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Produk::with('kategori');
        
        // Filter by category
        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }
        
        // Search by name
        if ($request->has('cari') && $request->cari) {
            $query->where('nama_produk', 'like', '%' . $request->cari . '%');
        }
        
        $produk = $query->orderBy('created_at', 'desc')->paginate(15);
        $kategori = KategoriProduk::where('nama_kategori', '!=', 'Hantaran')->get();
        
        return view('admin.produk.index', compact('produk', 'kategori'));
    }
    
    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $kategori = KategoriProduk::where('nama_kategori', '!=', 'Hantaran')->get();
        return view('admin.produk.create', compact('kategori'));
    }
    
    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'kategori_id' => 'required|exists:kategori_produk,id',
                'nama_produk' => 'required|string|max:200|unique:produk,nama_produk',
                'harga' => 'required|integer|min:0',
                'deskripsi' => 'nullable|string',
                'min_order' => 'required|integer|min:1',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'is_snackbox_only' => 'nullable|boolean',
            ]);
            
            $data = $request->except('gambar');
            $data['is_snackbox_only'] = $request->has('is_snackbox_only') ? 1 : 0;
            
            // Handle image upload
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $filename = time() . '_' . Str::slug($request->nama_produk) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/produk'), $filename);
                $data['gambar'] = $filename;  
            }
            
            Produk::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan!',
                'redirect' => route('admin.produk.index')
            ]);
            
        } catch (ValidationException $e) {
            // Kirim error validasi ke frontend dengan status 422
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah produk: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $produk = Produk::with('kategori')->findOrFail($id);
        return view('admin.produk.show', compact('produk'));
    }
    
    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = KategoriProduk::where('nama_kategori', '!=', 'Hantaran')->get();
        return view('admin.produk.edit', compact('produk', 'kategori'));
    }
    
    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $produk = Produk::findOrFail($id);
            
            $request->validate([
                'kategori_id' => 'required|exists:kategori_produk,id',
                'nama_produk' => 'required|string|max:200|unique:produk,nama_produk,' . $id,
                'harga' => 'required|integer|min:0',
                'deskripsi' => 'nullable|string',
                'min_order' => 'required|integer|min:1',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'is_snackbox_only' => 'nullable|boolean',
            ]);
            
            $data = $request->except('gambar');
            $data['is_snackbox_only'] = $request->has('is_snackbox_only') ? 1 : 0;
            
            // Handle image upload (hapus gambar lama jika ada)
            if ($request->hasFile('gambar')) {
                if ($produk->gambar && file_exists(public_path('storage/produk/' . $produk->gambar))) {
                    @unlink(public_path('storage/produk/' . $produk->gambar));
                }
        
                $file = $request->file('gambar');
                $filename = time() . '_' . Str::slug($request->nama_produk) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/produk'), $filename);
                $data['gambar'] = $filename;
            }
            
            $produk->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui!',
                'redirect' => route('admin.produk.index')
            ]);
            
        } catch (ValidationException $e) {
            // Kirim error validasi ke frontend dengan status 422
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui produk: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        try {
            $produk = Produk::findOrFail($id);
            
            if ($produk->gambar && file_exists(public_path('storage/produk/' . $produk->gambar))) {
                @unlink(public_path('storage/produk/' . $produk->gambar));
            }
            
            $produk->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}