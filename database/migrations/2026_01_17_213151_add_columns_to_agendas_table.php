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
            // Menambahkan kolom is_secret (untuk agenda rahasia)
            if (!Schema::hasColumn('agendas', 'is_secret')) {
                $table->boolean('is_secret')->default(false)->after('is_whatsapp_notify');
            }

            // Menambahkan kolom notification_sent (untuk status wa otomatis)
            if (!Schema::hasColumn('agendas', 'notification_sent')) {
                $table->boolean('notification_sent')->default(false)->after('is_secret');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            if (Schema::hasColumn('agendas', 'is_secret')) {
                $table->dropColumn('is_secret');
            }
            if (Schema::hasColumn('agendas', 'notification_sent')) {
                $table->dropColumn('notification_sent');
            }
        });
    }
};