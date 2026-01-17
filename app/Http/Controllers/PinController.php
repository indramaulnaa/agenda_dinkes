<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PinController extends Controller
{
    // Tampilkan Form PIN
    public function showForm()
    {
        // Jika sudah unlock, langsung ke home aja
        if (session('pin_unlocked') === true) {
            return redirect('/'); 
        }
        return view('auth.pin_login');
    }

    // Proses Cek PIN
    public function verify(Request $request)
    {
        $request->validate([
            'pin' => 'required'
        ]);

        // Ambil PIN asli dari file .env
        $correctPin = env('APP_PEGAWAI_PIN', '123456');

        if ($request->pin == $correctPin) {
            // Jika Benar: Simpan status 'unlocked' ke session browser
            session(['pin_unlocked' => true]);
            return redirect('/'); // Masuk ke Agenda
        }

        // Jika Salah
        return back()->with('error', 'PIN Salah! Silakan coba lagi.');
    }
}