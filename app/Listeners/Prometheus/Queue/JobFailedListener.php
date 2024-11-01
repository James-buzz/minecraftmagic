<?php

namespace App\Listeners\Prometheus\Queue;

use App\Facades\Prometheus;
use Illuminate\Queue\Events\JobFailed;

class JobFailedListener
{
    /**
     * Handle the event.
     */
    public function handle(JobFailed $event): void
    {
        $jobName = $this->getJobName($event->job->resolveName());
        $queueName = $event->job->getQueue();

        $counter = Prometheus::getOrRegisterCounter(
            'app',
            'queue_jobs_failed_total',
            'Number of jobs that have failed',
            ['job', 'queue']
        );

        $counter->inc([$jobName, $queueName]);
    }

    private function getJobName(string $jobClass): string
    {
        return class_basename($jobClass);
    }
}
