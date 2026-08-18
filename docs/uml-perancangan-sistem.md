# Dokumentasi Perancangan Sistem (UML)

## 1. Tujuan
Dokumentasi ini menjelaskan perancangan sistem aplikasi pemesanan snackbox, produk, dan manajemen pesanan menggunakan format UML. Fokusnya pada struktur domain, hubungan antar entitas, dan alur proses utama.

## 2. Aktor Utama
- Pelanggan
- Admin
- Pemilik

## 3. Use Case Utama
1. Pelanggan:
   - Lihat produk
   - Buat custom snackbox
   - Tambah produk ke keranjang
   - Checkout pesanan
   - Upload bukti pembayaran
   - Lihat dan batalkan pesanan

2. Admin:
   - Kelola produk
   - Kelola metode pembayaran
   - Kelola akun
   - Verifikasi pembayaran
   - Kelola pesanan
   - Kelola tanggal nonaktif
   - Kelola jadwal produksi

3. Pemilik:
   - Melihat laporan penjualan
   - Melihat jadwal produksi

## 4. Class Diagram (Model Domain)
Berikut adalah diagram kelas UML dalam format PlantUML. Diagram ini menggambarkan entitas utama dan relasinya.

```plantuml
@startuml

class User {
  +id: int
  +name: string
  +email: string
  +password: string
  +role: string
  +no_telepon: string
  +alamat: string
  +foto_profil: string
}

class Produk {
  +id: int
  +kategori_id: int
  +nama_produk: string
  +harga: int
  +deskripsi: text
  +gambar: string
  +min_order: int
  +is_snackbox_only: bool
}

class KategoriProduk {
  +id: int
  +nama_kategori: string
}

class MetodePembayaran {
  +id: int
  +nama_bank: string
  +nomor_rekening: string
  +atas_nama: string
  +cabang: string
  +status_aktif: bool
  +logo_bank: string
}

class Pesanan {
  +id: int
  +nomor_pesanan: string
  +user_id: int
  +tanggal_pesanan: datetime
  +expired_at: datetime
  +tanggal_pengambilan: datetime
  +alamat_pengiriman: string
  +total_harga: int
  +status: string
  +status_pembayaran: string
  +id_metode_pembayaran: int
  +bukti_pembayaran: string
  +tanggal_bayar: datetime
  +catatan_pesanan: text
  +is_whatsapp_order: bool
}

class DetailPesanan {
  +id: int
  +pesanan_id: int
  +produk_id: int
  +custom_snackbox_id: int
  +nama_item: string
  +kategori_id: int
  +jumlah: int
  +harga_satuan: int
  +subtotal: int
  +catatan: text
}

class KeranjangDetail {
  +id: int
  +user_id: int
  +produk_id: int
  +custom_snackbox_id: int
  +jumlah: int
  +harga: int
  +subtotal: int
}

class CustomSnackbox {
  +id: int
  +user_id: int
  +kode_ukuran: string
  +jumlah_item: int
  +nama_box: string
  +total_item: int
  +jumlah_box: int
  +harga_per_box: int
  +harga_total: int
}

class CustomSnackboxDetail {
  +id: int
  +custom_snackbox_id: int
  +produk_id: int
  +jumlah: int
  +subtotal: int
}

class JadwalProduksi {
  +id: int
  +pesanan_id: int
  +tanggal_produksi: date
  +jam_mulai: time
  +jam_selesai: time
  +urutan: int
  +status: string
}

User "1" -- "*" KeranjangDetail : memiliki
User "1" -- "*" CustomSnackbox : membuat
User "1" -- "*" Pesanan : membuat
Produk "1" -- "*" KeranjangDetail : ditambahkan
Produk "1" -- "*" CustomSnackboxDetail : isi
Produk "1" -- "*" DetailPesanan : item
KategoriProduk "1" -- "*" Produk : mengelompokkan
Pesanan "1" -- "*" DetailPesanan : berisi
Pesanan "1" -- "1" MetodePembayaran : menggunakan
Pesanan "1" -- "*" JadwalProduksi : dijadwalkan
CustomSnackbox "1" -- "*" CustomSnackboxDetail : berisi
CustomSnackbox "1" -- "1" KeranjangDetail : ditempatkan
CustomSnackbox "1" -- "*" DetailPesanan : termasuk

@enduml
```

### 4.1 Penjelasan Relasi
- `User` memiliki banyak `KeranjangDetail`, `CustomSnackbox`, dan `Pesanan`.
- `Produk` dikategorikan oleh `KategoriProduk` dan bisa muncul dalam keranjang, custom snackbox, dan detail pesanan.
- `Pesanan` menggunakan `MetodePembayaran`, memiliki banyak `DetailPesanan`, dan dapat dijadwalkan di `JadwalProduksi`.
- `CustomSnackbox` memuat banyak `CustomSnackboxDetail` dan dapat ditempatkan di `KeranjangDetail` atau `DetailPesanan`.

## 5. Sequence Diagram: Proses Checkout Pelanggan

```plantuml
@startuml
actor Pelanggan
participant "Produk / CustomSnackbox" as Item
participant Keranjang
participant Pesanan
participant MetodePembayaran

Pelanggan -> Item : Pilih produk / buat custom snackbox
Item -> Keranjang : Tambah item
Keranjang -> Pelanggan : Tampilkan ringkasan
Pelanggan -> Pesanan : Checkout
Pesanan -> MetodePembayaran : Pilih metode pembayaran
Pesanan --> Pelanggan : Buat pesanan dengan status menunggu_pembayaran
Pelanggan -> MetodePembayaran : Upload bukti pembayaran
MetodePembayaran -> Pesanan : Update status_pembayaran
Pesanan --> Pelanggan : Konfirmasi pesanan
@enduml
```

## 6. Sequence Diagram: Proses Admin Verifikasi dan Jadwal Produksi

```plantuml
@startuml
actor Admin
participant "Pesanan" as Pesanan
participant "Pembayaran" as Pembayaran
participant "JadwalProduksi" as Jadwal

Admin -> Pesanan : Lihat pesanan menunggu konfirmasi
Admin -> Pembayaran : Verifikasi bukti
Pembayaran -> Pesanan : Update status_pembayaran => lunas
Pesanan -> Jadwal : Buat / perbarui jadwal produksi
Jadwal --> Admin : Tampilkan status jadwal
@enduml
```

## 6.1 Use Case Diagram: Aktor dan Use Case Utama

```plantuml
@startuml
left to right direction
actor Pelanggan
actor Admin
actor Pemilik

Pelanggan --> (Lihat Produk)
Pelanggan --> (Buat Custom Snackbox)
Pelanggan --> (Tambah ke Keranjang)
Pelanggan --> (Checkout Pesanan)
Pelanggan --> (Upload Bukti Pembayaran)
Pelanggan --> (Lihat Pesanan)
Pelanggan --> (Batalkan Pesanan)

Admin --> (Kelola Produk)
Admin --> (Kelola Metode Pembayaran)
Admin --> (Kelola Akun)
Admin --> (Verifikasi Pembayaran)
Admin --> (Kelola Pesanan)
Admin --> (Kelola Tanggal Nonaktif)
Admin --> (Kelola Jadwal Produksi)

Pemilik --> (Lihat Laporan)
Pemilik --> (Lihat Jadwal Produksi)
@enduml
```

## 7. Alur Sistem Lengkap

### 7.1 Autentikasi dan Akses Role-Based
1. Pengguna mengakses halaman login atau registrasi.
2. Setelah login, sistem memeriksa `role` pengguna.
3. Jika `pelanggan`, diarahkan ke `pelanggan.dashboard`.
4. Jika `admin`, diarahkan ke `admin.dashboard`.
5. Jika `pemilik`, diarahkan ke `pemilik.dashboard`.
6. Semua route khusus role dilindungi oleh middleware `auth` dan `role:<role>`.

### 7.2 Alur Pelanggan
1. Pelanggan melihat daftar produk yang tersedia dan dapat memfilter berdasarkan kategori.
2. Pelanggan memilih produk biasa atau membuat custom snackbox.
3. Untuk custom snackbox:
   - Pelanggan memilih ukuran, jumlah item, dan nama box.
   - Sistem menyimpan `CustomSnackbox` dan detail snackbox (`CustomSnackboxDetail`).
   - Pelanggan dapat mengedit atau menghapus custom snackbox sebelum checkout.
4. Pelanggan menambahkan produk atau custom snackbox ke keranjang (`KeranjangDetail`).
5. Pelanggan dapat memperbarui jumlah, menghapus item, atau mengosongkan keranjang.
6. Saat checkout, sistem membuat `Pesanan` baru, menghitung `total_harga`, dan menempatkan item keranjang ke `DetailPesanan`.
7. Pesanan awal memiliki status `menunggu_pembayaran` dan `status_pembayaran` `belum_bayar`.
8. Pelanggan memilih `MetodePembayaran` aktif dan mengunggah bukti pembayaran.
9. Setelah bukti diunggah, status pembayaran berubah menjadi `menunggu_konfirmasi`.
10. Pelanggan dapat melihat detail pesanan, status pembayaran, dan membatalkan pesanan bila memenuhi aturan.

### 7.3 Alur Admin
1. Admin masuk ke dashboard admin.
2. Admin mengelola data produk: membuat, membaca, memperbarui, menghapus.
3. Admin mengelola metode pembayaran, termasuk menonaktifkan atau mengaktifkan metode.
4. Admin mengelola akun pengguna (admin, pelanggan, pemilik), termasuk reset password.
5. Admin mengatur tanggal nonaktif di mana pemesanan tidak bisa dipilih.
6. Admin meninjau pesanan yang menunggu konfirmasi pembayaran.
7. Admin memverifikasi bukti pembayaran dan memperbarui `Pesanan` menjadi `lunas` jika valid.
8. Setelah verifikasi, admin membuat atau memperbarui entri `JadwalProduksi` untuk pesanan tersebut.
9. Admin dapat memonitor status produksi pesanan `menunggu`, `produksi`, `selesai`.

### 7.4 Alur Pemilik
1. Pemilik melihat ringkasan laporan penjualan berdasarkan rentang tanggal atau kategori.
2. Pemilik meninjau jadwal produksi untuk melihat jumlah pesanan dan status produksi.
3. Pemilik dapat menggunakan informasi ini untuk keputusan operasional dan stok.

## 7.5 Activity Diagram: Proses Custom Snackbox Pelanggan

```plantuml
@startuml
start
:Masuk ke halaman custom snackbox;
if (Pilih ukuran?) then (ya)
  :Pilih ukuran dan jumlah item;
  if (Tambah item?) then (ya)
    :Pilih produk snackbox;
    :Tambahkan ke detail snackbox;
    -> [loop] if (Masih ingin tambah item?) then (ya)
  endif
endif
:Isi nama box (opsional);
if (Simpan custom snackbox?) then (ya)
  :Simpan CustomSnackbox dan detail;
  :Tampilkan ringkasan snackbox;
else (tidak)
  :Batalkan / kembali;
endif
stop
@enduml
```

## 7.6 Activity Diagram: Proses Verifikasi Pembayaran Admin

```plantuml
@startuml
start
:Admin membuka daftar pesanan menunggu konfirmasi;
if (Bukti pembayaran ada?) then (ya)
  :Tinjau bukti pembayaran;
  if (Valid?) then (ya)
    :Ubah status_pembayaran menjadi lunas;
    :Buat atau perbarui JadwalProduksi;
    :Kirim notifikasi sukses;
  else (tidak)
    :Tolak / minta bukti ulang;
  endif
else (tidak)
  :Tunggu bukti pembayaran;
endif
stop
@enduml
```

## 8. Komponen Sistem dan Alur Utama
1. Frontend menampilkan daftar produk, detail produk, custom snackbox, keranjang, dan halaman pesanan.
2. Backend Laravel menangani route role-based untuk `pelanggan`, `admin`, dan `pemilik`.
3. Model Eloquent mencerminkan entitas dan aturan relasi database.
4. Process flow penambahan ke keranjang -> checkout -> pembayaran -> konfirmasi -> produksi.

## 9. Rekomendasi Dokumentasi Lanjutan
- Tambahkan class diagram controller jika ingin memperlihatkan alur logika aplikasi.
- Tambahkan activity diagram untuk proses pembuatan custom snackbox dan pengelolaan tanggal nonaktif.
- Buat use case diagram terpisah jika perlu presentasi ke stakeholder.
