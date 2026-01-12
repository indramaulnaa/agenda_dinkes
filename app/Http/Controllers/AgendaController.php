<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agenda;

class AgendaController extends Controller
{
    // --- 1. HALAMAN DEPAN (Pegawai/Umum) ---
    // Fungsi ini sebelumnya TIDAK ADA di file kamu
    public function welcome()
    {
        return view('welcome');
    }

    // --- 2. ADMIN: LIST AGENDA (Index) ---
    public function index()
    {
        $agendas = Agenda::latest()->get();
        return view('agenda.index', compact('agendas'));
    }

    // --- 3. DATA FEED (API Kalender) ---
    public function feed()
    {
        $agendas = Agenda::all();
        $events = [];

        foreach ($agendas as $agenda) {
            // Logika pewarnaan selang-seling (Genap: Biru, Ganjil: Hijau)
            $isGenap = $agenda->id % 2 == 0;
            
            $events[] = [
                'id' => $agenda->id,
                'title' => $agenda->title,
                'start' => $agenda->start_time,
                'end'   => $agenda->end_time,
                'extendedProps' => [
                    'location' => $agenda->location,
                    'description' => $agenda->description,
                    // Tambahkan 2 baris ini agar data baru terbaca di Frontend
                    'participants' => $agenda->participants,
                    'is_whatsapp_notify' => $agenda->is_whatsapp_notify
                ],
                'backgroundColor' => $isGenap ? '#cfe2ff' : '#d1e7dd', 
                'borderColor'     => $isGenap ? '#cfe2ff' : '#d1e7dd', 
                'textColor'       => $isGenap ? '#084298' : '#0f5132',
                'allDay'          => false 
            ];
        }

        return response()->json($events);
    }

    // --- 4. DASHBOARD ADMIN ---
    public function dashboard()
    {
        // FIX: Pakai waktu Asia/Jakarta (WIB) agar akurat
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        
        // 1. Agenda Hari Ini (Total agenda yang tanggalnya HARI INI)
        $agendaHariIni = Agenda::whereDate('start_time', $now->toDateString())->count();

        // 2. Agenda Bulan Ini (Total agenda di BULAN INI)
        $agendaBulanIni = Agenda::whereMonth('start_time', $now->month)
                                ->whereYear('start_time', $now->year)
                                ->count();

        // 3. Akan Datang
        // Logika: Hitung agenda yang Waktu Mulainya LEBIH BESAR dari Waktu Sekarang (WIB)
        // Jadi kalau sekarang jam 08.00, agenda jam 07.15 tidak akan dihitung lagi.
        $agendaAkanDatang = Agenda::where('start_time', '>', $now->toDateTimeString())->count();

        // 4. Total Arsip (Agenda yang SUDAH LEWAT)
        $totalAgenda = Agenda::where('start_time', '<', $now->toDateTimeString())->count();

        // 5. Tabel Agenda Baru Ditambahkan (Inputan Hari Ini)
        $latestAgendas = Agenda::whereDate('created_at', $now->toDateString())
                                ->latest()
                                ->get();

        return view('dashboard', compact(
            'agendaHariIni', 
            'agendaBulanIni', 
            'agendaAkanDatang', 
            'totalAgenda', 
            'latestAgendas'
        ));
    }

    // --- 5. CRUD: CREATE ---
    public function create()
    {
        return view('agenda.create');
    }

    // --- 6. CRUD: STORE ---
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'start_hour' => 'required',
            'location' => 'required',
            'participants' => 'required|array', // Ubah jadi array
        ]);

        // ... SISA KODE SAMA (logika gabung tanggal) ...
        $start_datetime = $request->date . ' ' . $request->start_hour;
        $end_datetime = $request->end_hour ? $request->date . ' ' . $request->end_hour : null;

        Agenda::create([
            'title' => $request->title,
            'start_time' => $start_datetime,
            'end_time' => $end_datetime,
            'location' => $request->location,
            'participants' => $request->participants, // Laravel otomatis ubah array ke JSON
            'description' => $request->description,
            'is_whatsapp_notify' => $request->has('is_whatsapp_notify') ? true : false,
        ]);

        return redirect()->back()->with('success', 'Agenda berhasil dijadwalkan!');
    }

    // --- 7. CRUD: EDIT ---
    public function edit($id)
    {
        $agenda = Agenda::findOrFail($id);
        return view('agenda.edit', compact('agenda'));
    }

    // --- 8. CRUD: UPDATE ---
    public function update(Request $request, Agenda $agenda)
    {
        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'start_hour' => 'required',
            'location' => 'required',
            'participants' => 'required|array', // Ubah jadi array
        ]);

        // ... SISA KODE SAMA ...
        $start_datetime = $request->date . ' ' . $request->start_hour;
        $end_datetime = $request->end_hour ? $request->date . ' ' . $request->end_hour : null;

        $agenda->update([
            'title' => $request->title,
            'start_time' => $start_datetime,
            'end_time' => $end_datetime,
            'location' => $request->location,
            'participants' => $request->participants,
            'description' => $request->description,
            'is_whatsapp_notify' => $request->has('is_whatsapp_notify') ? true : false,
        ]);

        return redirect()->back()->with('success', 'Agenda berhasil diperbarui!');
    }

    // --- 9. CRUD: DESTROY ---
    public function destroy($id)
    {
        $agenda = Agenda::findOrFail($id);
        $agenda->delete();

        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil dihapus!');
    }
} 
// HANYA ADA SATU KURUNG TUTUP DI SINI (SUDAH BENAR)