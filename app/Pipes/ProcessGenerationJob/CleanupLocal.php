<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

readonly class CleanupLocal
{
    public function handle(mixed $data, Closure $next): mixed
    {
        $stepStartTime = microtime(true);

        $contextFilePath = $data['result']['file_path'];
        $contextThumbnailFilePath = $data['result']['thumbnail_file_path'];

        Log::info('Queue cleaning up local files', [
            'generation_id' => $data['generation']['id'],
        ]);

        Storage::disk('local')
            ->delete($contextFilePath);

        Storage::disk('local')
            ->delete($contextThumbnailFilePath);

        $stepEndTime = microtime(true);
        $data['steps']['cleanup'] = $stepEndTime - $stepStartTime;

        return $next($data);
    }
}
