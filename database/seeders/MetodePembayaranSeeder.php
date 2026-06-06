<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodePembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $bank = [
            [
                'nama_bank' => 'Bank Mandiri',
                'nomor_rekening' => '123-00-1234567-8',
                'atas_nama' => 'Prisma Sari Catering',
                'cabang' => 'Kantor Pusat',
                'status_aktif' => true,
            ],
            [
                'nama_bank' => 'Bank BCA',
                'nomor_rekening' => '1234567890',
                'atas_nama' => 'Prisma Sari Catering',
                'cabang' => 'Cabang Utama',
                'status_aktif' => true,
            ],
            [
                'nama_bank' => 'Bank BRI',
                'nomor_rekening' => '1234-01-123456-78-9',
                'atas_nama' => 'Prisma Sari Catering',
                'cabang' => 'Cabang Thamrin',
                'status_aktif' => true,
            ],
        ];

        DB::table('metode_pembayaran')->insert($bank);
    }
}