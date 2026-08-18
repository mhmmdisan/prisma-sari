<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TanggalNonaktif extends Model
{
    protected $table = 'tanggal_nonaktif';
    
    protected $fillable = [
        'tanggal',
        'keterangan',
        'status',
        'created_by',
    ];

    // ========== RELASI ==========
    
    /**
     * Relasi ke user (admin) yang menonaktifkan tanggal
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ========== SCOPES ==========
    
    public function scopeNonaktif($query)
    {
        return $query->where('status', 'nonaktif');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }

    // ========== ACCESSOR ==========
    
    public function getTanggalFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->tanggal)->locale('id')->translatedFormat('l, d F Y');
    }

    /**
     * Nama admin yang menonaktifkan
     */
    public function getCreatedByNameAttribute()
    {
        return $this->createdBy ? $this->createdBy->name : '-';
    }
}