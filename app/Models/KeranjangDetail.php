<?php
// app/Models/KeranjangDetail.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeranjangDetail extends Model
{
    protected $table = 'keranjang_detail';
    
    protected $fillable = [
        'user_id',
        'produk_id',
        'custom_snackbox_id',
        'jumlah',
        'harga',
        'subtotal'
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga' => 'integer',
        'subtotal' => 'integer'
    ];

    // ========== RELASI ==========
    
    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke produk (jika produk biasa)
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // Relasi ke custom snackbox (jika custom box)
    public function customSnackbox()
    {
        return $this->belongsTo(CustomSnackbox::class, 'custom_snackbox_id');
    }

    // ========== ACCESSOR ==========
    
    /**
     * Nama item (otomatis ambil dari produk atau custom snackbox)
     */
    public function getNamaItemAttribute()
    {
        // Jika produk biasa
        if ($this->produk_id) {
            return $this->produk->nama_produk;
        }
        
        // Jika custom snackbox
        if ($this->custom_snackbox_id && $this->customSnackbox) {
            $namaBox = $this->customSnackbox->nama_box;
            $ukuran = $this->customSnackbox->nama_ukuran ?? '';
            
            // Jika pelanggan mengisi nama custom (bukan default)
            if ($namaBox && $namaBox !== 'Custom Box ' . substr($namaBox, -14)) {
                // Tampilkan nama custom + ukuran
                return $namaBox . ' (' . $ukuran . ')';
            }
            
            // Default: Custom Snackbox + Ukuran
            return 'Custom Snackbox (' . $ukuran . ')';
        }
        
        return 'Item tidak dikenal';
    }

    // Format harga
    public function getHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Format subtotal
    public function getSubtotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}