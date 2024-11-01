<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Prometheus\CollectorRegistry;
use Prometheus\Counter;
use Prometheus\Gauge;
use Prometheus\Histogram;

/**
 * @method static Counter|null getCounter(string $namespace, string $name)
 * @method static Counter getOrRegisterCounter(string $namespace, string $name, string $help, array $labels = [])
 * @method static Gauge|null getGauge(string $namespace, string $name)
 * @method static Gauge getOrRegisterGauge(string $namespace, string $name, string $help, array $labels = [])
 * @method static Histogram|null getHistogram(string $namespace, string $name)
 * @method static Histogram getOrRegisterHistogram(string $namespace, string $name, string $help, array $labels = [], array $buckets = null)
 * @method static void register(mixed $collector)
 * @method static array getMetricFamilySamples()
 * @method static void wipeStorage()
 *
 * @see CollectorRegistry
 *
 * @mixin CollectorRegistry
 */
class Prometheus extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CollectorRegistry::class;
    }
}
