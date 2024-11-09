<?php

namespace Tests\Unit\Actions\Thumbnail;

use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;

class HandleTest extends BaseThumbnail
{
    public function testWhenFileThenThumbnail(): void
    {
        // Given
        $givenImagePath = 'fake/path/to/image.png';
        $givenThumbnailPath = 'fake/path/to/thumbnail.png';
        $givenThumbnailSize = 200;

        // Mock
        Storage::shouldReceive('path')
            ->with($givenImagePath)
            ->once()
            ->andReturn($givenImagePath);

        Storage::shouldReceive('path')
            ->with($givenThumbnailPath)
            ->once()
            ->andReturn($givenThumbnailPath);

        $imageFacade = $this->mock('alias:'.Image::class);
        $imageFacade->shouldReceive('load')
            ->with($givenImagePath)
            ->once()
            ->andReturnSelf();

        $imageFacade->shouldReceive('width')
            ->once()
            ->andReturnSelf();

        $imageFacade->shouldReceive('save')
            ->with($givenThumbnailPath)
            ->once();

        // Action
        $this->action->handle(
            $givenImagePath,
            $givenThumbnailPath,
            $givenThumbnailSize
        );
    }
}
