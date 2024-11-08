<?php

namespace App\Http\Middleware\Prometheus;

use App\Facades\Prometheus;
use Closure;
use Illuminate\Http\Request;
use Prometheus\Counter;
use Prometheus\Gauge;
use Prometheus\Histogram;
use Symfony\Component\HttpFoundation\Response;

class PrometheusMiddleware
{
    protected Histogram $requestDuration;

    protected Counter $requestsTotal;

    protected Gauge $requestsInProgress;

    public function __construct()
    {
        $this->requestDuration = Prometheus::getOrRegisterHistogram(
            'app',
            'http_request_duration_seconds',
            'The HTTP request duration in seconds',
            ['route', 'method'],
            [0.01, 0.025, 0.05, 0.075, 0.1, 0.25, 0.5, 0.75, 1.0, 2.5, 5.0, 7.5, 10.0]
        );

        $this->requestsTotal = Prometheus::getOrRegisterCounter(
            'app',
            'http_requests_total',
            'Total number of HTTP requests',
            ['method', 'route', 'status_code']
        );

        $this->requestsInProgress = Prometheus::getOrRegisterGauge(
            'app',
            'http_requests_in_progress',
            'Number of requests currently being processed',
            ['method']
        );
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $track = microtime(true);

        $this->requestsInProgress->inc([
            'method' => $request->method(),
        ]);

        try {
            $response = $next($request);

            $duration = microtime(true) - $track;

            $route = $request->route() ? $request->route()->getName() ?? 'unnamed' : 'unknown';
            $statusCode = $response->getStatusCode();

            $this->requestDuration->observe(
                $duration,
                ['route' => $route, 'method' => $request->method()]
            );

            $this->requestsTotal->inc([
                'method' => $request->method(),
                'route' => $route,
                'status_code' => $statusCode,
            ]);

            return $response;
        } finally {
            $this->requestsInProgress->dec([
                'method' => $request->method(),
            ]);
        }
    }
}
