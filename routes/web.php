<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;

Route::get('/', [AgendaController::class, 'dashboard'])->name('home');

Route::get('/agenda-feed', [AgendaController::class, 'feed'])->name('agenda.feed');

Route::resource('agenda', AgendaController::class);
