<?php
// database/migrations/2026_03_10_062049_create_custom_snackbox_detail_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_snackbox_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_snackbox_id')->constrained('custom_snackbox')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->integer('jumlah');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_snackbox_detail');
    }
};