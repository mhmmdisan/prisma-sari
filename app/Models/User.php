<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',              
        'no_telepon',
        'alamat',
        'foto_profil',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // Relasi ke keranjang
    public function keranjang()
    {
        return $this->hasMany(KeranjangDetail::class, 'user_id');
    }

    // Relasi ke custom snackbox
    public function customSnackbox()
    {
        return $this->hasMany(CustomSnackbox::class, 'user_id');
    }

    // Relasi ke pesanan
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'user_id');
    }

    // ========== HELPER ==========
    
    // Ambil jumlah item di keranjang
    public function getJumlahKeranjangAttribute()
    {
        return $this->keranjang()->count();
    }

    // Ambil total harga keranjang
    public function getTotalKeranjangAttribute()
    {
        return $this->keranjang()->sum('subtotal');
    }
    
    // ========== METHOD CEK ROLE ==========
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPelanggan()
    {
        return $this->role === 'pelanggan';
    }

    public function isPemilik()
    {
        return $this->role === 'pemilik';
    }
}