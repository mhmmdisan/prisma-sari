<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validasi input
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Cek apakah email terdaftar
        $user = User::where('email', $request->email)->first();

        // Jika email tidak ditemukan
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Mohon maaf, email yang Anda masukkan salah atau tidak terdaftar.',
            ]);
        }

        // Jika email ditemukan tapi password salah
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'password' => 'Mohon maaf, kata sandi yang Anda masukkan salah.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role == 'admin') {
            return redirect('/admin/dashboard');
        }

        if ($user->role == 'pelanggan') {
            return redirect('/pelanggan/dashboard');
        }

        if ($user->role == 'pemilik') {
            return redirect('/pemilik/dashboard');
        }
        
        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}