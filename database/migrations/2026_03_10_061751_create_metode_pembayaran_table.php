<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metode_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->string('atas_nama');
            $table->string('cabang')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->string('logo_bank')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metode_pembayaran');
    }
};