<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LoggingContextMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $context = [
            'correlation_id' => Str::uuid()->toString(),
        ];
        if ($request->user()) {
            $context['user_id'] = $request->user()->id;
        }

        Log::shareContext(array_filter($context));

        $response = $next($request);
        $response->headers->set('X-Correlation-ID', $context['correlation_id']);

        return $response;
    }
}
