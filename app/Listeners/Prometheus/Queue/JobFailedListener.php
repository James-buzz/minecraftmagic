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

        $gauge = Prometheus::getOrRegisterGauge(
            'app',
            'queue_jobs_failed_total',
            'Number of jobs that have failed',
            ['job', 'queue']
        );

        $gauge->inc([$jobName, $queueName]);
    }

    private function getJobName(string $jobClass): string
    {
        return class_basename($jobClass);
    }
}
