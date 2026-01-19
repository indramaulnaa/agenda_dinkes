<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            // Hapus kolom yang tidak jadi dipakai
            $table->dropColumn(['is_secret', 'target_groups']);
        });
    }

    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->boolean('is_secret')->default(false);
            $table->json('target_groups')->nullable();
        });
    }
};
