<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PlayerListController;
use App\Http\Controllers\ScoreboardController;
use Illuminate\Support\Facades\Route;

// Guest Routes (Accessible only if logged out)
Route::middleware('guest')->group(function () {
    Route::view('/register', 'auth.register')->name('register.view');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::view('/forgot', 'auth.forgot')->name('forgot.view');

    Route::view('/login', 'auth.login')->name('login.view');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// Protected Routes (Accessible only if logged in)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('scoreboards', ScoreboardController::class);
    Route::get('/scoreboards/{scoreboard}/preview', [ScoreboardController::class, 'preview'])->name('scoreboards.preview');
    Route::resource('countries', CountryController::class)->except(['show']);
    Route::resource('players', PlayerController::class);
    Route::post('/player_lists/import/preview', [PlayerListController::class, 'preview'])->name('player_lists.import.preview');
    Route::post('/player_lists/import/confirm', [PlayerListController::class, 'confirm'])->name('player_lists.import.confirm');
    Route::put('/player_lists/{playerList}/enable', [PlayerListController::class, 'enable'])->name('player_lists.enable');
    Route::put('/player_lists/{playerList}/disable', [PlayerListController::class, 'disable'])->name('player_lists.disable');
    Route::resource('/player_lists', PlayerListController::class);

});

Route::get('/', function () {
    return view('home');
})->name('home');
