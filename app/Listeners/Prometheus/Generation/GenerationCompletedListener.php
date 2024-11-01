<?php

namespace App\Listeners\Prometheus\Generation;

use App\Events\Generation\GenerationCompleted;
use App\Facades\Prometheus;

class GenerationCompletedListener
{
    /**
     * Handle the event.
     */
    public function handle(GenerationCompleted $event): void
    {
        $artType = $event->artType;
        $artStyle = $event->artStyle;
        $totalDuration = $event->totalDuration;

        // Total
        $totalCounter = Prometheus::getOrRegisterCounter(
            'app',
            'generation_completed_total',
            'Number of generations completed',
            ['art_type', 'art_style']
        );
        $totalCounter->inc([
            'art_type' => $artType,
            'art_style' => $artStyle,
        ]);

        // Duration
        $durationGauge = Prometheus::getOrRegisterGauge(
            'app',
            'generation_completed_duration_seconds',
            'Duration of generation requests',
            ['art_type', 'art_style']
        );

        $durationInSeconds = $totalDuration / 1_000_000;
        $durationGauge->set($durationInSeconds, [
            'art_type' => $artType,
            'art_style' => $artStyle,
        ]);
    }
}
