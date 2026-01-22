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
        
        $participants = $agenda->participants ?? []; 
        
        if (empty($participants)) {
            return; 
        }

        $groupConfig = config('whatsapp.groups');

        foreach ($participants as $participantName) {
            if (isset($groupConfig[$participantName]) && !empty($groupConfig[$participantName])) {
                
                $targetID = $groupConfig[$participantName];
                
                $this->sendMessage($token, $targetID, $agenda, $participantName);
                sleep(3); // Jeda anti-spam
            }
        }
    }

    private function sendMessage($token, $targetID, $agenda, $groupName)
    {
        try {
            // Format Waktu Indonesia
            $hari = Carbon::parse($agenda->start_time)->locale('id')->isoFormat('dddd, D MMMM Y');
            $jam = Carbon::parse($agenda->start_time)->format('H:i') . ' WIB';
            
            // Judul Dinamis (Agenda vs Booking Ruangan)
            $judulPesan = ($agenda->type == 'meeting_room') ? "PENGINGAT RUANGAN" : "PENGINGAT AGENDA";

            // Format Pesan Baru Sesuai Request
            $message = "🔔 *{$judulPesan} (30 Menit Lagi)* \n"
                . "_Pesan untuk: {$groupName}_\n\n"
                . "Yth. Bapak/Ibu,\n"
                . "Diingatkan bahwa kegiatan berikut akan dimulai dalam 30 menit:\n\n"
                . "*Kegiatan:* {$agenda->title}\n"
                . "*Hari:* {$hari}\n"
                . "*Jam:* {$jam}\n"
                . "*Lokasi:* {$agenda->location}\n"
                . "*Peserta:* " . implode(', ', $agenda->participants ?? ['-']) . "\n\n"
                . "*Detail Agenda:*\n"
                . ($agenda->description ? $agenda->description : "-") . "\n\n"
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