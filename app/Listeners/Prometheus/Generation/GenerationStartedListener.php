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
        $gauge = Prometheus::getOrRegisterGauge(
            'app',
            'generation_started_total',
            'Number of generations started total',
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
            'Number of generations queued',
            ['art_type', 'art_style']
        );
        $gauge->dec([
            'art_type' => $artType,
            'art_style' => $artStyle,
        ]);

        // Processing
        $gauge = Prometheus::getOrRegisterGauge(
            'app',
            'generation_processing',
            'Number of generations processing',
            ['art_type', 'art_style']
        );
        $gauge->inc([
            'art_type' => $artType,
            'art_style' => $artStyle,
        ]);
    }
}
