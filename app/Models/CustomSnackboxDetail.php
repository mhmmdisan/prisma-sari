<?php
// app/Models/CustomSnackboxDetail.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomSnackboxDetail extends Model
{
    protected $table = 'custom_snackbox_detail';
    
    protected $fillable = [
        'custom_snackbox_id',
        'produk_id',
        'jumlah',
        'subtotal'
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'subtotal' => 'integer'
    ];

    // ========== RELASI ==========
    
    // Relasi ke custom snackbox
    public function customSnackbox()
    {
        return $this->belongsTo(CustomSnackbox::class, 'custom_snackbox_id');
    }

    // Relasi ke produk (jajanan)
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // ========== ACCESSOR ==========
    
    // Format subtotal
    public function getSubtotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}