<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    /**
     * Handle an incoming request.
     * 
     * Middleware ini digunakan untuk route yang hanya bisa diakses oleh user yang BELUM login (guest).
     * Jika user sudah login, akan di-redirect ke dashboard sesuai role.
     * 
     * Alur:
     * 1. User mengakses route dengan middleware 'guest' (contoh: /login)
     * 2. Cek apakah user sudah login
     * 3. Jika sudah login → Redirect ke dashboard sesuai role
     * 4. Jika belum login → Lanjutkan ke route (tampilkan form login)
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // Cek apakah user sudah login dengan cara yang lebih ketat
            // Pastikan session benar-benar valid
            if (Auth::guard($guard)->check()) {
                try {
                    $user = Auth::guard($guard)->user();
                    
                    // Verifikasi user benar-benar ada di database
                    if (!$user || !$user->id) {
                        // User tidak valid, logout dan tampilkan login
                        Auth::guard($guard)->logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                        return $next($request);
                    }
                    
                    // Normalisasi role untuk perbandingan
                    $userRole = strtolower(trim($user->role ?? ''));
                    
                    // Cegah redirect loop - cek apakah request sudah menuju ke dashboard
                    $currentRoute = $request->route() ? $request->route()->getName() : null;
                    
                    // Jika sudah di dashboard, jangan redirect lagi (biarkan akses)
                    if ($currentRoute === 'admin.dashboard' || $currentRoute === 'santri.dashboard') {
                        return $next($request);
                    }
                    
                    // Jika user sudah login dan mencoba akses route guest (seperti /login)
                    // Redirect ke dashboard sesuai role
                    if ($userRole === 'admin') {
                        return redirect()->route('admin.dashboard');
                    } elseif ($userRole === 'santri') {
                        return redirect()->route('santri.dashboard');
                    }
                    
                    // Jika role tidak valid atau kosong, logout dan redirect ke login
                    \Log::warning('Invalid user role in RedirectIfAuthenticated', [
                        'user_id' => $user->id,
                        'user_role' => $userRole,
                        'raw_role' => $user->role
                    ]);
                    
                    Auth::guard($guard)->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('login')->withErrors(['error' => 'Role user tidak valid. Silakan hubungi administrator.']);
                } catch (\Exception $e) {
                    // Jika ada error saat cek user, logout dan tampilkan login
                    \Log::error('Error in RedirectIfAuthenticated: ' . $e->getMessage());
                    Auth::guard($guard)->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return $next($request);
                }
            }
        }

        // User belum login, lanjutkan ke route (tampilkan form login)
        // Pastikan tidak ada cache yang menyebabkan redirect
        $response = $next($request);
        
        // Tambahkan header untuk mencegah cache
        return $response->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate, private',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }
}

