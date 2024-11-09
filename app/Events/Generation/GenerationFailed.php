<?php

namespace App\Events\Generation;

use App\Models\Generation;
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
        public readonly Generation $generation,
        public readonly Throwable $exception,
        public readonly float $totalDuration,
    ) {}
}
