<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MeetingRoomController extends Controller
{
    public function index()
    {
        return view('meeting_room.index');
    }

    public function feed(Request $request)
    {
        // Hanya ambil data bertipe 'meeting_room'
        $events = Agenda::where('type', 'meeting_room')
            ->get()
            ->map(function ($agenda) {
                return [
                    'id' => $agenda->id,
                    'title' => $agenda->title . ' (' . $agenda->location . ')', // Tampilkan Nama & Ruangan
                    'start' => $agenda->start_time,
                    'end' => $agenda->end_time,
                    'extendedProps' => [
                        'location' => $agenda->location,
                        'description' => $agenda->description,
                        'participants' => $agenda->participants, // Penting untuk Edit
                        'is_whatsapp_notify' => $agenda->is_whatsapp_notify, // Penting untuk Edit
                        'creator_name' => $agenda->creator->name ?? 'Unknown',
                        'can_edit' => $agenda->user_id == auth()->id() || auth()->user()->role == 'super_admin'
                    ],
                    // Warna Oranye Khusus Booking Room
                    'backgroundColor' => '#fff7ed', 
                    'borderColor' => '#f97316',
                    'textColor' => '#ea580c'
                ];
            });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'start_hour' => 'required',
            'location' => 'required|string', // Ini adalah Nama Ruangan
            'participants' => 'nullable|array', // Tambahan Peserta
        ]);

        $start_time = Carbon::parse($request->date . ' ' . $request->start_hour);
        $end_time = $request->end_hour ? Carbon::parse($request->date . ' ' . $request->end_hour) : $start_time->copy()->addHour();

        // Cek Bentrok Ruangan
        // Logic: Cari agenda lain di ruangan yang sama, yang waktunya beririsan
        $conflict = Agenda::where('type', 'meeting_room')
            ->where('location', $request->location) // Cek ruangan yang sama
            ->where(function ($query) use ($start_time, $end_time) {
                $query->whereBetween('start_time', [$start_time, $end_time])
                      ->orWhereBetween('end_time', [$start_time, $end_time])
                      ->orWhere(function ($q) use ($start_time, $end_time) {
                          $q->where('start_time', '<=', $start_time)
                            ->where('end_time', '>=', $end_time);
                      });
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'GAGAL BOOKING: Ruangan ' . $request->location . ' sudah terpakai di jam tersebut.');
        }

        Agenda::create([
            'user_id' => auth()->id(),
            'type' => 'meeting_room', // KUNCI UTAMA: Tipe Meeting Room
            'title' => $request->title,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'location' => $request->location,
            'description' => $request->description,
            'participants' => $request->participants, // Simpan Peserta
            'is_whatsapp_notify' => $request->has('is_whatsapp_notify'), // Simpan Status WA
        ]);

        return redirect()->route('meeting-room.index')->with('success', 'Booking ruangan berhasil dibuat & Notifikasi dijadwalkan.');
    }

    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);

        // Validasi Hak Akses
        if ($agenda->user_id != auth()->id() && auth()->user()->role != 'super_admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $start_time = Carbon::parse($request->date . ' ' . $request->start_hour);
        $end_time = $request->end_hour ? Carbon::parse($request->date . ' ' . $request->end_hour) : $start_time->copy()->addHour();

        // Cek Bentrok (Kecuali Punya Sendiri)
        $conflict = Agenda::where('type', 'meeting_room')
            ->where('id', '!=', $id) // Abaikan diri sendiri
            ->where('location', $request->location)
            ->where(function ($query) use ($start_time, $end_time) {
                $query->whereBetween('start_time', [$start_time, $end_time])
                      ->orWhereBetween('end_time', [$start_time, $end_time])
                      ->orWhere(function ($q) use ($start_time, $end_time) {
                          $q->where('start_time', '<=', $start_time)
                            ->where('end_time', '>=', $end_time);
                      });
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'UPDATE GAGAL: Jadwal baru bentrok dengan penggunaan ruangan lain.');
        }

        $agenda->update([
            'title' => $request->title,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'location' => $request->location,
            'description' => $request->description,
            'participants' => $request->participants, // Update Peserta
            'is_whatsapp_notify' => $request->has('is_whatsapp_notify'), // Update WA
        ]);

        // Jika user mencentang ulang WA, reset status terkirim agar dikirim lagi
        if ($request->has('is_whatsapp_notify')) {
            $agenda->update(['notification_sent' => false]);
        }

        return back()->with('success', 'Booking ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $agenda = Agenda::findOrFail($id);
        if ($agenda->user_id != auth()->id() && auth()->user()->role != 'super_admin') {
            return back()->with('error', 'Anda tidak berhak menghapus booking ini.');
        }
        $agenda->delete();
        return back()->with('success', 'Booking ruangan dihapus.');
    }
}