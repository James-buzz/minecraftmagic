<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use Closure;
use Illuminate\Support\Facades\Storage;

readonly class UploadToS3
{
    public function handle(mixed $data, Closure $next): mixed
    {
        $stepStartTime = microtime(true);

        $contextFilePath = $data['result']['file_path'];
        $contextThumbnailFilePath = $data['result']['thumbnail_file_path'];

        Storage::disk('s3')->put(
            $contextFilePath,
            Storage::disk('local')->get($contextFilePath)
        );

        Storage::disk('s3')->put(
            $contextThumbnailFilePath,
            Storage::disk('local')->get($contextThumbnailFilePath)
        );

        $stepEndTime = microtime(true);
        $data['steps']['upload'] = $stepEndTime - $stepStartTime;

        return $next($data);
    }
}
