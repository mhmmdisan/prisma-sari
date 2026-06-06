<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class JadwalProduksi extends Model
{
    protected $table = 'jadwal_produksi';
    
    protected $fillable = [
        'pesanan_id',
        'tanggal_produksi',
        'jam_mulai',
        'jam_selesai',
        'urutan',
        'status'
    ];

    protected $casts = [
        'tanggal_produksi' => 'date',
        'urutan' => 'integer'
    ];

    // ========== RELASI ==========
    
    /**
     * Relasi ke pesanan
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    // ========== ACCESSOR ==========
    
    /**
     * Format jam mulai (H:i)
     * 🔥 DIPERBAIKI: Karena jam_mulai di database bertipe TIME, bukan DATETIME
     */
    public function getJamMulaiFormatAttribute(): string
    {
        if (!$this->jam_mulai) return '-';
        
        // Jika sudah dalam format string waktu (H:i:s)
        if (is_string($this->jam_mulai)) {
            return substr($this->jam_mulai, 0, 5);
        }
        
        return $this->jam_mulai ? Carbon::parse($this->jam_mulai)->format('H:i') : '-';
    }

    /**
     * Format jam selesai (H:i)
     */
    public function getJamSelesaiFormatAttribute(): string
    {
        if (!$this->jam_selesai) return '-';
        
        if (is_string($this->jam_selesai)) {
            return substr($this->jam_selesai, 0, 5);
        }
        
        return $this->jam_selesai ? Carbon::parse($this->jam_selesai)->format('H:i') : '-';
    }

    /**
     * Durasi produksi dalam menit
     * 🔥 DIPERBAIKI: Menghitung durasi dari jam_mulai dan jam_selesai
     */
    public function getDurasiAttribute(): int
    {
        if (!$this->jam_mulai || !$this->jam_selesai) return 0;
        
        $mulai = is_string($this->jam_mulai) ? $this->jam_mulai : Carbon::parse($this->jam_mulai)->format('H:i:s');
        $selesai = is_string($this->jam_selesai) ? $this->jam_selesai : Carbon::parse($this->jam_selesai)->format('H:i:s');
        
        $mulaiMenit = explode(':', $mulai);
        $selesaiMenit = explode(':', $selesai);
        
        $totalMulai = (int)$mulaiMenit[0] * 60 + (int)$mulaiMenit[1];
        $totalSelesai = (int)$selesaiMenit[0] * 60 + (int)$selesaiMenit[1];
        
        $durasi = $totalSelesai - $totalMulai;
        
        // Jika durasi negatif (melewati tengah malam), tambah 24 jam
        if ($durasi < 0) $durasi += 24 * 60;
        
        return $durasi;
    }

    /**
     * Badge status dengan warna
     */
    public function getStatusBadgeAttribute(): array
    {
        $badges = [
            'menunggu' => ['class' => 'bg-warning text-dark', 'label' => 'Menunggu'],
            'produksi' => ['class' => 'bg-info text-white', 'label' => 'Produksi'],
            'selesai' => ['class' => 'bg-success text-white', 'label' => 'Selesai'],
        ];
        
        return $badges[$this->status] ?? ['class' => 'bg-secondary text-white', 'label' => $this->status];
    }

    // ========== SCOPES ==========
    
    /**
     * Scope untuk jadwal yang masih menunggu
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    /**
     * Scope untuk jadwal yang sedang produkcji
     */
    public function scopeProduksi($query)
    {
        return $query->where('status', 'produksi');
    }

    /**
     * Scope untuk jadwal yang sudah selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope untuk tanggal tertentu
     */
    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal_produksi', $tanggal);
    }
}