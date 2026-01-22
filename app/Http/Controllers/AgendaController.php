<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function dashboard()
    {
        // 1. Tentukan Waktu Sekarang (WIB)
        $now = Carbon::now('Asia/Jakarta');

        // 2. Hitung Statistik
        $agendaToday = Agenda::whereDate('start_time', $now->toDateString())->count();
        $agendaMonth = Agenda::whereMonth('start_time', $now->month)
                             ->whereYear('start_time', $now->year)
                             ->count();
        $agendaUpcoming = Agenda::where('start_time', '>', $now)->count();
        $agendaArchived = Agenda::where('end_time', '<', $now)->count();

        // 3. UPDATE: Daftar Agenda Ditambahkan HARI INI (Unlimited)
        $recentAgendas = Agenda::with('user')
                               ->whereDate('created_at', $now->toDateString()) // Filter: Dibuat Hari Ini
                               ->orderBy('created_at', 'desc') // Urutkan dari yang terbaru
                               // ->take(5) <--- INI DIHAPUS agar tidak terbatas 5
                               ->get();

        return view('dashboard', compact(
            'agendaToday', 
            'agendaMonth', 
            'agendaUpcoming', 
            'agendaArchived', 
            'recentAgendas'
        ));
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
            
            $isOwner = $agenda->user_id == Auth::id();
            $isSuperAdmin = Auth::check() && Auth::user()->role === 'super_admin';
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
                    'can_edit' => $canEdit,
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

        if ($agenda->user_id != Auth::id() && Auth::user()->role !== 'super_admin') {
            return redirect()->back()->with('error', 'Maaf, Anda tidak memiliki izin untuk mengubah agenda ini.');
        }

        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'start_hour' => 'required',
            'location' => 'required',
        ]);

        $start_datetime = $request->date . ' ' . $request->start_hour . ':00';
        $end_datetime = $request->end_hour ? $request->date . ' ' . $request->end_hour . ':00' : null;

        $agenda->update([
            'title' => $request->title,
            'start_time' => $start_datetime,
            'end_time' => $end_datetime,
            'location' => $request->location,
            'participants' => $request->participants ?? [],
            'description' => $request->description,
            'is_whatsapp_notify' => $request->has('is_whatsapp_notify') ? true : false,
            'notification_sent' => false,
        ]);

        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $agenda = Agenda::findOrFail($id);

        if ($agenda->user_id != Auth::id() && Auth::user()->role !== 'super_admin') {
            return redirect()->back()->with('error', 'Maaf, Anda tidak memiliki izin untuk menghapus agenda ini.');
        }

        $agenda->delete();

        return redirect()->back()->with('success', 'Agenda berhasil dihapus');
    }
}