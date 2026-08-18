<?php
// app/Models/Pesanan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // Tambahkan ini

class Pesanan extends Model
{
    protected $table = 'pesanan';
    
    protected $fillable = [
        'nomor_pesanan',
        'user_id',
        'tanggal_pesanan',
        'expired_at',
        'tanggal_pengambilan',
        'alamat_pengiriman',
        'total_harga',
        'status',
        'status_pembayaran',
        'id_metode_pembayaran',
        'bukti_pembayaran',
        'tanggal_bayar',
        'catatan_pesanan',
        'is_whatsapp_order'
    ];

    protected $casts = [
        'tanggal_pesanan' => 'datetime',
        'tanggal_pengambilan' => 'datetime',
        'tanggal_bayar' => 'datetime',
        'total_harga' => 'integer',
        'is_whatsapp_order' => 'boolean'
    ];

    // ========== RELASI ==========
    
    // Relasi ke user (pelanggan)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke metode pembayaran
    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'id_metode_pembayaran');
    }

    // Relasi ke detail pesanan (item-item pesanan)
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }

    // Badge status pesanan
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'menunggu_pembayaran' => 'warning',
            'diproses' => 'info',
            'selesai' => 'success',
            'dibatalkan' => 'danger',
            'terlambat' => 'danger'
        ];
        
        $labels = [
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Pesanan Selesai',
            'dibatalkan' => 'Pesanan Dibatalkan',
            'terlambat' => 'Pesanan Mengalami Keterlambat'
        ];
        
        return [
            'class' => $badges[$this->status] ?? 'secondary',
            'label' => $labels[$this->status] ?? $this->status
        ];
    }

    // Badge status pembayaran
    public function getStatusPembayaranBadgeAttribute()
    {
        $badges = [
            'belum_bayar' => 'danger',
            'menunggu_konfirmasi' => 'warning',
            'lunas' => 'success'
        ];
        
        $labels = [
            'belum_bayar' => 'Belum Dibayar',
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'lunas' => 'Lunas'
        ];
        
        return [
            'class' => $badges[$this->status_pembayaran] ?? 'secondary',
            'label' => $labels[$this->status_pembayaran] ?? $this->status_pembayaran
        ];
    }

    // Format total harga
    public function getTotalHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    // ========== ACCESSOR BUKTI PEMBAYARAN ==========
    
    /**
     * URL bukti pembayaran - dengan pengecekan file
     */
    public function getBuktiPembayaranUrlAttribute()
    {
        if (!$this->bukti_pembayaran) {
            return null;
        }
        
        // Cek apakah file benar-benar ada di storage
        if (Storage::disk('public')->exists($this->bukti_pembayaran)) {
            return asset('storage/' . $this->bukti_pembayaran);
        }
        
        return null; // file tidak ditemukan
    }
    
    /**
     * Cek apakah file bukti pembayaran ada di storage
     */
    public function getBuktiPembayaranExistsAttribute()
    {
        if (!$this->bukti_pembayaran) {
            return false;
        }
        return Storage::disk('public')->exists($this->bukti_pembayaran);
    }

    // ========== HELPER ==========
    
    // Cek apakah bisa dibatalkan
    public function bisaDibatalkan()
    {
        return $this->status === 'menunggu_pembayaran' && $this->status_pembayaran === 'belum_bayar';
    }

    // Cek apakah bisa upload bukti
    public function bisaUploadBukti()
    {
        return $this->status_pembayaran === 'belum_bayar';
    }
}