<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    
    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'custom_snackbox_id',
        'nama_item',
        'kategori_id',      
        'jumlah',
        'harga_satuan',
        'subtotal',
        'catatan'
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'integer',
        'subtotal' => 'integer'
    ];

    // ========== RELASI ==========
    
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function customSnackbox()
    {
        return $this->belongsTo(CustomSnackbox::class, 'custom_snackbox_id');
    }
    
    public function kategori()
{
    return $this->belongsTo(KategoriProduk::class, 'kategori_id');
}

    // ========== ACCESSOR ==========
    
    public function getHargaSatuanFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    public function getSubtotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getTipeItemAttribute()
    {
        if ($this->produk_id) {
            return 'produk';
        }
        if ($this->custom_snackbox_id) {
            return 'snackbox';
        }
        return 'unknown';
    }
}