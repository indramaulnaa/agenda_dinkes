<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        // 1. Ambil data user yang sedang login
        $user = Auth::user();

        // 2. Validasi input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            // Password bersifat opsional (nullable), hanya divalidasi jika diisi
            'password' => 'nullable|string|min:8|confirmed', 
        ]);

        // 3. Update Nama
        $user->name = $request->name;

        // 4. Cek apakah user mengisi password baru?
        if ($request->filled('password')) {
            // Jika diisi, update password (jangan lupa di-Hash)
            $user->password = Hash::make($request->password);
        }

        // 5. Simpan ke database
        $user->save();

        // 6. Kembali ke halaman settings dengan pesan sukses
        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}