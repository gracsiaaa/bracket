<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;

Route::get('/', [TournamentController::class, 'landing'])->name('landing');
Route::get('/live/tournaments/{id}', [TournamentController::class, 'publicShow'])->name('tournaments.public');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});



Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [TournamentController::class, 'index'])->name('tournaments.index');

    Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{id}', [TournamentController::class, 'show'])->name('tournaments.show');
    Route::delete('/tournaments/{id}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
    Route::post('/tournaments/{id}/generate', [TournamentController::class, 'generateBracket'])->name('tournaments.generate');

    Route::post('/tournaments/{id}/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::post('/matches/{matchId}/score', [TournamentController::class, 'updateScore'])->name('matches.updateScore');

    Route::get('/god-mode', [SuperAdminController::class, 'index'])->name('superadmin.index');
    Route::delete('/god-mode/tournaments/{id}', [SuperAdminController::class, 'destroyTournament'])->name('superadmin.tournament.destroy');
    Route::delete('/god-mode/users/{id}', [SuperAdminController::class, 'destroyUser'])->name('superadmin.user.destroy');
});

Route::get('/clear-system', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    return "MEMORI SERVER BERHASIL DIRESET! Silakan kembali ke halaman utama.";
});