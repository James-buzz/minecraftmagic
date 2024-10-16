<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use App\Contracts\GenerationCreationServiceInterface;
use Closure;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Exceptions\CouldNotLoadImage;
use Spatie\Image\Image;

readonly class ThumbnailGeneration
{
    private const THUMBNAIL_WIDTH = 300;

    public function __construct(protected GenerationCreationServiceInterface $creationService) {}

    /**
     * @throws CouldNotLoadImage
     */
    public function handle(mixed $data, Closure $next)
    {
        $contextUserId = $data['user'];
        $contextGenerationId = $data['generation']['id'];
        $contextFilePath = $data['result']['file_path'];

        $thumbnailFilePath = $this->creationService->getGenerationThumbnailFilePath(
            $contextUserId,
            $contextGenerationId
        );

        $storagePath = Storage::disk('local')
            ->path($contextFilePath);
        $storageThumbnailPath = Storage::disk('local')
            ->path($thumbnailFilePath);

        $data['result']['thumbnail_file_path'] = $thumbnailFilePath;

        Image::load($storagePath)
            ->width(self::THUMBNAIL_WIDTH)
            ->save($storageThumbnailPath);

        return $next($data);
    }
}
