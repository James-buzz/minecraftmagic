<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Image\Exceptions\CouldNotLoadImage;
use Spatie\Image\Image;

class Thumbnail
{
    use AsAction;

    /**
     * Thumbnail an image
     *
     * @throws CouldNotLoadImage
     */
    public function handle(string $imagePath, string $thumbnailPath, int $size = 300): void
    {
        $localImagePath = Storage::path($imagePath);
        $localThumbnailPath = Storage::path($thumbnailPath);

        Image::load($localImagePath)
            ->width($size)
            ->save($localThumbnailPath);
    }
}
