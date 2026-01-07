<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;

Route::get('/', [AgendaController::class, 'dashboard'])->name('home');
Route::get('/agenda-feed', [AgendaController::class, 'feed'])->name('agenda.feed');
Route::get('/dashboard', [App\Http\Controllers\AgendaController::class, 'dashboard'])->name('dashboard');

Route::resource('agenda', AgendaController::class);
