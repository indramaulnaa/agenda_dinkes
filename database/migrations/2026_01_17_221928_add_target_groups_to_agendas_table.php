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
            // Kolom JSON untuk menyimpan array grup tujuan (misal: ["Sekretariat", "Kesmas"])
            $table->json('target_groups')->nullable()->after('notification_sent');
        });
    }

    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropColumn('target_groups');
        });
    }
};
