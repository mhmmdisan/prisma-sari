<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MetodePembayaranController extends Controller
{
    /**
     * Tampilkan daftar metode pembayaran
     */
    public function index()
    {
        $metodePembayaran = MetodePembayaran::orderBy('status_aktif', 'desc')
            ->orderBy('nama_bank', 'asc')
            ->get();
        
        return view('admin.metode-pembayaran.index', compact('metodePembayaran'));
    }
    
    /**
     * Form tambah metode pembayaran
     */
    public function create()
    {
        return view('admin.metode-pembayaran.create');
    }
    
    /**
     * Simpan metode pembayaran baru - AJAX RESPONSE JSON
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_bank' => 'required|string|max:50',
                'nomor_rekening' => 'required|string|max:50',
                'atas_nama' => 'required|string|max:100',
                'logo_bank' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status_aktif' => 'nullable|boolean',
            ]);
            
            $data = [
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'atas_nama' => $request->atas_nama,
                'status_aktif' => $request->has('status_aktif'),
            ];
            
            if ($request->hasFile('logo_bank')) {
                $file = $request->file('logo_bank');
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $destinationPath = public_path('storage/bank');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                $file->move($destinationPath, $filename);
                $data['logo_bank'] = $filename;
            }
            
            MetodePembayaran::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Metode pembayaran berhasil ditambahkan!',
                'redirect' => route('admin.metode-pembayaran.index')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Form edit metode pembayaran
     */
    public function edit($id)
    {
        $metode = MetodePembayaran::findOrFail($id);
        return view('admin.metode-pembayaran.edit', compact('metode'));
    }
    
    /**
     * Update metode pembayaran - AJAX RESPONSE JSON
     */
    public function update(Request $request, $id)
    {
        try {
            $metode = MetodePembayaran::findOrFail($id);
            
            $request->validate([
                'nama_bank' => 'required|string|max:50',
                'nomor_rekening' => 'required|string|max:50',
                'atas_nama' => 'required|string|max:100',
                'logo_bank' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status_aktif' => 'nullable|boolean',
            ]);
            
            $data = [
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'atas_nama' => $request->atas_nama,
                'status_aktif' => $request->has('status_aktif'),
            ];
            
            if ($request->hasFile('logo_bank')) {
                // Hapus logo lama
                if ($metode->logo_bank && file_exists(public_path('storage/bank/' . $metode->logo_bank))) {
                    @unlink(public_path('storage/bank/' . $metode->logo_bank));
                }
                
                $file = $request->file('logo_bank');
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $destinationPath = public_path('storage/bank');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                $file->move($destinationPath, $filename);
                $data['logo_bank'] = $filename;
            }
            
            $metode->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Metode pembayaran berhasil diperbarui!',
                'redirect' => route('admin.metode-pembayaran.index')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Hapus metode pembayaran - AJAX RESPONSE JSON
     */
    public function destroy($id)
    {
        try {
            $metode = MetodePembayaran::findOrFail($id);
            
            // Hapus file logo jika ada
            if ($metode->logo_bank && file_exists(public_path('storage/bank/' . $metode->logo_bank))) {
                @unlink(public_path('storage/bank/' . $metode->logo_bank));
            }
            
            $metode->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Metode pembayaran berhasil dihapus!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update status aktif/nonaktif - AJAX RESPONSE JSON
     */
    public function toggleStatus($id)
    {
        try {
            $metode = MetodePembayaran::findOrFail($id);
            $metode->status_aktif = !$metode->status_aktif;
            $metode->save();
            
            $statusText = $metode->status_aktif ? 'diaktifkan' : 'dinonaktifkan';
            
            return response()->json([
                'success' => true,
                'status' => $metode->status_aktif,
                'message' => "Metode pembayaran berhasil {$statusText}!"
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status!'
            ], 500);
        }
    }
}