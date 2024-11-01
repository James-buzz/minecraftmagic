<?php

namespace App\Events\Generation;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class GenerationCompleted
{
    use Dispatchable;
    use InteractsWithSockets;

    /**
     * Create a new event instance.
     *
     * @param  array<string, float>  $stepTimes
     */
    public function __construct(
        public readonly string $artType,
        public readonly string $artStyle,
        public readonly float $totalDuration,
        public readonly array $stepTimes,
    ) {}
}
