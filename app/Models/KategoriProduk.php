<?php
// app/Models/KategoriProduk.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriProduk extends Model
{
    protected $table = 'kategori_produk';
    
    protected $fillable = [
        'nama_kategori'
    ];

    // ========== RELASI ==========
    
    // Relasi ke produk (one to many)
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }

    // ========== ACCESSOR ==========
    
    // Slug otomatis dari nama_kategori
    public function getSlugAttribute()
    {
        return Str::slug($this->nama_kategori);
    }
}