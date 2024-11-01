<?php

namespace App\Listeners\Prometheus\Generation;

use App\Events\Generation\GenerationStarted;
use App\Facades\Prometheus;

class GenerationStartedListener
{
    /**
     * Handle the event.
     */
    public function handle(GenerationStarted $event): void
    {
        $artType = $event->artType;
        $artStyle = $event->artStyle;

        // Total
        $totalCounter = Prometheus::getOrRegisterCounter(
            'app',
            'generation_started_total',
            'Number of generations started total',
            ['art_type', 'art_style']
        );
        $totalCounter->inc([
            'art_type' => $artType,
            'art_style' => $artStyle,
        ]);
    }
}
