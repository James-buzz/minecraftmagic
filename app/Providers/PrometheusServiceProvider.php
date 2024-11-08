<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;
use Prometheus\Storage\Redis;

class PrometheusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CollectorRegistry::class, function () {
            if (app()->environment('testing')) {
                return new CollectorRegistry(new InMemory);
            }

            Redis::setDefaultOptions(
                Arr::only(
                    config('database.redis.default'),
                    ['host', 'port', 'password']
                )
            );

            return CollectorRegistry::getDefault();
        });
    }
}
