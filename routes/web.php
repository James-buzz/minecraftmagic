<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');
Route::get('/privacy', [\App\Http\Controllers\Legal\PrivacyController::class, 'index'])->name('privacy');
Route::get('/terms', [\App\Http\Controllers\Legal\TermsController::class, 'index'])->name('terms');
Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about');

// (protected)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');

    Route::resource('generate', \App\Http\Controllers\GenerateController::class)
        ->only(['index', 'store'])
        ->middleware(['auth', 'verified'])->names([
            'index' => 'generate.index',
            'store' => 'generate.store',
        ]);

    Route::get('/status/{id}', [\App\Http\Controllers\StatusController::class, 'show'])
        ->name('status');

    Route::get('/download/{id}', \App\Http\Controllers\DownloadController::class)
        ->name('download');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
