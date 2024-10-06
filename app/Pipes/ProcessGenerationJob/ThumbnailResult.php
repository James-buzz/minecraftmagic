<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use App\Services\GenerationCreationService;
use Closure;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Exceptions\CouldNotLoadImage;
use Spatie\Image\Image;

readonly class ThumbnailResult
{
    private const THUMBNAIL_WIDTH = 300;

    public function __construct(protected GenerationCreationService $creationService)
    {
    }

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

        $storagePath = Storage::path($contextFilePath);
        $storageThumbnailPath = Storage::path($thumbnailFilePath);

        Image::load($storagePath)
            ->width(self::THUMBNAIL_WIDTH)
            ->save($storageThumbnailPath);

        $data['result']['thumbnail_file_path'] = $thumbnailFilePath;

        return $next($data);
    }
}
