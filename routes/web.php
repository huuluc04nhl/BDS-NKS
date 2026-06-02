<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;

// Homepage Route
Route::get('/', [PropertyController::class, 'home'])->name('home');

// Properties List & Interactive Map Route
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');

// Property Detail Route (by slug)
Route::get('/properties/{slug}', [PropertyController::class, 'show'])->name('properties.show');

// Profile Dashboard Route
Route::get('/profile', function () {
    return view('profile.dashboard');
})->name('profile.dashboard');
