<?php

namespace App\Listeners\Prometheus\Queue;

use App\Facades\Prometheus;
use Illuminate\Queue\Events\JobRetryRequested;

class JobRetryRequestedListener
{
    /**
     * Handle the event.
     */
    public function handle(JobRetryRequested $event): void
    {
        $counter = Prometheus::getOrRegisterCounter(
            'app',
            'queue_jobs_retries_total',
            'Number of jobs that have been retried',
            ['job']
        );

        $jobName = $this->getJobName($event->payload()['data']['commandName']);
        $counter->inc(['job' => $jobName]);
    }

    private function getJobName(string $jobClass): string
    {
        return class_basename($jobClass);
    }
}
