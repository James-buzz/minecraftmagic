<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use Closure;
use Illuminate\Support\Facades\Storage;

readonly class CleanupLocal
{
    public function handle(mixed $data, Closure $next): mixed
    {
        $contextFilePath = $data['result']['file_path'];
        $contextThumbnailFilePath = $data['result']['thumbnail_file_path'];

        Storage::disk('local')
            ->delete($contextFilePath);

        Storage::disk('local')
            ->delete($contextThumbnailFilePath);

        return $next($data);
    }
}
