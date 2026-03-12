<?php

use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ColisController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/track', [TrackingController::class, 'index'])->name('tracking.index');

// routes/web.php
Route::get('/home', [HomeController::class, 'index'])
     ->middleware('auth')
     ->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/accueil', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   Route::resource('colis', ColisController::class)->parameters(['colis' => 'colis']);
});



require __DIR__.'/auth.php';
