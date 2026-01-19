<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function dashboard()
    {
        $totalAgenda = Agenda::count();
        $agendaToday = Agenda::whereDate('start_time', date('Y-m-d'))->count();
        $upcomingAgenda = Agenda::where('start_time', '>', now())->count();
        $recentAgendas = Agenda::orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard', compact('totalAgenda', 'agendaToday', 'upcomingAgenda', 'recentAgendas'));
    }

    public function index()
    {
        return view('agenda.index');
    }

    public function feed(Request $request)
    {
        $query = Agenda::query();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $agendas = $query->get();
        
        $events = $agendas->map(function ($agenda) {
            $color = $agenda->type == 'meeting_room' ? '#dc3545' : '#198754';
            
            // --- LOGIKA SUPER ADMIN DI SINI ---
            $isOwner = $agenda->user_id == Auth::id();
            $isSuperAdmin = Auth::user()->role === 'super_admin'; // Cek role user
            
            // User boleh edit jika dia Pemilik ATAU Super Admin
            $canEdit = $isOwner || $isSuperAdmin; 

            return [
                'id' => $agenda->id,
                'title' => $agenda->title,
                'start' => $agenda->start_time->format('Y-m-d H:i:s'),
                'end' => $agenda->end_time ? $agenda->end_time->format('Y-m-d H:i:s') : null,
                'location' => $agenda->location,
                'description' => $agenda->description,
                'participants' => $agenda->participants,
                'is_whatsapp_notify' => $agenda->is_whatsapp_notify,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'can_edit' => $canEdit, // Kirim status izin ke frontend
                    'creator_name' => $agenda->user ? $agenda->user->name : 'Unknown'
                ]
            ];
        });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'start_hour' => 'required',
            'location' => 'required',
        ]);

        $start_datetime = $request->date . ' ' . $request->start_hour . ':00';
        $end_datetime = $request->end_hour ? $request->date . ' ' . $request->end_hour . ':00' : null;

        // Cek Bentrok
        if ($end_datetime) {
            $conflicting = Agenda::where('location', $request->location)
                ->where(function ($query) use ($start_datetime, $end_datetime) {
                    $query->where('start_time', '<', $end_datetime)
                          ->where('end_time', '>', $start_datetime);
                })->first();

            if ($conflicting) {
                $booker = $conflicting->user ? $conflicting->user->name : 'Admin lain';
                return redirect()->back()->with('error', "Gagal! Lokasi ini sedang dipakai oleh {$booker}.");
            }
        }

        Agenda::create([
            'user_id' => Auth::id(),
            'type' => 'general',
            'title' => $request->title,
            'start_time' => $start_datetime,
            'end_time' => $end_datetime,
            'location' => $request->location,
            'participants' => $request->participants ?? [],
            'description' => $request->description,
            'is_whatsapp_notify' => $request->has('is_whatsapp_notify') ? true : false,
        ]);

        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        $agenda = Agenda::findOrFail($id);

        // --- UPDATE LOGIKA SECURITY (SUPER ADMIN) ---
        // Jika User BUKAN Pemilik DAN User BUKAN Super Admin -> Tendang
        if ($agenda->user_id != Auth::id() && Auth::user()->role !== 'super_admin') {
            return redirect()->back()->with('error', 'Maaf, Anda tidak memiliki izin untuk mengubah agenda ini.');
        }
        // --------------------------------------------

        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'start_hour' => 'required',
            'location' => 'required',
        ]);

        $start_datetime = $request->date . ' ' . $request->start_hour . ':00';
        $end_datetime = $request->end_hour ? $request->date . ' ' . $request->end_hour . ':00' : null;

        // Cek Bentrok (Kecuali Punya Sendiri)
        if ($end_datetime) {
            $conflicting = Agenda::where('location', $request->location)
                ->where('id', '!=', $id)
                ->where(function ($query) use ($start_datetime, $end_datetime) {
                    $query->where('start_time', '<', $end_datetime)
                          ->where('end_time', '>', $start_datetime);
                })->first();

            if ($conflicting) {
                $booker = $conflicting->user ? $conflicting->user->name : 'Admin lain';
                return redirect()->back()->with('error', "Gagal Update! Lokasi bentrok dengan {$booker}.");
            }
        }

        $agenda->update([
            'title' => $request->title,
            'start_time' => $start_datetime,
            'end_time' => $end_datetime,
            'location' => $request->location,
            'participants' => $request->participants ?? [],
            'description' => $request->description,
            'is_whatsapp_notify' => $request->has('is_whatsapp_notify') ? true : false,
            'notification_sent' => false, // Reset agar notifikasi dikirim ulang jika jadwal berubah
        ]);

        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $agenda = Agenda::findOrFail($id);

        // --- UPDATE LOGIKA SECURITY (SUPER ADMIN) ---
        // Jika User BUKAN Pemilik DAN User BUKAN Super Admin -> Tendang
        if ($agenda->user_id != Auth::id() && Auth::user()->role !== 'super_admin') {
            return redirect()->back()->with('error', 'Maaf, Anda tidak memiliki izin untuk menghapus agenda ini.');
        }
        // --------------------------------------------

        $agenda->delete();

        return redirect()->back()->with('success', 'Agenda berhasil dihapus');
    }

    public function welcome() {
        return view('welcome');
    }
}