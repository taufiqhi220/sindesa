<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah user sudah login dan role-nya sesuai dengan yang diizinkan
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            abort(403, 'Akses Ditolak. Halaman ini bukan untuk hak akses kamu.');
        }

        return $next($request);
    }
}