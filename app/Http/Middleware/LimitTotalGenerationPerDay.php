<?php

namespace App\Http\Middleware;

use App\Models\Generation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class LimitTotalGenerationPerDay
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return response('Unauthorized', 401);
        }

        // temporary
        $count = Generation::where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereDate('created_at', Carbon::today())
                    ->orWhere('created_at', '>=', Carbon::now()->subHours(24));
            })
            ->whereNotIn('status', ['failed'])
            ->count();

        if ($count > 2) {
            return back()->with('error', 'You are limited to three generations per day. Please try again later.');
        }

        return $next($request);
    }
}
