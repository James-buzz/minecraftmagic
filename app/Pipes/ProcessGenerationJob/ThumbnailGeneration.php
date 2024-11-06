<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use App\Contracts\GenerationServiceInterface;
use App\Models\Generation;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Exceptions\CouldNotLoadImage;
use Spatie\Image\Image;

readonly class ThumbnailGeneration
{
    private const THUMBNAIL_WIDTH = 300;

    public function __construct(protected GenerationServiceInterface $creationService) {}

    /**
     * @throws CouldNotLoadImage
     */
    public function handle(mixed $data, Closure $next): mixed
    {
        $stepStartTime = microtime(true);

        $contextFilePath = $data['result']['file_path'];

        $contextGeneration = $data['generation'];
        $generation = Generation::find($contextGeneration['id']);

        $thumbnailFilePath = $this->creationService->getGenerationThumbnailFilePath($generation);

        $storagePath = Storage::disk('local')->path($contextFilePath);
        $storageThumbnailPath = Storage::disk('local')->path($thumbnailFilePath);

        $data['result']['thumbnail_file_path'] = $thumbnailFilePath;

        Image::load($storagePath)
            ->width(self::THUMBNAIL_WIDTH)
            ->save($storageThumbnailPath);

        $stepEndTime = microtime(true);
        $data['steps']['thumbnail'] = $stepEndTime - $stepStartTime;

        return $next($data);
    }
}
