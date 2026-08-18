<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $produk = [
            // ID 4
            [
                'kategori_id' => 1,
                'nama_produk' => 'Apem',
                'harga' => 2500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782393384_apem.jpeg',
                'min_order' => 50,
            ],
            // ID 5
            [
                'kategori_id' => 1,
                'nama_produk' => 'Puli',
                'harga' => 2500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 6
            [
                'kategori_id' => 1,
                'nama_produk' => 'Tortila/Happytos',
                'harga' => 2500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 8
            [
                'kategori_id' => 1,
                'nama_produk' => 'Kacang Telor',
                'harga' => 2500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 9
            [
                'kategori_id' => 1,
                'nama_produk' => 'Stik Bawang',
                'harga' => 2500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 10
            [
                'kategori_id' => 1,
                'nama_produk' => 'Gado-gado Tela',
                'harga' => 2500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 11
            [
                'kategori_id' => 1,
                'nama_produk' => 'Kerupuk Ikan',
                'harga' => 2500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 12
            [
                'kategori_id' => 1,
                'nama_produk' => 'Kuro Jadul',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782391760_kuro-jadul.jpeg',
                'min_order' => 50,
            ],
            // ID 13
            [
                'kategori_id' => 1,
                'nama_produk' => 'Kuro Jeruk',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782391686_kuro-jeruk.jpeg',
                'min_order' => 50,
            ],
            // ID 14
            [
                'kategori_id' => 1,
                'nama_produk' => 'Kuro Jambu Merah',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782391573_kuro-jambu-merah.jpeg',
                'min_order' => 50,
            ],
            // ID 15
            [
                'kategori_id' => 1,
                'nama_produk' => 'Kuro Jambu Hijau',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782391504_kuro-jambu-hijau.jpeg',
                'min_order' => 50,
            ],
            // ID 16
            [
                'kategori_id' => 1,
                'nama_produk' => 'Kuro Apel Merah',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782391549_kuro-apel-merah.jpeg',
                'min_order' => 50,
            ],
            // ID 17
            [
                'kategori_id' => 1,
                'nama_produk' => 'Kuro Apel Hijau',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782391406_kuro-apel-hijau.jpeg',
                'min_order' => 50,
            ],
            // ID 19
            [
                'kategori_id' => 1,
                'nama_produk' => 'Sosis Solo',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 20
            [
                'kategori_id' => 1,
                'nama_produk' => 'Risoles Bihun',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 21
            [
                'kategori_id' => 1,
                'nama_produk' => 'Lumpia Bihun',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 22
            [
                'kategori_id' => 1,
                'nama_produk' => 'Donat',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 23
            [
                'kategori_id' => 1,
                'nama_produk' => 'Misoa',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782402263_misoa.jpeg',
                'min_order' => 50,
            ],
            // ID 24
            [
                'kategori_id' => 1,
                'nama_produk' => 'Awug-awug gula aren',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782392350_awug-awug-gula-aren.jpeg',
                'min_order' => 50,
            ],
            // ID 25
            [
                'kategori_id' => 1,
                'nama_produk' => 'Awug-awug mutiara',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782392372_awug-awug-mutiara.jpeg',
                'min_order' => 50,
            ],
            // ID 26
            [
                'kategori_id' => 1,
                'nama_produk' => 'Lepet Jagung',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782402234_lepet-jagung.jpeg',
                'min_order' => 50,
            ],
            // ID 27
            [
                'kategori_id' => 1,
                'nama_produk' => 'Nogosari bandung (bunga)',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782402052_nogosari-bandung-bunga.jpeg',
                'min_order' => 50,
            ],
            // ID 28
            [
                'kategori_id' => 1,
                'nama_produk' => 'Jentik manis Jagung (bunga)',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782392080_jentik-manis-jagung-bunga.jpeg',
                'min_order' => 50,
            ],
            // ID 29
            [
                'kategori_id' => 1,
                'nama_produk' => 'Jentik manis mutiara (bunga)',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782392219_jentik-manis-mutiara-bunga.jpeg',
                'min_order' => 50,
            ],
            // ID 30
            [
                'kategori_id' => 1,
                'nama_produk' => 'Nogosari Daun',
                'harga' => 3000,
                'deskripsi' => 'Nagasari dengan isian pisang raja',
                'is_snackbox_only' => 0,
                'gambar' => '1782401462_nogosari-daun.jpeg',
                'min_order' => 50,
            ],
            // ID 31
            [
                'kategori_id' => 1,
                'nama_produk' => 'Bikang',
                'harga' => 3000,
                'deskripsi' => 'Bikang dengan motif bunga',
                'is_snackbox_only' => 0,
                'gambar' => '1782392254_bikang.jpeg',
                'min_order' => 50,
            ],
            // ID 32
            [
                'kategori_id' => 1,
                'nama_produk' => 'Tahu Bacem',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782392550_tahu-bacem.jpeg',
                'min_order' => 50,
            ],
            // ID 33
            [
                'kategori_id' => 1,
                'nama_produk' => 'Tahu Fantasi Telur Puyuh',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782392579_tahu-fantasi-telur-puyuh.jpeg',
                'min_order' => 50,
            ],
            // ID 34
            [
                'kategori_id' => 1,
                'nama_produk' => 'Putri Ayu',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782392618_putri-ayu.jpeg',
                'min_order' => 50,
            ],
            // ID 35
            [
                'kategori_id' => 1,
                'nama_produk' => 'Ketan Srundeng',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782392648_ketan-srundeng.jpeg',
                'min_order' => 50,
            ],
            // ID 36
            [
                'kategori_id' => 1,
                'nama_produk' => 'Ketan Kelapa Gula Aren',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 37
            [
                'kategori_id' => 1,
                'nama_produk' => 'Ketan Tolo',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 38
            [
                'kategori_id' => 1,
                'nama_produk' => 'Dadar gulung Pink',
                'harga' => 3000,
                'deskripsi' => 'Jajanan basah dadar gulung  dengan isian kelapa pu...',
                'is_snackbox_only' => 0,
                'gambar' => '1782401996_dadar-gulung-pink.jpeg',
                'min_order' => 50,
            ],
            // ID 39
            [
                'kategori_id' => 1,
                'nama_produk' => 'Pisang goreng crispy (Pisang Pipit)',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 42
            [
                'kategori_id' => 1,
                'nama_produk' => 'Peyek Kacang Hijau',
                'harga' => 3000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 43
            [
                'kategori_id' => 2,
                'nama_produk' => 'Jajanan Godogan Kecil',
                'harga' => 45000,
                'deskripsi' => 'Paket jajanan godogan nampan mika dengan isian pis...',
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 10,
            ],
            // ID 44
            [
                'kategori_id' => 2,
                'nama_produk' => 'Snack Mini isi 100',
                'harga' => 300000,
                'deskripsi' => 'Paket Snack Mini yang menyediakan putri mandi, kur...',
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 2,
            ],
            // ID 45
            [
                'kategori_id' => 2,
                'nama_produk' => 'Paket Jajan Godogan 1',
                'harga' => 180000,
                'deskripsi' => 'Paket dengan isian pisang, kacang rebus, ubi kunin...',
                'is_snackbox_only' => 0,
                'gambar' => '1782393789_paket-jajan-godogan-1.jpeg',
                'min_order' => 1,
            ],
            // ID 46
            [
                'kategori_id' => 2,
                'nama_produk' => 'Paket Jajan Godogan 2',
                'harga' => 180000,
                'deskripsi' => 'Paket dengan isian pisang, kacang rebus, ubi kunin...',
                'is_snackbox_only' => 0,
                'gambar' => '1782393816_paket-jajan-godogan-2.jpeg',
                'min_order' => 1,
            ],
            // ID 47
            [
                'kategori_id' => 2,
                'nama_produk' => 'Paket Gethuk',
                'harga' => 250000,
                'deskripsi' => 'Paket dengan isian gethuk, tiwul, sentiling pisang...',
                'is_snackbox_only' => 0,
                'gambar' => '1782393747_paket-gethuk.jpeg',
                'min_order' => 2,
            ],
            // ID 48
            [
                'kategori_id' => 2,
                'nama_produk' => 'Snack Mini isi 150',
                'harga' => 425000,
                'deskripsi' => 'Paket Snack Mini yang menyediakan putri mandi, kur...',
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 1,
            ],
            // ID 49
            [
                'kategori_id' => 2,
                'nama_produk' => 'Snack Mini 100 Paket A',
                'harga' => 300000,
                'deskripsi' => 'Paket Snack Mini yang menyediakan putri mandi, kur...',
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 2,
            ],
            // ID 50
            [
                'kategori_id' => 2,
                'nama_produk' => 'Snack Mini 100 Paket B',
                'harga' => 300000,
                'deskripsi' => 'Paket Snack Mini yang menyediakan bugis daun, lepe...',
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 2,
            ],
            // ID 51
            [
                'kategori_id' => 2,
                'nama_produk' => 'Snack Mini 100 Paket C',
                'harga' => 300000,
                'deskripsi' => 'Paket Snack Mini yang menyediakan Semar mendem, ku...',
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 2,
            ],
            // ID 64
            [
                'kategori_id' => 1,
                'nama_produk' => 'Cleo (botol mini)',
                'harga' => 2500,
                'deskripsi' => null,
                'is_snackbox_only' => 1,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 72
            [
                'kategori_id' => 1,
                'nama_produk' => 'KHQ (botol mini)',
                'harga' => 1500,
                'deskripsi' => null,
                'is_snackbox_only' => 1,
                'gambar' => null,
                'min_order' => 1,
            ],
            // ID 73
            [
                'kategori_id' => 1,
                'nama_produk' => 'aqua (botol mini)',
                'harga' => 2000,
                'deskripsi' => null,
                'is_snackbox_only' => 1,
                'gambar' => null,
                'min_order' => 1,
            ],
            // ID 74
            [
                'kategori_id' => 1,
                'nama_produk' => 'Le Minerale (botol mini)',
                'harga' => 2000,
                'deskripsi' => null,
                'is_snackbox_only' => 1,
                'gambar' => '1781069433_le-minerale-botol-mini.png',
                'min_order' => 1,
            ],
            // ID 75
            [
                'kategori_id' => 1,
                'nama_produk' => 'Cendol keju',
                'harga' => 2500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 76
            [
                'kategori_id' => 1,
                'nama_produk' => 'Putu mayang bihun',
                'harga' => 2500,
                'deskripsi' => 'Putu mayang berbentuk bihun dengan siraman sirup g...',
                'is_snackbox_only' => 0,
                'gambar' => '1782393180_putu-mayang-bihun.jpeg',
                'min_order' => 50,
            ],
            // ID 77
            [
                'kategori_id' => 1,
                'nama_produk' => 'Gethuk',
                'harga' => 2500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 78
            [
                'kategori_id' => 1,
                'nama_produk' => 'Tahu isi Sayur',
                'harga' => 2500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782393289_tahu-isi-sayur.jpeg',
                'min_order' => 50,
            ],
            // ID 79
            [
                'kategori_id' => 1,
                'nama_produk' => 'Pastry',
                'harga' => 4500,
                'deskripsi' => 'Pastry dengan isian sosis dan keju',
                'is_snackbox_only' => 0,
                'gambar' => '1782393940_pastry.jpeg',
                'min_order' => 50,
            ],
            // ID 80
            [
                'kategori_id' => 1,
                'nama_produk' => 'Wajik gula aren',
                'harga' => 4500,
                'deskripsi' => '4500',
                'is_snackbox_only' => 0,
                'gambar' => '1782394023_wajik-gula-aren.jpeg',
                'min_order' => 50,
            ],
            // ID 81
            [
                'kategori_id' => 1,
                'nama_produk' => 'Putri mandi isi 3',
                'harga' => 4500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782394098_putri-mandi-isi-3.jpeg',
                'min_order' => 50,
            ],
            // ID 82
            [
                'kategori_id' => 1,
                'nama_produk' => 'Soes marble',
                'harga' => 4500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782394278_soes-marble.jpeg',
                'min_order' => 50,
            ],
            // ID 83
            [
                'kategori_id' => 1,
                'nama_produk' => 'Soes vla vanila',
                'harga' => 4500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782394339_soes-vla-vanila.jpeg',
                'min_order' => 50,
            ],
            // ID 84
            [
                'kategori_id' => 1,
                'nama_produk' => 'Soes buah',
                'harga' => 4500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782394406_soes-buah.jpeg',
                'min_order' => 50,
            ],
            // ID 85
            [
                'kategori_id' => 1,
                'nama_produk' => 'Soes bunga',
                'harga' => 4500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782394447_soes-bunga.jpeg',
                'min_order' => 50,
            ],
            // ID 86
            [
                'kategori_id' => 1,
                'nama_produk' => 'Soes mayo',
                'harga' => 4500,
                'deskripsi' => 'Kue soes mayo dengan isian selada, sosis, dan telu...',
                'is_snackbox_only' => 0,
                'gambar' => '1782394559_soes-mayo.jpeg',
                'min_order' => 50,
            ],
            // ID 87
            [
                'kategori_id' => 1,
                'nama_produk' => 'Soes rougut ayam',
                'harga' => 4500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782394660_soes-rougut-ayam.jpeg',
                'min_order' => 50,
            ],
            // ID 88
            [
                'kategori_id' => 1,
                'nama_produk' => 'Pie buah',
                'harga' => 4500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782394761_pie-buah.jpeg',
                'min_order' => 50,
            ],
            // ID 89
            [
                'kategori_id' => 1,
                'nama_produk' => 'puding buah',
                'harga' => 4500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782394843_puding-buah.jpeg',
                'min_order' => 50,
            ],
            // ID 90
            [
                'kategori_id' => 1,
                'nama_produk' => 'Puding kelapa muda',
                'harga' => 4500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 91
            [
                'kategori_id' => 1,
                'nama_produk' => 'Makaroni schutle',
                'harga' => 4500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782402089_makaroni-schutle.jpeg',
                'min_order' => 50,
            ],
            // ID 92
            [
                'kategori_id' => 1,
                'nama_produk' => 'Brownies oven',
                'harga' => 4500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782396988_brownies-oven.jpeg',
                'min_order' => 50,
            ],
            // ID 93
            [
                'kategori_id' => 1,
                'nama_produk' => 'Kuro strawberry',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782464445_kuro-strawberry.jpeg',
                'min_order' => 50,
            ],
            // ID 94
            [
                'kategori_id' => 1,
                'nama_produk' => 'Kuro manggis',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782464505_kuro-manggis.jpeg',
                'min_order' => 50,
            ],
            // ID 95
            [
                'kategori_id' => 1,
                'nama_produk' => 'Kuro wortel',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782464557_kuro-wortel.jpeg',
                'min_order' => 50,
            ],
            // ID 96
            [
                'kategori_id' => 1,
                'nama_produk' => 'Kuro tomat',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782464655_kuro-tomat.jpeg',
                'min_order' => 50,
            ],
            // ID 97
            [
                'kategori_id' => 1,
                'nama_produk' => 'Bugis daun',
                'harga' => 3500,
                'deskripsi' => 'Kue bugis dengan isian parutan kelapa putih',
                'is_snackbox_only' => 0,
                'gambar' => '1782464742_bugis-daun.jpeg',
                'min_order' => 50,
            ],
            // ID 98
            [
                'kategori_id' => 1,
                'nama_produk' => 'Putri mandi isian kelapa putih',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782464834_putri-mandi-isi-1.jpeg',
                'min_order' => 50,
            ],
            // ID 99
            [
                'kategori_id' => 1,
                'nama_produk' => 'Lemper ayam',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782464887_lemper-ayam.jpeg',
                'min_order' => 50,
            ],
            // ID 100
            [
                'kategori_id' => 1,
                'nama_produk' => 'Lemper ayam bakar',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782464922_lemper-ayam-bakar.jpeg',
                'min_order' => 50,
            ],
            // ID 101
            [
                'kategori_id' => 1,
                'nama_produk' => 'Nagasari daun (pisang raja)',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782465010_nagasari-daun-pisang-raja.jpeg',
                'min_order' => 50,
            ],
            // ID 102
            [
                'kategori_id' => 1,
                'nama_produk' => 'Arem-arem mie',
                'harga' => 3500,
                'deskripsi' => 'arem-arem dengan bahan dasar mie yang diisi dengan...',
                'is_snackbox_only' => 0,
                'gambar' => '1782465105_arem-arem-mie.jpeg',
                'min_order' => 50,
            ],
            // ID 103
            [
                'kategori_id' => 1,
                'nama_produk' => 'Arem-arem nasi tahu jeroan',
                'harga' => 3500,
                'deskripsi' => 'Arem-arem yang bahan dasar nasi dengan isian samba...',
                'is_snackbox_only' => 0,
                'gambar' => '1782465225_arem-arem-nasi-tahu-jeroan.jpeg',
                'min_order' => 50,
            ],
            // ID 104
            [
                'kategori_id' => 1,
                'nama_produk' => 'Arem-arem nasi tahu udang',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782472332_arem-arem-nasi-tahu-udang.jpeg',
                'min_order' => 50,
            ],
            // ID 105
            [
                'kategori_id' => 1,
                'nama_produk' => 'Dadar gulung pisang coklat keju',
                'harga' => 3500,
                'deskripsi' => 'Dadar gulung dengan isian pisang raja yang dipaduk...',
                'is_snackbox_only' => 0,
                'gambar' => '1782465470_dadar-gulung-pisang-coklat-keju.jpeg',
                'min_order' => 50,
            ],
            // ID 106
            [
                'kategori_id' => 1,
                'nama_produk' => 'Dadar gulung hijau',
                'harga' => 3500,
                'deskripsi' => 'Dadar gulungdengan kulit berwarna hijau dengan isi...',
                'is_snackbox_only' => 0,
                'gambar' => null,
                'min_order' => 50,
            ],
            // ID 107
            [
                'kategori_id' => 1,
                'nama_produk' => 'Bolu gulung selai nanas',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782465656_bolu-gulung-selai-nanas.jpeg',
                'min_order' => 50,
            ],
            // ID 108
            [
                'kategori_id' => 1,
                'nama_produk' => 'Bolu gulung selai strawberry',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782465717_bolu-gulung-selai-strawberry.jpeg',
                'min_order' => 50,
            ],
            // ID 109
            [
                'kategori_id' => 1,
                'nama_produk' => 'Bolu kukus',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782465843_bolu-kukus.jpeg',
                'min_order' => 50,
            ],
            // ID 110
            [
                'kategori_id' => 1,
                'nama_produk' => 'Tahu bakso',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782465891_tahu-bakso.jpeg',
                'min_order' => 50,
            ],
            // ID 111
            [
                'kategori_id' => 1,
                'nama_produk' => 'Wingko',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782465981_wingko.jpeg',
                'min_order' => 50,
            ],
            // ID 112
            [
                'kategori_id' => 1,
                'nama_produk' => 'Klepon',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782466058_klepon.jpeg',
                'min_order' => 50,
            ],
            // ID 113
            [
                'kategori_id' => 1,
                'nama_produk' => 'Lapis',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782466112_lapis.jpeg',
                'min_order' => 50,
            ],
            // ID 114
            [
                'kategori_id' => 1,
                'nama_produk' => 'Moachi kacang',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782466199_moachi-kacang.jpeg',
                'min_order' => 50,
            ],
            // ID 115
            [
                'kategori_id' => 1,
                'nama_produk' => 'Prol tape',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782466272_prol-tape.jpeg',
                'min_order' => 50,
            ],
            // ID 116
            [
                'kategori_id' => 1,
                'nama_produk' => 'Semar mendem',
                'harga' => 3500,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782466318_semar-mendem.jpeg',
                'min_order' => 50,
            ],
            // ID 117
            [
                'kategori_id' => 1,
                'nama_produk' => 'Bolen pisang raja rasa keju',
                'harga' => 5000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782467896_bolen-pisang-raja-rasa-keju.jpeg',
                'min_order' => 50,
            ],
            // ID 118
            [
                'kategori_id' => 1,
                'nama_produk' => 'Wajik pelangi',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782469189_wajik-pelangi.jpeg',
                'min_order' => 50,
            ],
            // ID 119
            [
                'kategori_id' => 1,
                'nama_produk' => 'Ketan salak gula aren',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782469243_ketan-salak-gula-aren.jpeg',
                'min_order' => 50,
            ],
            // ID 120
            [
                'kategori_id' => 1,
                'nama_produk' => 'Ladu',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782469297_ladu.jpeg',
                'min_order' => 50,
            ],
            // ID 121
            [
                'kategori_id' => 1,
                'nama_produk' => 'Gemblong',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782469369_gemblong.jpeg',
                'min_order' => 50,
            ],
            // ID 122
            [
                'kategori_id' => 1,
                'nama_produk' => 'Bugis daun kelapa gula aren jahe',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782469587_bugis-daun-kelapa-gula-aren-jahe.jpeg',
                'min_order' => 50,
            ],
            // ID 123
            [
                'kategori_id' => 1,
                'nama_produk' => 'Putri mandi isi kelapa gula aren jahe',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782471360_putri-mandi-isi-kelapa-gula-aren-jahe.jpeg',
                'min_order' => 50,
            ],
            // ID 124
            [
                'kategori_id' => 1,
                'nama_produk' => 'Putri mandi 3 biji isi kelapa putih',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782471437_putri-mandi-isi-kelapa-putih-3.jpeg',
                'min_order' => 50,
            ],
            // ID 125
            [
                'kategori_id' => 1,
                'nama_produk' => 'bolu gulung keju parut & cream',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782471504_bolu-gulung-keju-parut-cream.jpeg',
                'min_order' => 50,
            ],
            // ID 126
            [
                'kategori_id' => 1,
                'nama_produk' => 'Dimsum ayam jamur',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782471652_dimsum-ayam-jamur.jpeg',
                'min_order' => 50,
            ],
            // ID 127
            [
                'kategori_id' => 1,
                'nama_produk' => 'Samosa ayam',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782471699_samosa-ayam.jpeg',
                'min_order' => 50,
            ],
            // ID 128
            [
                'kategori_id' => 1,
                'nama_produk' => 'Kroket kentang',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782471750_kroket-kentang.jpeg',
                'min_order' => 50,
            ],
            // ID 129
            [
                'kategori_id' => 1,
                'nama_produk' => 'Pastel',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782471797_pastel.jpeg',
                'min_order' => 50,
            ],
            // ID 130
            [
                'kategori_id' => 1,
                'nama_produk' => 'Bolu marmer',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782471836_bolu-marmer.jpeg',
                'min_order' => 50,
            ],
            // ID 131
            [
                'kategori_id' => 1,
                'nama_produk' => 'risoles rougut ayam',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782471951_risoles-rougut-ayam.jpeg',
                'min_order' => 50,
            ],
            // ID 132
            [
                'kategori_id' => 1,
                'nama_produk' => 'risoles mayo sosis telur',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782472015_risoles-mayo-sosis-telur.jpeg',
                'min_order' => 50,
            ],
            // ID 133
            [
                'kategori_id' => 1,
                'nama_produk' => 'Arem-arem nasi sambal goreng kentang jeroan',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782472083_arem-arem-nasi-sambal-goreng-kentang-jeroan.jpeg',
                'min_order' => 50,
            ],
            // ID 134
            [
                'kategori_id' => 1,
                'nama_produk' => 'Kue lumpur kentang',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782472139_kue-lumpur-kentang.jpeg',
                'min_order' => 50,
            ],
            // ID 135
            [
                'kategori_id' => 1,
                'nama_produk' => 'Puding ceplok',
                'harga' => 4000,
                'deskripsi' => null,
                'is_snackbox_only' => 0,
                'gambar' => '1782472227_puding-ceplok.jpeg',
                'min_order' => 50,
            ],
        ];
        foreach ($produk as $item) {
            DB::table('produk')->insert($item);
        }
    }
}