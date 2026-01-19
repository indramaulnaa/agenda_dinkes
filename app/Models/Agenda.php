<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',                 // 'general' atau 'meeting_room'
        'title',
        'start_time',
        'end_time',
        'location',
        'participants',
        'description',
        'is_whatsapp_notify',
        'notification_sent',    // <--- WAJIB ADA: Agar sistem bisa update status terkirim
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'participants' => 'array',
        'is_whatsapp_notify' => 'boolean',
        'notification_sent' => 'boolean', // <--- WAJIB ADA
    ];

    // Relasi ke User (Pembuat Agenda)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}