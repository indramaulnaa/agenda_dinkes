<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    // 1. TAMPILKAN DATA
    public function index(Request $request)
    {
        // 1. Mulai Query dasar
        $query = Staff::query();

        // 2. Cek apakah user sedang mencari sesuatu?
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            
            // Cari berdasarkan Nama ATAU NIP ATAU No HP
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('nip', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // 3. Ambil data dengan Pagination (10 data per halaman)
        // Gunakan withQueryString() agar saat pindah halaman, pencarian tidak hilang
        $staff = $query->latest()->paginate(10)->withQueryString();

        return view('staff.index', compact('staff'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('staff.create');
    }

    // 3. PROSES SIMPAN
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:staff,nip',
            'name' => 'required',
            'position' => 'required',
            'phone' => 'required|numeric',
        ]);

        Staff::create($request->all());

        return redirect()->route('staff.index')->with('success', 'Data Staff berhasil ditambahkan!');
    }

    // 4. FORM EDIT
    public function edit(Staff $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    // 5. PROSES UPDATE
    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'nip' => 'required|unique:staff,nip,'.$staff->id,
            'name' => 'required',
            'position' => 'required',
            'phone' => 'required|numeric',
        ]);

        $staff->update($request->all());

        return redirect()->route('staff.index')->with('success', 'Data Staff berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Data Staff berhasil dihapus!');
    }
}