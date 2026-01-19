<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika User Login & Role-nya 'super_admin', Silakan Masuk
        if (Auth::check() && Auth::user()->role === 'super_admin') {
            return $next($request);
        }

        // Jika Bukan, Tendang Kembali
        return redirect()->route('dashboard')->with('error', 'AKSES DITOLAK: Halaman ini khusus Super Admin!');
    }
}