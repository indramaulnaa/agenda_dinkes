<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPegawaiPin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Jika user adalah ADMIN (sudah login), bebaskan masuk tanpa PIN
        if (Auth::check()) {
            return $next($request);
        }

        // 2. Jika user adalah Pegawai yang SUDAH masukkan PIN (ada di session), silakan masuk
        if (session('pin_unlocked') === true) {
            return $next($request);
        }

        // 3. Jika belum keduanya, lempar ke halaman Input PIN
        return redirect()->route('pin.form');
    }
}