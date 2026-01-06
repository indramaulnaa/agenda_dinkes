<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('agendas', function (Blueprint $table) {
        $table->id();
        $table->string('title');            // Judul Kegiatan
        $table->text('description')->nullable(); // Detail (Boleh kosong)
        $table->string('location');         // Lokasi (Aula, Lapangan, dll)
        $table->dateTime('start_time');     // Waktu Mulai
        $table->dateTime('end_time');       // Waktu Selesai
        $table->boolean('is_reminder_sent')->default(false); // Status Notif WA
        $table->timestamps();               // Created_at & Updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
