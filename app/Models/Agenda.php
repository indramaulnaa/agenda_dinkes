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
        'description',
        'location',
        'start_time',
        'end_time',
        'is_reminder_sent'
    ];
}
