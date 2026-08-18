<?php
// database/migrations/2026_03_10_061654_create_produk_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_produk')->onDelete('cascade');
            $table->string('nama_produk');
            $table->integer('harga');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->integer('min_order')->default(1);
            $table->boolean('is_snackbox_only')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};