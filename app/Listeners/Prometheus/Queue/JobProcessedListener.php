<?php

namespace App\Listeners\Prometheus\Queue;

use App\Facades\Prometheus;
use Illuminate\Queue\Events\JobProcessed;

class JobProcessedListener
{
    /**
     * Handle the event.
     */
    public function handle(JobProcessed $event): void
    {
        $jobName = $this->getJobName($event->job->resolveName());
        $queueName = $event->job->getQueue();

        // Total
        $counter = Prometheus::getOrRegisterCounter(
            'app',
            'queue_jobs_processed_total',
            'Number of jobs that have been processed',
            ['job', 'queue']
        );
        $counter->inc([$jobName, $queueName]);
    }

    private function getJobName(string $jobClass): string
    {
        return class_basename($jobClass);
    }
}
