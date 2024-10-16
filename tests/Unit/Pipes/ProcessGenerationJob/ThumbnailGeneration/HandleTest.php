<?php

namespace Tests\Unit\Pipes\ProcessGenerationJob\ThumbnailGeneration;

use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;

class HandleTest extends BaseThumbnailGeneration
{
    public function testWhenPassedThenReturnCorrectData(): void
    {
        // Given
        $givenContextUserId = '1';
        $givenContextGenerationId = '11';
        $givenContextFilePath = 'path/to/file';
        $givenGenerationThumbnailFilePath = 'path/to/generation/thumbnail';
        $givenData = [
            'user' => $givenContextUserId,
            'generation' => [
                'id' => $givenContextGenerationId,
            ],
            'result' => [
                'file_path' => $givenContextFilePath,
            ],
        ];

        // Mock
        $this->mockCreationService
            ->shouldReceive('getGenerationThumbnailFilePath')
            ->with($givenContextUserId, $givenContextGenerationId)
            ->andReturn($givenGenerationThumbnailFilePath);

        Storage::shouldReceive('disk')
            ->with('local')
            ->andReturnSelf();

        Storage::shouldReceive('path')
            ->with($givenContextFilePath)
            ->andReturn($givenContextFilePath);

        Storage::shouldReceive('path')
            ->with($givenGenerationThumbnailFilePath)
            ->andReturn($givenGenerationThumbnailFilePath);

        $imageFacade = $this->mock('alias:'.Image::class);
        $imageFacade
            ->shouldReceive('load')
            ->with($givenContextFilePath)
            ->andReturnSelf();
        $imageFacade
            ->shouldReceive('width')
            ->with(300)
            ->andReturnSelf();

        $imageFacade
            ->shouldReceive('save')
            ->with($givenGenerationThumbnailFilePath)
            ->andReturnSelf();

        // Expected
        $expectedOutputData = [
            'user' => $givenContextUserId,
            'generation' => [
                'id' => $givenContextGenerationId,
            ],
            'result' => [
                'file_path' => $givenContextFilePath,
                'thumbnail_file_path' => $givenGenerationThumbnailFilePath,
            ],
        ];

        // Action
        $this->pipe->handle($givenData, function ($actualData) use ($expectedOutputData) {

            // Assert
            $this->assertEquals($expectedOutputData, $actualData);

        });
    }
}
