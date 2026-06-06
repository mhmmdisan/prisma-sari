<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user login
        if (!auth()->check()) {
            Log::info('RoleMiddleware: User tidak login, redirect ke login');
            return redirect('/login');
        }
        
        // Jika $roles kosong, izinkan semua user yang login
        if (empty($roles)) {
            Log::info('RoleMiddleware: Tidak ada role yang diperlukan, akses diizinkan untuk user: ' . auth()->user()->email);
            return $next($request);
        }
        
        $userRole = auth()->user()->role;
        $allowedRoles = implode(', ', $roles);
        
        Log::info('RoleMiddleware: User role = ' . $userRole . ', Required roles = ' . $allowedRoles);
        
        // Cek apakah role user termasuk dalam daftar roles yang diizinkan
        if (!in_array($userRole, $roles)) {
            Log::warning('RoleMiddleware: Akses DITOLAK untuk user ' . auth()->user()->email . ' dengan role ' . $userRole);
            abort(403, 'Unauthorized access. Hanya untuk: ' . $allowedRoles);
        }
        
        Log::info('RoleMiddleware: Akses DIIZINKAN untuk user ' . auth()->user()->email);
        
        return $next($request);
    }
}