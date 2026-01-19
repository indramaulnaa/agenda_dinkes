<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agenda;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SendAgendaReminders extends Command
{
    protected $signature = 'agenda:send-reminders';
    protected $description = 'Kirim notifikasi WA ke peserta yang terdaftar di config';

    public function handle()
    {
        $this->info('Memeriksa jadwal agenda...');
        $now = Carbon::now('Asia/Jakarta');
        $targetTime = $now->copy()->addMinutes(30);

        // Ambil agenda yang harus dikirim
        $agendas = Agenda::where('is_whatsapp_notify', true)
            ->where('notification_sent', false)
            ->where('start_time', '<=', $targetTime)
            ->where('start_time', '>', $now)
            ->get();

        foreach ($agendas as $agenda) {
            $this->sendToParticipants($agenda);
            $agenda->update(['notification_sent' => true]);
            $this->info("Notifikasi terkirim untuk: {$agenda->title}");
        }
    }

    private function sendToParticipants($agenda)
    {
        $token = env('FONNTE_TOKEN');
        
        // 1. Ambil List Peserta dari Database
        // Karena sekarang "Peserta" berfungsi ganda sebagai target WA
        $participants = $agenda->participants ?? []; 
        
        if (empty($participants)) {
            return; 
        }

        // 2. Ambil Mapping Nama Grup -> ID WA dari Config
        $groupConfig = config('whatsapp.groups');

        // 3. Loop setiap peserta yang dipilih
        foreach ($participants as $participantName) {
            // Cek apakah nama peserta ini ada di config WA (artinya dia adalah grup target)
            if (isset($groupConfig[$participantName]) && !empty($groupConfig[$participantName])) {
                
                $targetID = $groupConfig[$participantName];
                
                // Kirim Pesan ke ID Grup tersebut
                $this->sendMessage($token, $targetID, $agenda, $participantName);
                // --- TAMBAHAN: Jeda 3 detik agar tidak dianggap spam ---
                sleep(3);
            }
        }
    }

    private function sendMessage($token, $targetID, $agenda, $groupName)
    {
        try {
            $hari = Carbon::parse($agenda->start_time)->locale('id')->isoFormat('dddd, D MMMM Y');
            $jam = Carbon::parse($agenda->start_time)->format('H:i') . ' WIB';
            $judulPesan = ($agenda->type == 'meeting_room') ? "RUANGAN AKAN DIGUNAKAN" : "PENGINGAT AGENDA";

            $message = "🔔 *{$judulPesan} (30 Menit Lagi)* 🔔\n"
                . "_Pesan untuk: {$groupName}_\n\n"
                . "📌 *Kegiatan:* {$agenda->title}\n"
                . "📅 *Hari:* {$hari}\n"
                . "⏰ *Jam:* {$jam}\n"
                . "📍 *Lokasi:* {$agenda->location}\n"
                . "👥 *Peserta:* " . implode(', ', $agenda->participants ?? ['-']) . "\n\n"
                . "Mohon persiapan kehadirannya.";

            Http::withHeaders(['Authorization' => $token])
                ->post('https://api.fonnte.com/send', [
                    'target' => $targetID,
                    'message' => $message,
                ]);
        } catch (\Exception $e) {
            \Log::error("Gagal kirim WA: " . $e->getMessage());
        }
    }
}