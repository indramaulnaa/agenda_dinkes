<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    // Izinkan kolom-kolom ini diisi data
    protected $fillable = [
        'title',
        'start_time',
        'end_time',
        'location',
        'participants',       // <--- Baru
        'description',
        'is_whatsapp_notify'  // <--- Baru
    ];

    protected $casts = [
        'participants' => 'array', // Agar otomatis jadi Array saat diambil
        'is_whatsapp_notify' => 'boolean',
    ];
}
