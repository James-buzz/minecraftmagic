<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use App\Services\GenerationCreationService;
use Closure;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Exceptions\CouldNotLoadImage;
use Spatie\Image\Image;

readonly class CleanupLocal
{
    /**
     * @throws CouldNotLoadImage
     */
    public function handle(mixed $data, Closure $next)
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
