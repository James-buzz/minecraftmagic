<?php

namespace App\Console\Commands;

use App\Facades\Prometheus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class MonitorQueueSizeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monitor-queue-size-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor the queues';

    /**
     * Queues to monitor.
     *
     * @var string[]
     */
    protected array $queues = ['default'];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $gauge = Prometheus::getOrRegisterGauge(
            'app',
            'queue_size',
            'Current number of jobs in queue',
            ['queue']
        );

        foreach ($this->queues as $queue) {
            $queueSize = Queue::size($queue);

            $gauge->set(
                $queueSize,
                ['queue' => $queue]
            );
        }
    }
}
