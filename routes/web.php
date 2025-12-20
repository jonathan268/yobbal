<?php

use App\Http\Controllers\ColisController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/accueil', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/colis', [ColisController::class, 'index'])->name('colis.index');
    Route::get('/colis/creer', [ColisController::class, 'create'])->name('colis.create');
    Route::post('/colis', [ColisController::class, 'store'])->name('colis.store');

    // Afficher le formulaire de modification (On a besoin de savoir QUEL colis modifier -> {colis})
Route::get('/colis/{colis}/edit', [ColisController::class, 'edit'])->name('colis.edit');

// Traiter la mise à jour (Verbe PUT)
Route::put('/colis/{colis}', [ColisController::class, 'update'])->name('colis.update');

// Supprimer (Verbe DELETE)
Route::delete('/colis/{colis}', [ColisController::class, 'destroy'])->name('colis.destroy');
});



require __DIR__.'/auth.php';
