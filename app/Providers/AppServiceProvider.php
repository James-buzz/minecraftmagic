<?php

namespace App\Providers;

use App\Models\Generation;
use App\Policies\GenerationPolicy;
use Illuminate\Support\Facades\Gate;
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
        $this->app->singleton(\App\Contracts\ArtRepositoryInterface::class, \App\Repositories\ArtRepository::class);

        // Services
        $this->app->singleton(\App\Contracts\ArtServiceInterface::class, \App\Services\ArtService::class);
        $this->app->singleton(\App\Contracts\GenerationRetrievalServiceInterface::class, \App\Services\GenerationRetrievalService::class);
        $this->app->singleton(\App\Contracts\GenerationServiceInterface::class, \App\Services\GenerationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Gate::policy(Generation::class, GenerationPolicy::class);
    }
}
