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
        $gauge = Prometheus::getOrRegisterGauge(
            'app',
            'generation_completed_total',
            'Number of generations completed',
            ['art_type', 'art_style', 'total_duration']
        );
        $gauge->inc([
            'art_type' => $artType,
            'art_style' => $artStyle,
            'total_duration' => $totalDuration,
        ]);

        // Processing
        $gauge = Prometheus::getOrRegisterGauge(
            'app',
            'generation_processing',
            'Number of generations processing',
            ['art_type', 'art_style']
        );
        $gauge->dec([
            'art_type' => $artType,
            'art_style' => $artStyle,
        ]);
    }
}
