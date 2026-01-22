<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        // Validasi
        $request->validate([
            'site_pin' => 'required|numeric|digits_between:4,8', // Minimal 4, Maksimal 8 angka
        ]);

        // Update atau Buat Baru jika belum ada
        Setting::updateOrCreate(
            ['key' => 'site_pin'],
            ['value' => $request->site_pin]
        );

        return redirect()->back()->with('success', 'PIN Keamanan Website berhasil diperbarui!');
    }
}