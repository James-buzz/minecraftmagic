<?php

namespace App\Events\Generation;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Throwable;

class GenerationFailed
{
    use Dispatchable;
    use InteractsWithSockets;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly string $artType,
        public readonly string $artStyle,
        public readonly Throwable $exception,
        public readonly float $totalDuration,
    ) {}
}
