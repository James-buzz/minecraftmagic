<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\PDO;

class PrometheusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CollectorRegistry::class, function () {
            $pdo = DB::connection()->getPdo();

            return new CollectorRegistry(new PDO($pdo));
        });
    }
}
