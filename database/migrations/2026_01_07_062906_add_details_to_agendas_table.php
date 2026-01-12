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
        Schema::table('agendas', function (Blueprint $table) {
        // Kolom untuk menyimpan target peserta (misal: "Seluruh Pegawai")
        $table->string('participants')->nullable()->after('location');

        // Kolom boolean untuk status notifikasi WA (0 = mati, 1 = hidup)
        $table->boolean('is_whatsapp_notify')->default(false)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
        $table->dropColumn(['participants', 'is_whatsapp_notify']);
        });
    }
};
