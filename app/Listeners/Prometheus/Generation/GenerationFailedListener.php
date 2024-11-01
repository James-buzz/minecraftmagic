<?php

namespace App\Listeners\Prometheus\Generation;

use App\Events\Generation\GenerationFailed;
use App\Facades\Prometheus;

class GenerationFailedListener
{
    /**
     * Handle the event.
     */
    public function handle(GenerationFailed $event): void
    {
        $artType = $event->artType;
        $artStyle = $event->artStyle;
        $totalDuration = $event->totalDuration;

        // Total
        $gauge = Prometheus::getOrRegisterGauge(
            'app',
            'generation_failed_total',
            'Number of generations failed',
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
