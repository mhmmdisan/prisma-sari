<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class KelolaAkunController extends Controller
{
    /**
     * Daftar semua akun (admin, pelanggan, pemilik)
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filter berdasarkan role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        
        // Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderByRaw("FIELD(role, 'admin', 'pelanggan', 'pemilik')")
            ->orderBy('name')
            ->paginate(15);
        
        // Statistik
        $totalAdmin = User::where('role', 'admin')->count();
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalPemilik = User::where('role', 'pemilik')->count();
        
        return view('admin.kelola-akun.index', compact('users', 'totalAdmin', 'totalPelanggan', 'totalPemilik'));
    }
    
    /**
     * Form tambah akun
     */
    public function create()
    {
        return view('admin.kelola-akun.create');
    }
    
    /**
     * Simpan akun baru - AJAX RESPONSE JSON
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'role' => 'required|in:admin,pelanggan,pemilik',
                'no_telepon' => 'nullable|string|max:20',
                'alamat' => 'nullable|string',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Akun "' . $user->name . '" berhasil ditambahkan!',
                'redirect' => route('admin.kelola-akun.index')
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_map(fn($v) => implode(', ', $v), $e->errors()))
            ], 422);
        } catch (\Exception $e) {
            Log::error('Tambah akun error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan akun: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Form edit akun
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.kelola-akun.edit', compact('user'));
    }
    
    /**
     * Update akun (tanpa password) - AJAX RESPONSE JSON
     */
    public function update(Request $request, $id)
{
    try {
        // Handle method spoofing untuk PUT
        if ($request->has('_method') && $request->_method == 'PUT') {
            // Method sudah dihandle oleh route
        }
        
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,pelanggan,pemilik',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Akun "' . $user->name . '" berhasil diperbarui!',
            'redirect' => route('admin.kelola-akun.index')
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal: ' . implode(', ', array_map(fn($v) => implode(', ', $v), $e->errors()))
        ], 422);
    } catch (\Exception $e) {
        Log::error('Update akun error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui akun: ' . $e->getMessage()
        ], 500);
    }
}    
    /**
     * Reset password akun - AJAX RESPONSE JSON
     */
    public function resetPassword(Request $request, $id)
    {
        try {
            $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);
            
            $user = User::findOrFail($id);
            $user->password = Hash::make($request->password);
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset untuk akun ' . $user->name,
                'redirect' => route('admin.kelola-akun.index')
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_map(fn($v) => implode(', ', $v), $e->errors()))
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal reset password: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Hapus akun - AJAX RESPONSE JSON
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Cegah menghapus akun sendiri
            if ($user->id == auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menghapus akun sendiri!'
                ], 400);
            }
            
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dihapus!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus akun: ' . $e->getMessage()
            ], 500);
        }
    }
}