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
        $generation = $event->generation;
        $totalDuration = $event->totalDuration;

        $artStyle = $generation->style->name;
        $artType = $generation->style->type->name;

        // Total
        $failedCounter = Prometheus::getOrRegisterCounter(
            'app',
            'generation_failed_total',
            'Number of generations failed',
            ['art_type', 'art_style']
        );
        $failedCounter->inc([
            'art_type' => $artType,
            'art_style' => $artStyle,
        ]);

        // Duration
        $durationGauge = Prometheus::getOrRegisterGauge(
            'app',
            'generation_failed_duration_seconds',
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
