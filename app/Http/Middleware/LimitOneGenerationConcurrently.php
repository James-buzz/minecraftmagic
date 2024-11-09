<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LimitOneGenerationConcurrently
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return response('Unauthorized', 401);
        }

        if ($request->user()->isAdmin()) {
            return $next($request);
        }

        $hasProcessing = $request->user()->generations()
            ->where(function ($query) {
                $query->whereDate('created_at', Carbon::today())
                    ->orWhere('created_at', '>=', Carbon::now()->subHours(48));
            })
            ->whereNotIn('status', ['completed', 'failed'])->exists();

        if ($hasProcessing) {
            return back()->with('error', 'You are limited to one generation at a time. Please wait until the current generation is completed.');
        }

        return $next($request);
    }
}
