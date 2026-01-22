<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\MeetingRoomController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\UserController; // Controller User Baru
use App\Http\Middleware\CheckPegawaiPin;
use App\Http\Middleware\CheckSuperAdmin; // Middleware Super Admin Baru
use App\Models\Agenda; 
use Carbon\Carbon;

// --- GROUP 1: SISTEM PIN (Pintu Gerbang) ---
Route::get('/enter-pin', [PinController::class, 'showForm'])->name('pin.form');
Route::post('/verify-pin', [PinController::class, 'verify'])->name('pin.verify');


// --- GROUP 2: HALAMAN PEGAWAI (DIPROTEKSI PIN) ---
Route::middleware([CheckPegawaiPin::class])->group(function () {

    // 1. Home Page (Halaman Pegawai) dengan Logika Cek Ruangan
    Route::get('/', function () {
        $rooms = ['Aula Utama', 'Ruang Rapat A', 'Ruang Rapat B', 'Ruang Diskusi Kecil'];
        $roomStatus = [];
        $now = Carbon::now('Asia/Jakarta');

        foreach ($rooms as $room) {
            // Cek agenda di ruangan ini PADA JAM SEKARANG
            $currentAgenda = Agenda::where('location', $room)
                ->where('start_time', '<=', $now)
                ->where(function ($query) use ($now) {
                    $query->where('end_time', '>=', $now)->orWhereNull('end_time'); 
                })->first();

            if ($currentAgenda) {
                // Status DIPAKAI
                $roomStatus[] = [
                    'name' => $room,
                    'status' => 'dipakai',
                    'agenda' => $currentAgenda->title, // Judul selalu muncul (Fitur Secret dihapus)
                    'until' => $currentAgenda->end_time ? Carbon::parse($currentAgenda->end_time)->format('H:i') : 'Selesai'
                ];
            } else {
                // Status TERSEDIA
                $roomStatus[] = [
                    'name' => $room,
                    'status' => 'tersedia',
                    'agenda' => '-',
                    'until' => '-'
                ];
            }
        }

        return view('welcome', compact('roomStatus'));
    })->name('home');

    // 2. Feed Data untuk Kalender (JSON)
    Route::get('/agenda-feed', [AgendaController::class, 'feed'])->name('agenda.feed');
});


// --- GROUP 3: AUTHENTICATION (Login Admin) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- GROUP 4: ADMIN AREA (DIKUNCI / Middleware Auth) ---
Route::middleware(['auth'])->group(function () {
    
    // 1. Dashboard
    Route::get('/dashboard', [AgendaController::class, 'dashboard'])->name('dashboard');
    
    // 2. CRUD Agenda
    Route::resource('agenda', AgendaController::class);

    // 3. Meeting Room Management
    Route::get('/meeting-room', [MeetingRoomController::class, 'index'])->name('meeting-room.index');
    Route::post('/meeting-room', [MeetingRoomController::class, 'store'])->name('meeting-room.store');
    Route::put('/meeting-room/{id}', [MeetingRoomController::class, 'update'])->name('meeting-room.update');
    Route::delete('/meeting-room/{id}', [AgendaController::class, 'destroy'])->name('meeting-room.destroy');

    // 4. Laporan & Rekapitulasi (PENGGANTI STAFF)
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [App\Http\Controllers\ReportController::class, 'print'])->name('reports.print');
    
    // 5. Settings Application
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');

    // --- GROUP 5: SUPER ADMIN AREA ---
    Route::middleware([CheckSuperAdmin::class])->group(function () {
        // Manajemen User (CRUD Lengkap untuk Super Admin)
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
    });
});