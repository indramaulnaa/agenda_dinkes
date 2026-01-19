<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Tampilkan Daftar User
    public function index()
    {
        // Ambil semua user, urutkan super_admin di atas
        $users = User::orderBy('role', 'desc')->orderBy('name', 'asc')->get();
        return view('users.index', compact('users'));
    }

    // Simpan User Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,super_admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi Password
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    // Update User (Termasuk Reset Password)
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Abaikan email milik sendiri
            'role' => 'required|in:admin,super_admin',
            // Password boleh kosong (artinya tidak diubah)
            'password' => 'nullable|min:6',
        ]);

        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // LOGIKA RESET PASSWORD
        // Jika kolom password diisi, maka update password baru.
        // Jika kosong, biarkan password lama.
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataToUpdate);

        return redirect()->back()->with('success', 'Data user berhasil diperbarui!');
    }

    // Hapus User
    public function destroy(string $id)
    {
        // Cegah Super Admin menghapus dirinya sendiri saat sedang login
        if ($id == Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }
}