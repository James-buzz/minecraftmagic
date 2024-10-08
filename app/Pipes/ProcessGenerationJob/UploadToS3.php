<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use App\Services\GenerationCreationService;
use Closure;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Exceptions\CouldNotLoadImage;
use Spatie\Image\Image;

readonly class UploadToS3
{
    /**
     * @throws CouldNotLoadImage
     */
    public function handle(mixed $data, Closure $next)
    {
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

        return $next($data);
    }
}
