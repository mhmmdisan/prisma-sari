<?php
// app/Models/Produk.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Produk extends Model
{
    protected $table = 'produk';
    
    protected $fillable = [
        'kategori_id',
        'nama_produk',
        'harga',
        'deskripsi',
        'gambar',
        'min_order',
        'is_snackbox_only',
    ];
    
    protected $casts = [
        'is_snackbox_only' => 'boolean',
    ];

    // ========== RELASI ==========
    
    // Relasi ke kategori (many to one)
    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id');
    }

    // Relasi ke keranjang
    public function keranjang()
    {
        return $this->hasMany(KeranjangDetail::class, 'produk_id');
    }

    // Relasi ke custom snackbox detail (jajanan dalam snackbox)
    public function customSnackboxDetail()
    {
        return $this->hasMany(CustomSnackboxDetail::class, 'produk_id');
    }

    // Relasi ke detail pesanan
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'produk_id');
    }

    // ========== ACCESSOR ==========
    
    // Slug otomatis
    public function getSlugAttribute()
    {
        return Str::slug($this->nama_produk);
    }

    // Format harga Rupiah
    public function getHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // URL gambar
    public function getGambarUrlAttribute()
    {
        return $this->gambar ? asset('storage/produk/' . $this->gambar) : null;
    }
}