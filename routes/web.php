<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

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
