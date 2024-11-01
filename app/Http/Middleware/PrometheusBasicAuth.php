<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrometheusBasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requiredUsername = config('prometheus.basic_auth.username');
        $requiredPassword = config('prometheus.basic_auth.password');

        abort_if(! $requiredUsername || ! $requiredPassword, 403, 'Unauthorized');

        $authHeader = $request->header('Authorization');

        if (! $authHeader) {
            return $this->unauthorizedResponse();
        }

        if (! str_starts_with($authHeader, 'Basic ')) {
            return $this->unauthorizedResponse();
        }

        $providedCredentials = base64_decode(substr($authHeader, 6));
        [$username, $password] = array_pad(explode(':', $providedCredentials), 2, null);

        if ($username === $requiredUsername && $password === $requiredPassword) {
            return $next($request);
        }

        return $this->unauthorizedResponse();
    }

    /**
     * Return unauthorized response
     *
     * @return \Illuminate\Http\Response
     */
    private function unauthorizedResponse(): Response
    {
        return new Response('Unauthorized', 401, [
            'WWW-Authenticate' => 'Basic realm="Prometheus Metrics"',
        ]);
    }
}
