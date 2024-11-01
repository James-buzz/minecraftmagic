<?php

namespace App\Listeners\Prometheus\Generation;

use App\Events\Generation\GenerationStarted;
use App\Facades\Prometheus;

class GenerationQueuedListener
{
    /**
     * Handle the event.
     */
    public function handle(GenerationStarted $event): void
    {
        $artType = $event->artType;
        $artStyle = $event->artStyle;

        // Total
        $gauge = Prometheus::getOrRegisterGauge(
            'app',
            'generation_queued_total',
            'Number of generations queued total',
            ['art_type', 'art_style']
        );
        $gauge->inc([
            'art_type' => $artType,
            'art_style' => $artStyle,
        ]);

        // Queued
        $gauge = Prometheus::getOrRegisterGauge(
            'app',
            'generation_queued',
            'Number of generations queued currently',
            ['art_type', 'art_style']
        );
        $gauge->inc([
            'art_type' => $artType,
            'art_style' => $artStyle,
        ]);
    }
}
