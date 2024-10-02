<?php
declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use Closure;

class ThumbnailResult
{
    public function handle(mixed $data, Closure $next) {
        return $next($data);
    }
}
