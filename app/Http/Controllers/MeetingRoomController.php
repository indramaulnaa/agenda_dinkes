<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingRoomController extends Controller
{
    public function index()
    {
        return view('meeting_room.index');
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'start_hour' => 'required',
            'end_hour' => 'required',
            'location' => 'required',
        ]);

        $start_datetime = $request->date . ' ' . $request->start_hour . ':00';
        $end_datetime = $request->date . ' ' . $request->end_hour . ':00';

        if ($end_datetime <= $start_datetime) {
            return redirect()->back()->with('error', 'Jam selesai harus lebih akhir dari jam mulai.');
        }

        // 2. CEK BENTROK (Create)
        $conflictingAgenda = Agenda::where('location', $request->location)
            ->where(function ($query) use ($start_datetime, $end_datetime) {
                $query->where('start_time', '<', $end_datetime)
                      ->where('end_time', '>', $start_datetime);
            })
            ->first();

        if ($conflictingAgenda) {
            $booker = $conflictingAgenda->user ? $conflictingAgenda->user->name : 'Admin lain';
            $jam = $conflictingAgenda->start_time->format('H:i') . ' - ' . ($conflictingAgenda->end_time ? $conflictingAgenda->end_time->format('H:i') : '?');
            return redirect()->back()->with('error', "Gagal! Ruangan dipakai oleh {$booker} ({$jam}).");
        }

        // 3. Simpan
        Agenda::create([
            'user_id' => Auth::id(),
            'type' => 'meeting_room',
            'title' => $request->title,
            'start_time' => $start_datetime,
            'end_time' => $end_datetime,
            'location' => $request->location,
            'participants' => $request->participants ?? [],
            'description' => $request->description,
            'is_whatsapp_notify' => $request->has('is_whatsapp_notify') ? true : false,
        ]);

        return redirect()->back()->with('success', 'Booking berhasil dibuat!');
    }

    // --- FUNGSI BARU UNTUK UPDATE ---
    public function update(Request $request, string $id)
    {
        $agenda = Agenda::findOrFail($id);

        // 1. Cek Kepemilikan
        if ($agenda->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Maaf, Anda tidak memiliki izin untuk mengubah booking ini.');
        }

        // 2. Validasi
        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'start_hour' => 'required',
            'end_hour' => 'required',
            'location' => 'required',
        ]);

        $start_datetime = $request->date . ' ' . $request->start_hour . ':00';
        $end_datetime = $request->date . ' ' . $request->end_hour . ':00';

        if ($end_datetime <= $start_datetime) {
            return redirect()->back()->with('error', 'Jam selesai harus lebih akhir dari jam mulai.');
        }

        // 3. CEK BENTROK (KECUALI DIRI SENDIRI)
        $conflictingAgenda = Agenda::where('location', $request->location)
            ->where('id', '!=', $id) // <--- PENTING: Abaikan data ini sendiri
            ->where(function ($query) use ($start_datetime, $end_datetime) {
                $query->where('start_time', '<', $end_datetime)
                      ->where('end_time', '>', $start_datetime);
            })
            ->first();

        if ($conflictingAgenda) {
            $booker = $conflictingAgenda->user ? $conflictingAgenda->user->name : 'Admin lain';
            $jam = $conflictingAgenda->start_time->format('H:i') . ' - ' . ($conflictingAgenda->end_time ? $conflictingAgenda->end_time->format('H:i') : '?');
            return redirect()->back()->with('error', "Gagal Update! Ruangan bentrok dengan {$booker} ({$jam}).");
        }

        // 4. Update Data
        $agenda->update([
            'title' => $request->title,
            'start_time' => $start_datetime,
            'end_time' => $end_datetime,
            'location' => $request->location,
            'participants' => $request->participants ?? [],
            'description' => $request->description,
            // is_whatsapp_notify opsional, tidak diupdate disini agar simpel
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui!');
    }
}