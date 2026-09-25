<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ScoreboardController;
use App\Http\Controllers\PlayerListController;

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
    Route::resource('countries', CountryController::class)->except(['show']);
    Route::resource('players', PlayerController::class);
    Route::resource('/player_lists', PlayerListController::class);

});

Route::get('/', function () {
    return view('home');
})->name('home');
