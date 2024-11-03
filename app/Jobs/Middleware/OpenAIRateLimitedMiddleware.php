<?php

namespace App\Jobs\Middleware;

use Closure;
use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Support\Facades\Redis;

class OpenAIRateLimitedMiddleware
{
    /**
     * Process the queued job.
     *
     * @param  \Closure(object): void  $next
     *
     * @throws LimiterTimeoutException
     */
    public function handle(object $job, Closure $next): void
    {
        Redis::throttle('openai:rate-limit:jobs')
            ->block(0)->allow(4)->every(60)
            ->then(function () use ($job, $next) {
                $next($job);
            }, function () use ($job) {
                $job->release(12);
            });
    }
}
