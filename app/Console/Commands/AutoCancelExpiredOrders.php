<?php

namespace App\Console\Commands;

use App\Models\Pesanan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoCancelExpiredOrders extends Command
{
    protected $signature = 'orders:auto-cancel';
    protected $description = 'Batalkan otomatis pesanan yang melewati batas waktu pembayaran';

    public function handle()
    {
        // Cari pesanan yang sudah expired
        $expiredOrders = Pesanan::where('status', 'menunggu_pembayaran')
            ->where('status_pembayaran', 'belum_bayar')
            ->where('expired_at', '<', now())
            ->get();

        $count = 0;

        foreach ($expiredOrders as $pesanan) {
            try {
                // Ubah status menjadi dibatalkan
                $pesanan->status = 'dibatalkan';
                $pesanan->save();

                $count++;
                
                // Log ke file
                Log::info("Pesanan {$pesanan->nomor_pesanan} otomatis dibatalkan karena telah melewati masa pembayaran");
                
                // Tampilkan di terminal
                $this->info("✓ Pesanan {$pesanan->nomor_pesanan} dibatalkan");
                
            } catch (\Exception $e) {
                Log::error("Gagal membatalkan pesanan {$pesanan->id}: " . $e->getMessage());
                $this->error("✗ Gagal membatalkan pesanan {$pesanan->nomor_pesanan}");
            }
        }

        // Tampilkan ringkasan
        if ($count > 0) {
            $this->info("✅ Berhasil membatalkan {$count} pesanan expired.");
        } else {
            $this->info("ℹ️ Tidak ada pesanan expired.");
        }

        return Command::SUCCESS;
    }
}