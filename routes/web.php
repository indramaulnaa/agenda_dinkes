<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StaffController;

// --- GROUP 1: PUBLIK (Bisa Diakses Siapa Saja) ---
Route::get('/', [AgendaController::class, 'welcome'])->name('home');
Route::get('/agenda-feed', [AgendaController::class, 'feed'])->name('agenda.feed');

// --- GROUP 2: AUTHENTICATION (Login/Logout) ---
// Tamu (Guest) hanya bisa akses login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Logout bisa diakses siapa saja atau user auth
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- GROUP 3: ADMIN AREA (DIKUNCI / Middleware Auth) ---
// Halaman di dalam grup ini HANYA bisa diakses jika sudah Login
Route::middleware(['auth'])->group(function () {
    
    // 1. Dashboard
    Route::get('/dashboard', [AgendaController::class, 'dashboard'])->name('dashboard');
    
    // 2. CRUD Agenda (Create, Read, Update, Delete)
    Route::resource('agenda', AgendaController::class);

    // 3. Settings (PENTING: Harus di dalam sini agar aman)
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
});

Route::middleware(['auth'])->group(function () {
    // ... route dashboard & agenda ...

    // Route Staff
    Route::resource('staff', StaffController::class);
});