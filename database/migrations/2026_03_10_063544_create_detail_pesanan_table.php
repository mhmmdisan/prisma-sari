<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            
            // Hanya salah satu yang terisi
            $table->foreignId('produk_id')->nullable()->constrained('produk')->onDelete('set null');
            $table->foreignId('custom_snackbox_id')->nullable()->constrained('custom_snackbox')->onDelete('set null');
            
            $table->string('nama_item', 200);    
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_produk')->onDelete('set null');                
            $table->integer('jumlah');                            
            $table->integer('harga_satuan');                      
            $table->integer('subtotal');                          
            $table->text('catatan')->nullable();                 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};