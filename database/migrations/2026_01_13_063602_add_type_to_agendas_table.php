<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            // Kolom untuk membedakan: 'general' (Agenda Biasa) atau 'meeting_room' (Ruang Rapat)
            $table->string('type')->default('general')->after('id'); 
        });
    }

    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};