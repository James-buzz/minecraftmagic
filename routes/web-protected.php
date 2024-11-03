<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GenerateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatusController;
use App\Http\Middleware\LimitOneGenerationConcurrently;
use App\Http\Middleware\LimitTotalGenerationPerDay;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/generate', [GenerateController::class, 'index'])->name('generate.index');
    Route::post('/generate', [GenerateController::class, 'store'])->middleware([LimitTotalGenerationPerDay::class, LimitOneGenerationConcurrently::class])->name('generate.store');
    Route::get('/status/{id}', [StatusController::class, 'show'])->name('status');
    Route::get('/download/{id}', [DownloadController::class, 'show'])->name('download');
    Route::post('/feedback/{id}', [FeedbackController::class, 'store'])->name('feedback.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
