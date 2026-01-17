<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // WAJIB ADA: Untuk kirim WA

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
            $isOwner = $agenda->user_id == Auth::id(); 

            // Logika Privasi (Jika Anda pakai fitur private)
            $title = $agenda->title;
            if ($agenda->is_secret && !Auth::check()) { $title = "🔒 Agenda Tertutup"; }

            return [
                'id' => $agenda->id,
                'title' => $title,
                'start' => $agenda->start_time->format('Y-m-d H:i:s'), // Fix Timezone
                'end' => $agenda->end_time ? $agenda->end_time->format('Y-m-d H:i:s') : null,
                'location' => $agenda->location,
                'description' => $agenda->description,
                'participants' => $agenda->participants,
                'is_whatsapp_notify' => $agenda->is_whatsapp_notify,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'can_edit' => $isOwner,
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

        $agenda = Agenda::create([
            'user_id' => Auth::id(),
            'type' => 'general',
            'title' => $request->title,
            'start_time' => $start_datetime,
            'end_time' => $end_datetime,
            'location' => $request->location,
            'participants' => $request->participants ?? [],
            'description' => $request->description,
            'is_whatsapp_notify' => $request->has('is_whatsapp_notify') ? true : false,
            'is_secret' => $request->has('is_secret') ? true : false,
        ]);

        // --- KIRIM WHATSAPP ---
        if ($agenda->is_whatsapp_notify) {
            $this->sendWhatsappNotification($agenda);
        }

        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        $agenda = Agenda::findOrFail($id);

        if ($agenda->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Maaf, Anda tidak memiliki izin untuk mengubah agenda ini karena dibuat oleh admin lain.');
        }

        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'start_hour' => 'required',
            'location' => 'required',
        ]);

        $start_datetime = $request->date . ' ' . $request->start_hour . ':00';
        $end_datetime = $request->end_hour ? $request->date . ' ' . $request->end_hour . ':00' : null;

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
            'is_secret' => $request->has('is_secret') ? true : false,
        ]);

        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $agenda = Agenda::findOrFail($id);

        if ($agenda->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Maaf, Anda tidak memiliki izin untuk menghapus agenda ini karena dibuat oleh admin lain.');
        }

        $agenda->delete();

        return redirect()->back()->with('success', 'Agenda berhasil dihapus');
    }

    // --- FUNGSI KIRIM WA KHUSUS AGENDA ---
    private function sendWhatsappNotification($agenda)
    {
        try {
            $token = env('FONNTE_TOKEN');
            $target = env('FONNTE_TARGET_GROUP'); 

            $hari = $agenda->start_time->isoFormat('dddd, D MMMM Y');
            $jam = $agenda->start_time->format('H:i') . ' WIB';
            if ($agenda->end_time) {
                $jam .= ' s/d ' . $agenda->end_time->format('H:i') . ' WIB';
            }

            $message = "*AGENDA BARU DINAS KESEHATAN* 📢\n\n"
                . "📌 *Kegiatan:* {$agenda->title}\n"
                . "📅 *Hari/Tgl:* {$hari}\n"
                . "⏰ *Pukul:* {$jam}\n"
                . "📍 *Lokasi:* {$agenda->location}\n"
                . "👥 *Peserta:* " . implode(', ', $agenda->participants ?? ['-']) . "\n\n"
                . "📝 *Catatan:* " . ($agenda->description ?? '-') . "\n\n"
                . "_Pesan otomatis Sistem Agenda_";

            Http::withHeaders(['Authorization' => $token])
                ->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                ]);
        } catch (\Exception $e) {
            // Silent Fail (Lanjut terus meski WA gagal)
        }
    }

    public function welcome() {
        return view('welcome');
    }
}