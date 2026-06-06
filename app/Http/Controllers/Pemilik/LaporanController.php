<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'harian');
        $tanggalMulai = $request->get('tanggal_mulai', date('Y-m-d'));
        $tanggalSelesai = $request->get('tanggal_selesai', date('Y-m-d'));
        $bulan = $request->get('bulan', date('Y-m'));
        
        $query = Pesanan::with('user')->where('status', 'selesai');
        
        switch ($periode) {
            case 'harian':
                $query->whereDate('created_at', $tanggalMulai);
                $judul = 'Laporan Penjualan Harian - ' . date('d/m/Y', strtotime($tanggalMulai));
                break;
            case 'mingguan':
                $query->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai]);
                $judul = 'Laporan Penjualan Mingguan - ' . date('d/m/Y', strtotime($tanggalMulai)) . ' s/d ' . date('d/m/Y', strtotime($tanggalSelesai));
                break;
            case 'bulanan':
                $query->where('created_at', 'like', $bulan . '%');
                $judul = 'Laporan Penjualan Bulanan - ' . date('F Y', strtotime($bulan . '-01'));
                break;
            default:
                $query->whereDate('created_at', $tanggalMulai);
                $judul = 'Laporan Penjualan';
        }
        
        $pesanan = $query->orderBy('created_at', 'desc')->get();
        $totalPendapatan = $pesanan->sum('total_harga');
        $totalPesanan = $pesanan->count();
        
        // Produk terlaris (dari detail pesanan yang lunas)
        $produkTerlaris = DetailPesanan::select('nama_item', DB::raw('SUM(jumlah) as total_jumlah'))
            ->whereIn('pesanan_id', $pesanan->pluck('id'))
            ->groupBy('nama_item')
            ->orderBy('total_jumlah', 'desc')
            ->limit(10)
            ->get();
        
        return view('pemilik.laporan', compact(
            'pesanan', 'totalPendapatan', 'totalPesanan',
            'produkTerlaris', 'judul', 'periode',
            'tanggalMulai', 'tanggalSelesai', 'bulan'
        ));
    }
    
    public function export(Request $request)
    {
        $periode = $request->get('periode', 'harian');
        $tanggalMulai = $request->get('tanggal_mulai', date('Y-m-d'));
        $tanggalSelesai = $request->get('tanggal_selesai', date('Y-m-d'));
        $bulan = $request->get('bulan', date('Y-m'));
        
        $query = Pesanan::with('user')->where('status', 'selesai');
        
        switch ($periode) {
            case 'harian':
                $query->whereDate('created_at', $tanggalMulai);
                $filename = 'laporan_harian_' . $tanggalMulai . '.xlsx';
                break;
            case 'mingguan':
                $query->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai]);
                $filename = 'laporan_mingguan_' . $tanggalMulai . '_sampai_' . $tanggalSelesai . '.xlsx';
                break;
            case 'bulanan':
                $query->where('created_at', 'like', $bulan . '%');
                $filename = 'laporan_bulanan_' . $bulan . '.xlsx';
                break;
            default:
                $query->whereDate('created_at', $tanggalMulai);
                $filename = 'laporan_penjualan.xlsx';
        }
        
        $pesanan = $query->orderBy('created_at', 'desc')->get();
        
        // Buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Sub header
        $sheet->setCellValue('A2', 'Periode: ' . date('d/m/Y', strtotime($tanggalMulai)) . ' - ' . date('d/m/Y', strtotime($tanggalSelesai)));
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Header tabel
        $headers = ['No', 'Tanggal', 'Nomor Pesanan', 'Pelanggan', 'Total', 'Status', 'Tanggal Ambil'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $sheet->getStyle($col . '4')->getFont()->setBold(true);
            $sheet->getStyle($col . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE0E0E0');
            $col++;
        }
        
        // Data
        $row = 5;
        $no = 1;
        foreach ($pesanan as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, date('d/m/Y H:i', strtotime($item->created_at)));
            $sheet->setCellValue('C' . $row, $item->nomor_pesanan);
            $sheet->setCellValue('D' . $row, $item->user->name ?? '-');
            $sheet->setCellValue('E' . $row, $item->total_harga);
            $sheet->setCellValue('F' . $row, ucfirst($item->status));
            $sheet->setCellValue('G' . $row, date('d/m/Y', strtotime($item->tanggal_pengambilan)));
            $row++;
        }
        
        // Total
        $sheet->setCellValue('D' . $row, 'TOTAL:');
        $sheet->setCellValue('E' . $row, $pesanan->sum('total_harga'));
        $sheet->getStyle('D' . $row . ':E' . $row)->getFont()->setBold(true);
        
        // Auto size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set headers untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}