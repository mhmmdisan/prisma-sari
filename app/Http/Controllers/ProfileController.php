<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Pesanan;

class ProfileController extends Controller
{
    /**
     * Display the user's profile index (halaman tampil profil)
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Hitung total pesanan
        $totalPesanan = Pesanan::where('user_id', $user->id)->count();
        
        // 🔥 HITUNG PESANAN SELESAI
        // Status yang dianggap selesai: 'selesai', 'diproses', 'dikirim', 'completed'
        $pesananSelesai = Pesanan::where('user_id', $user->id)
            ->whereIn('status', ['selesai', 'diproses', 'dikirim', 'completed'])
            ->count();
        
        // Total pengeluaran (pesanan dengan pembayaran lunas)
        $totalPengeluaran = Pesanan::where('user_id', $user->id)
            ->where('status_pembayaran', 'lunas')
            ->sum('total_harga');
        
        // Format Rupiah
        $totalPengeluaranFormatted = 'Rp ' . number_format($totalPengeluaran, 0, ',', '.');
        
        return view('profile.index', compact(
            'user', 
            'totalPesanan', 
            'pesananSelesai', 
            'totalPengeluaranFormatted'
        ));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        try {
            // Validasi data
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
                'no_telepon' => ['nullable', 'string', 'max:20'],
                'alamat' => ['nullable', 'string'],
                'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]);

            $user = $request->user();
            
            // Update data langsung (tanpa array)
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_telepon = $request->no_telepon;
            $user->alamat = $request->alamat;
            
            // Handle upload foto profil
            if ($request->hasFile('foto_profil')) {
                // Hapus foto lama jika ada
                if ($user->foto_profil && file_exists(public_path($user->foto_profil))) {
                    @unlink(public_path($user->foto_profil));
                }
                
                $file = $request->file('foto_profil');
                
                // Buat nama file unik
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                
                // Tujuan langsung ke public/storage/profil
                $destinationPath = public_path('storage/profil');
                
                // Buat folder jika belum ada
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                // Pindahkan file
                $file->move($destinationPath, $filename);
                
                // Simpan path ke database
                $user->foto_profil = 'storage/profil/' . $filename;
            }

            // Simpan ke database
            $user->save();

            if ($user->wasChanged('email')) {
                $user->email_verified_at = null;
                $user->save();
            }

            return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error update profil: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}