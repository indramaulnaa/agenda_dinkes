<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agenda;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data agenda, urutkan dari yang terbaru
        $agendas = Agenda::latest()->get();

        // Kirim data ($agendas) ke view
        return view('agenda.index', compact('agendas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('agenda.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi data (Cek apakah isian sudah benar)
        $request->validate([
            'title' => 'required',
            'location' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
    ]);

        // 2. Simpan ke Database
        Agenda::create($request->all());

        // 3. Kembali ke halaman utama dengan pesan sukses
        return redirect()->route('agenda.index')
                        ->with('success', 'Agenda berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // 1. Ambil data agenda yang mau diedit
        $agenda = Agenda::findOrFail($id);

        // 2. Tampilkan form edit sambil membawa data tadi
        return view('agenda.edit', compact('agenda'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            // 1. Validasi inputan
        $request->validate([
            'title' => 'required',
            'location' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // 2. Cari data yang mau diupdate
        $agenda = Agenda::findOrFail($id);

        // 3. Simpan perubahan
        $agenda->update($request->all());

        // 4. Kembali ke halaman utama
        return redirect()->route('agenda.index')
                        ->with('success', 'Agenda berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // 1. Cari data berdasarkan ID
        $agenda = Agenda::findOrFail($id);

        // 2. Hapus data
        $agenda->delete();

        // 3. Kembali ke halaman utama
        return redirect()->route('agenda.index')
                         ->with('success', 'Agenda berhasil dihapus!');
    }

    public function dashboard()
    {
        // Ambil data untuk halaman depan (Pegawai)
        $agendas = Agenda::latest()->get();
        return view('welcome', compact('agendas'));
    }

    // Fungsi khusus untuk memberikan data ke Kalender
    public function feed()
    {
        $agendas = Agenda::all();
        $events = [];

        foreach ($agendas as $agenda) {
            $isGenap = $agenda->id % 2 == 0;
            
            $events[] = [
                'id' => $agenda->id,
                'title' => $agenda->title,
                'start' => $agenda->start_time,
                'end'   => $agenda->end_time,
                'extendedProps' => [
                    'location' => $agenda->location,
                    'description' => $agenda->description
                ],
                // WARNA PASTEL PERSIS PROTOTYPE
                'backgroundColor' => $isGenap ? '#cfe2ff' : '#d1e7dd', // Biru Muda / Hijau Muda
                'borderColor'     => $isGenap ? '#cfe2ff' : '#d1e7dd', 
                'textColor'       => $isGenap ? '#084298' : '#0f5132', // Teks Biru Tua / Hijau Tua
                'allDay'          => false 
            ];
        }

        return response()->json($events);
    }
}
