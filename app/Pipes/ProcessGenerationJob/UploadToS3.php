<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

readonly class UploadToS3
{
    public function handle(mixed $data, Closure $next): mixed
    {
        $stepStartTime = microtime(true);

        $contextGeneration = $data['generation'];

        $contextFilePath = $data['result']['file_path'];
        $contextThumbnailFilePath = $data['result']['thumbnail_file_path'];

        Log::info('Queue uploading generation image to S3', [
            'generation_id' => $contextGeneration['id'],
            'file_path' => $contextFilePath,
            'thumbnail_file_path' => $contextThumbnailFilePath,
        ]);

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
