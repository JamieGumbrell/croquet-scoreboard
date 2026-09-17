<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Guest Routes (Accessible only if logged out)
Route::middleware('guest')->group(function () {
    Route::view('/register', 'auth.register')->name('register.view');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::view('/login', 'auth.login')->name('login.view');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// Protected Routes (Accessible only if logged in)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::view('/scoreboards', 'scoreboard.scoreboards')->name('scoreboards');
    Route::view('/players', 'player.players')->name('players');
    Route::view('/player_lists', 'player.player_lists')->name('player_lists');
    Route::view('/countries', 'country.countries')->name('countries');

});

Route::get('/', function () {
    return view('home');
})->name('home');
