<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session; // <--- PENTING: Tambahkan ini

class CheckPegawaiPin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah Admin sedang login?
        // Jika Admin login, boleh masuk tanpa PIN (Bypass)
        if (\Illuminate\Support\Facades\Auth::check()) {
            return $next($request);
        }

        // Cek apakah Session PIN sudah tervalidasi?
        if (Session::get('pegawai_pin_verified') === true) {
             return $next($request);
        }

        // Jika belum login admin DAN belum masukkan PIN, tendang ke form PIN
        return redirect()->route('pin.form')->with('error', 'Akses terkunci! Silakan masukkan PIN Dinas.');
    }
}