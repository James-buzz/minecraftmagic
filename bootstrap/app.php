<?php

use App\Exceptions\Handlers\PrometheusExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration as SentryIntegration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            \App\Http\Middleware\LoggingContextMiddleware::class,
            \App\Http\Middleware\Prometheus\PrometheusMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        PrometheusExceptionHandler::handles($exceptions);
        SentryIntegration::handles($exceptions);
    })->create();
