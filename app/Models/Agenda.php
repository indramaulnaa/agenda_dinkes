<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    // Tambahkan semua kolom yang boleh diisi ke sini
    protected $fillable = [
        'user_id',            // <--- PENTING: Untuk menyimpan ID pembuat
        'type',               // <--- PENTING: Untuk membedakan general/meeting_room
        'title',
        'start_time',
        'end_time',
        'location',           // <--- INI YANG MENYEBABKAN ERROR SEBELUMNYA
        'description',
        'participants',
        'is_whatsapp_notify'
    ];

    protected $casts = [
        'participants' => 'array',
        'is_whatsapp_notify' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relasi ke User (Admin yang membuat)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}