<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * 
     * Alur: User belum login → Redirect ke halaman login → Setelah login → Redirect ke dashboard sesuai role
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            // Redirect ke halaman login jika belum login
            return route('login');
        }

        return null;
    }
}