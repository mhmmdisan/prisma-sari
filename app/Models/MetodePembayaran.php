<?php
// app/Models/MetodePembayaran.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    protected $table = 'metode_pembayaran';
    
    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'cabang',
        'status_aktif',
        'logo_bank'
    ];

    protected $casts = [
        'status_aktif' => 'boolean'
    ];

    // ========== RELASI ==========
    
    // Relasi ke pesanan
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_metode_pembayaran');
    }

    // ========== SCOPE ==========
    
    // Scope untuk yang aktif saja
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    // ========== ACCESSOR ==========
    
    // Format rekening lengkap
    public function getRekeningLengkapAttribute()
    {
        return $this->nama_bank . ' - ' . $this->nomor_rekening . ' (a.n. ' . $this->atas_nama . ')';
    }

    // URL logo
    public function getLogoUrlAttribute()
    {
        return $this->logo_bank ? asset('storage/bank/' . $this->logo_bank) : null;
    }
}