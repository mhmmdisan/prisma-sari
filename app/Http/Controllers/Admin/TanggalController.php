<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TanggalNonaktif; // 🔥 TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TanggalController extends Controller
{
    /**
     * Display a listing of the resource (daftar tanggal nonaktif).
     */
    public function index()
    {
        // 🔥 UBAH dari DB::table() menjadi Eloquent dengan relasi
        $tanggalNonaktif = TanggalNonaktif::with('createdBy') // eager loading relasi
            ->orderBy('tanggal', 'desc')
            ->paginate(15);
        
        return view('admin.tanggal', compact('tanggalNonaktif'));
    }

    /**
     * Store a newly created resource in storage (tambah tanggal nonaktif) - AJAX RESPONSE JSON
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date|after:today',
                'keterangan' => 'nullable|string|max:255'
            ]);
            
            // Cek apakah tanggal sudah ada
            $exists = DB::table('tanggal_nonaktif')
                ->where('tanggal', $request->tanggal)
                ->exists();
            
            if ($exists) {
                // Jika sudah ada, update status menjadi 'nonaktif' dan created_by (jika null)
                DB::table('tanggal_nonaktif')
                    ->where('tanggal', $request->tanggal)
                    ->update([
                        'status' => 'nonaktif',
                        'keterangan' => $request->keterangan,
                        'updated_at' => now(),
                        'created_by' => DB::raw('COALESCE(created_by, ' . auth()->id() . ')'),
                    ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Tanggal berhasil dinonaktifkan!',
                    'redirect' => route('admin.tanggal.index')
                ]);
            }
            
            // Jika belum ada, tambah baru dengan status 'nonaktif' dan created_by
            DB::table('tanggal_nonaktif')->insert([
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'status' => 'nonaktif',
                'created_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Tanggal berhasil dinonaktifkan!',
                'redirect' => route('admin.tanggal.index')
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_map(fn($v) => implode(', ', $v), $e->errors()))
            ], 422);
        } catch (\Exception $e) {
            Log::error('Store tanggal error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menonaktifkan tanggal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aktifkan tanggal (ubah status menjadi 'aktif') - AJAX RESPONSE JSON
     */
    public function aktifkan($id)
    {
        try {
            $tanggal = DB::table('tanggal_nonaktif')->where('id', $id)->first();
            
            if (!$tanggal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tanggal tidak ditemukan!'
                ], 404);
            }
            
            DB::table('tanggal_nonaktif')
                ->where('id', $id)
                ->update([
                    'status' => 'aktif',
                    'updated_at' => now(),
                ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Tanggal berhasil diaktifkan!',
                'redirect' => route('admin.tanggal.index')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Aktifkan tanggal error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan tanggal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Nonaktifkan tanggal (ubah status menjadi 'nonaktif') - AJAX RESPONSE JSON
     */
    public function nonaktifkan(Request $request, $id)
    {
        try {
            $data = [
                'status' => 'nonaktif',
                'updated_at' => now(),
            ];
            
            if ($request->has('keterangan')) {
                $data['keterangan'] = $request->keterangan;
            }
            
            // 🔥 TAMBAHKAN: jika created_by null, isi dengan admin yang melakukan aksi
            $data['created_by'] = DB::raw('COALESCE(created_by, ' . auth()->id() . ')');
            
            DB::table('tanggal_nonaktif')
                ->where('id', $id)
                ->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Tanggal berhasil dinonaktifkan!',
                'redirect' => route('admin.tanggal.index')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Nonaktifkan tanggal error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menonaktifkan tanggal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage (hapus permanen tanggal) - AJAX RESPONSE JSON
     */
    public function destroy($id)
    {
        try {
            DB::table('tanggal_nonaktif')->where('id', $id)->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Tanggal berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tanggal: ' . $e->getMessage()
            ], 500);
        }
    }
}