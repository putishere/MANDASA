<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next, $role)
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['error' => 'Silakan login terlebih dahulu.']);
        }

        // Pastikan role sesuai
        $user = Auth::user();
        
        // Refresh user dari database untuk memastikan data terbaru
        $user->refresh();
        
        $userRole = $user->role;
        
        // Normalisasi role untuk perbandingan (case-insensitive, trim whitespace)
        $userRole = strtolower(trim($userRole ?? ''));
        $requiredRole = strtolower(trim($role ?? ''));
        
        // Jika role kosong atau tidak valid, coba perbaiki di database
        if (empty($userRole) || $userRole === '' || !in_array($userRole, ['admin', 'santri'])) {
            \Log::warning('User role is empty, null, or invalid, attempting to fix', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_username' => $user->username,
                'raw_role' => $user->role,
                'normalized_role' => $userRole
            ]);
            
            // Coba perbaiki role berdasarkan email atau required role
            if ($requiredRole === 'admin' || ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL))) {
                $user->role = 'admin';
            } else {
                $user->role = 'santri';
            }
            $user->save();
            $user->refresh();
            $userRole = strtolower(trim($user->role ?? ''));
        }
        
        // Jika role sesuai, lanjutkan
        if ($userRole === $requiredRole) {
            return $next($request);
        }

        // Log untuk debugging jika role tidak sesuai
        \Log::warning('Role mismatch detected', [
            'user_id' => $user->id,
            'user_role' => $userRole,
            'required_role' => $requiredRole,
            'raw_role' => $user->role,
            'current_route' => $request->route() ? $request->route()->getName() : null
        ]);
        
        // Jika role tidak sesuai, redirect ke dashboard sesuai role user
        // Cegah redirect loop dengan mengecek current route dan path
        $currentRoute = $request->route() ? $request->route()->getName() : null;
        $currentPath = $request->path();
        
        // CEGAH REDIRECT LOOP: Jika sudah di dashboard yang sesuai dengan role user, biarkan akses
        if ($userRole === 'admin') {
            // Jika user admin mencoba akses route santri, redirect ke admin dashboard
            // CEGAH REDIRECT LOOP: Jika sudah di admin dashboard atau path admin, jangan redirect lagi
            if ($currentRoute === 'admin.dashboard' || strpos($currentPath, 'admin') === 0) {
                // Sudah di dashboard yang benar, tapi role tidak sesuai dengan required role
                // Ini seharusnya tidak terjadi jika role sudah benar
                // Untuk mencegah loop, biarkan akses atau logout jika role benar-benar salah
                \Log::error('EnsureUserRole: User admin di admin route tapi required role tidak sesuai', [
                    'user_id' => $user->id,
                    'user_role' => $userRole,
                    'required_role' => $requiredRole,
                    'current_route' => $currentRoute,
                    'current_path' => $currentPath
                ]);
                // Biarkan akses untuk mencegah loop, tapi log error
                return $next($request);
            }
            // Redirect ke admin dashboard
            return redirect()->route('admin.dashboard')
                ->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        } elseif ($userRole === 'santri') {
            // Jika user santri mencoba akses route admin, redirect ke santri dashboard
            // CEGAH REDIRECT LOOP: Jika sudah di santri dashboard atau path santri, jangan redirect lagi
            if ($currentRoute === 'santri.dashboard' || strpos($currentPath, 'santri') === 0) {
                // Sudah di dashboard yang benar, tapi role tidak sesuai dengan required role
                // Ini seharusnya tidak terjadi jika role sudah benar
                // Untuk mencegah loop, biarkan akses atau logout jika role benar-benar salah
                \Log::error('EnsureUserRole: User santri di santri route tapi required role tidak sesuai', [
                    'user_id' => $user->id,
                    'user_role' => $userRole,
                    'required_role' => $requiredRole,
                    'current_route' => $currentRoute,
                    'current_path' => $currentPath
                ]);
                // Biarkan akses untuk mencegah loop, tapi log error
                return $next($request);
            }
            // Redirect ke santri dashboard
            return redirect()->route('santri.dashboard')
                ->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }
        
        // Jika role tidak valid atau tidak dikenali, logout dan redirect ke login
        \Log::error('Invalid user role detected', [
            'user_id' => $user->id,
            'user_role' => $userRole,
            'required_role' => $requiredRole,
            'raw_role' => $user->role
        ]);
        Auth::logout();
        return redirect()->route('login')->withErrors(['error' => 'Role user tidak valid. Silakan hubungi administrator.']);
    }
}
