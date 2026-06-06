<?php
// database/migrations/2026_03_10_063451_create_keranjang_detail_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keranjang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Hanya salah satu yang terisi (nullable)
            $table->foreignId('produk_id')->nullable()->constrained('produk')->onDelete('cascade');
            $table->foreignId('custom_snackbox_id')->nullable()->constrained('custom_snackbox')->onDelete('cascade');
            
            $table->integer('jumlah'); // jumlah item (bisa box atau produk)
            $table->integer('harga'); // harga per item
            $table->integer('subtotal'); // jumlah × harga
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keranjang_detail');
    }
};