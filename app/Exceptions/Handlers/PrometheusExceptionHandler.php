<?php

namespace App\Exceptions\Handlers;

use App\Facades\Prometheus;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Validation\ValidationException;
use Throwable;

class PrometheusExceptionHandler
{
    public static function handles(Exceptions $exceptions): void
    {
        $exceptions->report(function (Throwable $e) {
            static $counterExceptionTotal;
            static $counterValidationErrors;

            if (! $counterExceptionTotal) {
                $counterExceptionTotal = Prometheus::getOrRegisterCounter(
                    'app',
                    'exceptions_total',
                    'Total number of exceptions',
                    ['exception_class', 'route', 'method']
                );
            }

            if (! $counterValidationErrors) {
                $counterValidationErrors = Prometheus::getOrRegisterCounter(
                    'app',
                    'validation_errors_total',
                    'Total number of validation errors',
                    ['field', 'route']
                );
            }

            $request = request();
            $route = $request->route() ? $request->route()->getName() ?? 'unnamed' : 'unknown';

            $counterExceptionTotal->inc([
                'exception_class' => get_class($e),
                'route' => $route,
                'method' => $request->method(),
            ]);

            if ($e instanceof ValidationException) {
                foreach ($e->errors() as $field => $errors) {
                    $counterValidationErrors->inc([
                        'field' => $field,
                        'route' => $route,
                    ]);
                }
            }

            return true;
        });
    }
}
