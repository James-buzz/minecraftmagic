<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Legal\PrivacyController;
use App\Http\Controllers\Legal\TermsController;
use App\Http\Controllers\Prometheus\MetricsController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\Prometheus\PrometheusBasicAuth;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/privacy', [PrivacyController::class, 'index'])->name('privacy');
Route::get('/terms', [TermsController::class, 'index'])->name('terms');
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/metrics', MetricsController::class)->middleware(PrometheusBasicAuth::class)->name('metrics');

require __DIR__.'/web-protected.php';
require __DIR__.'/auth.php';
