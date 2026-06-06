<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriProdukSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Jajanan Basah'],
            ['nama_kategori' => 'Paketan'],
        ];

        DB::table('kategori_produk')->insert($kategori);
    }
}