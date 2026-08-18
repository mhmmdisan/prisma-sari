<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'Prismasari.snack@gmail.com'], // kondisi unik
            [
                'name' => 'Prisma Sari',
                'email_verified_at' => null,
                'password' => Hash::make('password'),
                'role' => 'admin',
                'no_telepon' => '081326092609',
                'alamat' => 'Jl. Serma Abdul Kadir, Sumber, Hadipolo, Kec. Jekulo, Kabupaten Kudus, Jawa Tengah 59382, Indonesia',
                'foto_profil' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}