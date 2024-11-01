<?php

namespace App\Listeners\Queue;

use App\Facades\Prometheus;
use Illuminate\Queue\Events\JobRetryRequested;

class JobRetryRequestedListener
{
    /**
     * Handle the event.
     */
    public function handle(JobRetryRequested $event): void
    {
        $gauge = Prometheus::getOrRegisterGauge(
            config('app.name'),
            'queue_jobs_total_retries',
            'Number of jobs that have been retried'
        );

        $gauge->inc();
    }
}
