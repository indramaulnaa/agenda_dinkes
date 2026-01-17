<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\MeetingRoomController;
use App\Http\Controllers\PinController; // Controller PIN Baru
use App\Http\Middleware\CheckPegawaiPin; // Middleware PIN Baru
use App\Models\Agenda; 
use Carbon\Carbon;

// --- GROUP 1: SISTEM PIN (Pintu Gerbang) ---
// Route ini harus terbuka agar user bisa memasukkan PIN
Route::get('/enter-pin', [PinController::class, 'showForm'])->name('pin.form');
Route::post('/verify-pin', [PinController::class, 'verify'])->name('pin.verify');


// --- GROUP 2: HALAMAN PEGAWAI (DIPROTEKSI PIN) ---
// User harus masukkan PIN dulu (atau login admin) baru bisa akses route di dalam grup ini
Route::middleware([CheckPegawaiPin::class])->group(function () {

    // 1. Home Page (Halaman Pegawai) dengan Logika Cek Ruangan
    Route::get('/', function () {
        // Daftar Ruangan yang sesuai dengan Dropdown di halaman Admin
        $rooms = [
            'Aula Utama',
            'Ruang Rapat A',
            'Ruang Rapat B',
            'Ruang Diskusi Kecil'
        ];

        $roomStatus = [];
        $now = Carbon::now('Asia/Jakarta'); // Waktu Indonesia Barat

        foreach ($rooms as $room) {
            // Cek apakah ada agenda di ruangan ini PADA JAM SEKARANG
            $currentAgenda = Agenda::where('location', $room)
                ->where('start_time', '<=', $now)
                ->where(function ($query) use ($now) {
                    $query->where('end_time', '>=', $now)
                          ->orWhereNull('end_time'); 
                })
                ->first();

            if ($currentAgenda) {
                // Jika ada agenda -> Status DIPAKAI
                // Cek Privasi: Jika Rahasia & Bukan Admin -> Samarkan
                $agendaTitle = $currentAgenda->title;
                if ($currentAgenda->is_secret && !auth()->check()) {
                    $agendaTitle = "🔒 Booked (Private)";
                }

                $roomStatus[] = [
                    'name' => $room,
                    'status' => 'dipakai',
                    'agenda' => $agendaTitle,
                    'until' => $currentAgenda->end_time ? Carbon::parse($currentAgenda->end_time)->format('H:i') : 'Selesai'
                ];
            } else {
                // Jika tidak ada agenda -> Status TERSEDIA
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
    // Ini juga diproteksi PIN agar tidak bisa ditembak langsung via URL
    Route::get('/agenda-feed', [AgendaController::class, 'feed'])->name('agenda.feed');
});


// --- GROUP 3: AUTHENTICATION (Login Admin) ---

// Tamu (Guest) hanya bisa akses halaman login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- GROUP 4: ADMIN AREA (DIKUNCI / Middleware Auth) ---
// Semua route di dalam sini HANYA bisa diakses jika sudah Login sebagai Admin
Route::middleware(['auth'])->group(function () {
    
    // 1. Dashboard
    Route::get('/dashboard', [AgendaController::class, 'dashboard'])->name('dashboard');
    
    // 2. CRUD Agenda (Create, Read, Update, Delete)
    Route::resource('agenda', AgendaController::class);

    // 3. Meeting Room Management
    Route::get('/meeting-room', [MeetingRoomController::class, 'index'])->name('meeting-room.index');
    Route::post('/meeting-room', [MeetingRoomController::class, 'store'])->name('meeting-room.store');
    
    // Route PUT untuk Update Meeting Room (Perbaikan Bug Edit Bentrok)
    Route::put('/meeting-room/{id}', [MeetingRoomController::class, 'update'])->name('meeting-room.update');
    
    Route::delete('/meeting-room/{id}', [AgendaController::class, 'destroy'])->name('meeting-room.destroy'); // Delete pakai controller Agenda karena model sama

    // 4. Staff Data Management
    Route::resource('staff', StaffController::class);

    // 5. Settings Application
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
});