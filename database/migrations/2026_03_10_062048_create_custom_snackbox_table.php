<?php
// database/migrations/2026_03_10_062048_create_custom_snackbox_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_snackbox', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Ukuran langsung disimpan di sini (tanpa tabel terpisah)
            $table->enum('kode_ukuran', ['A', 'B', 'C','D','E','F'])->default('B');
            $table->integer('jumlah_item'); // 5, 7, atau 10 sesuai kode_ukuran
            $table->string('nama_box')->nullable();
            $table->integer('total_item'); // total item dalam 1 box (sama dengan jumlah_item)
            $table->integer('jumlah_box'); // jumlah box dipesan (min 50)
            $table->integer('harga_per_box');
            $table->integer('harga_total');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_snackbox');
    }
};