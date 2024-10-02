<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repositories
        $this->app->singleton(\App\Contracts\ArtRepositoryInterface::class, \App\Repositories\JsonArtRepository::class);
        $this->app->singleton(\App\Contracts\ArtServiceInterface::class, \App\Services\ArtService::class);
        $this->app->singleton(\App\Contracts\GenerationRepositoryInterface::class, \App\Repositories\GenerationRepository::class);

        // Services
        $this->app->singleton(\App\Contracts\GenerationServiceInterface::class, \App\Services\GenerationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
