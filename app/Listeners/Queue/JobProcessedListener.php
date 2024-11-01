<?php

namespace App\Listeners\Queue;

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

        $gauge = Prometheus::getOrRegisterGauge(
            config('app.name'),
            'queue_jobs_total_processed',
            'Number of jobs that have been processed',
            ['job', 'queue']
        );

        $gauge->inc([$jobName, $queueName]);
    }

    private function getJobName(string $jobClass): string
    {
        return class_basename($jobClass);
    }
}
