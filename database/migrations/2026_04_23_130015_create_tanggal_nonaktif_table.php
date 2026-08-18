<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tanggal_nonaktif', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
             $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanggal_nonaktif');
    }
};