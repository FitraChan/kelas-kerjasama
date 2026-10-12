<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if (!auth()->check()) {
            abort(403, 'Akses Ditolak. Anda harus login.');
        }

        // Cek 2: Verifikasi level pengguna
        // Jika level pengguna BUKAN 1, maka akses ditolak.
        if (auth()->user()->level != 1) { 
            abort(403, 'Akses Ditolak. Anda bukan Administrator Level 1.');
        }

        return $next($request);
    }
}
