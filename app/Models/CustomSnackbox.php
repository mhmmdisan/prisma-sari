<?php
// app/Models/CustomSnackbox.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomSnackbox extends Model
{
    protected $table = 'custom_snackbox';
    
    protected $fillable = [
        'user_id',
        'kode_ukuran',
        'jumlah_item',
        'nama_box',           
        'total_item',
        'jumlah_box',
        'harga_per_box',
        'harga_total'
    ];

    // ========== CASTS ==========
    
    protected $casts = [
        'jumlah_item' => 'integer',
        'total_item' => 'integer',
        'jumlah_box' => 'integer',
        'harga_per_box' => 'integer',
        'harga_total' => 'integer'
    ];

    // ========== RELASI ==========
    
    // Relasi ke user (pelanggan)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke detail custom snackbox (isi jajanan)
    public function detail()
    {
        return $this->hasMany(CustomSnackboxDetail::class, 'custom_snackbox_id');
    }

    // Relasi ke keranjang
    public function keranjang()
    {
        return $this->hasOne(KeranjangDetail::class, 'custom_snackbox_id');
    }

    // Relasi ke detail pesanan
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'custom_snackbox_id');
    }

    // ========== ACCESSOR ==========
    
    // Nama ukuran berdasarkan kode (sesuai data ukuran Anda)
    public function getNamaUkuranAttribute()
    {
        $ukuran = [
            'A' => 'Ukuran A (3 Item)',
            'B' => 'Ukuran B (4 Item)',
            'C' => 'Ukuran C (5 Item)',
            'D' => 'Ukuran D (5 Item)',
            'E' => 'Ukuran E (6 Item)',
            'F' => 'Ukuran F (8 Item)',
        ];
        return $ukuran[$this->kode_ukuran] ?? 'Reguler';
    }

    // Format harga per box
    public function getHargaPerBoxFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_per_box, 0, ',', '.');
    }

    // Format harga total
    public function getHargaTotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_total, 0, ',', '.');
    }

    // ========== HELPER ==========
    
    // Hitung ulang harga total (jika diperlukan)
    public function hitungHargaTotal()
    {
        return $this->harga_per_box * $this->jumlah_box;
    }
}