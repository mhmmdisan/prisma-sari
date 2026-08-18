<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nomor_pesanan', 50)->unique();
            $table->dateTime('tanggal_pesanan');
            $table->dateTime('expired_at')->nullable();
            $table->dateTime('tanggal_pengambilan');
            $table->text('alamat_pengiriman');
            $table->integer('total_harga');
            
            //FIELD PEMBAYARAN 
            $table->foreignId('id_metode_pembayaran')->nullable()
                  ->constrained('metode_pembayaran')->onDelete('set null');
            $table->string('bukti_pembayaran')->nullable();
            $table->datetime('tanggal_bayar')->nullable();
            $table->enum('status_pembayaran', ['belum_bayar', 'menunggu_konfirmasi', 'lunas'])
                  ->default('belum_bayar');
            $table->text('catatan_pesanan')->nullable();
            
             $table->boolean('is_whatsapp_order')->default(false);
             
            $table->enum('status', [
                'menunggu_pembayaran',
                'diproses',
                'selesai',
                'dibatalkan'
            ])->default('menunggu_pembayaran');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};