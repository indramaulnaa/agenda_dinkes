<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Setting; // Tambahkan Model Setting

class PinController extends Controller
{
    public function showForm()
    {
        return view('auth.pin_login');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'pin' => 'required|numeric',
        ]);

        // AMBIL PIN DARI DATABASE
        // Jika tidak ada di DB, fallback ke '123456'
        $correctPin = Setting::where('key', 'site_pin')->value('value') ?? '123456';

        if ($request->pin == $correctPin) {
            Session::put('pegawai_pin_verified', true);
            return redirect()->route('home');
        }

        return back()->with('error', 'PIN Salah! Silakan coba lagi.');
    }
}