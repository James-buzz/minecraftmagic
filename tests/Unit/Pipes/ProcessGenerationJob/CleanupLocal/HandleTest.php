<?php

namespace Tests\Unit\Pipes\ProcessGenerationJob\CleanupLocal;

use Illuminate\Support\Facades\Storage;

class HandleTest extends BaseCleanupLocal
{
    public function testWhenPassedThenReturnCorrectData()
    {
        // Given
        $givenContextFilePath = 'original_image.png';
        $givenContextThumbnailPath = 'thumbnail_image.png';
        $givenData = [
            'generation' => [
                'id' => 1,
                'status' => 'pending',
            ],
            'result' => [
                'file_path' => $givenContextFilePath,
                'thumbnail_file_path' => $givenContextThumbnailPath,
            ],
        ];

        // Mock
        Storage::shouldReceive('disk')
            ->with('local')
            ->andReturnSelf();

        Storage::shouldReceive('delete')
            ->with($givenContextFilePath)
            ->andReturn(true);

        Storage::shouldReceive('delete')
            ->with($givenContextThumbnailPath)
            ->andReturn(true);

        // Expected
        $expectedOutputDataResult = [
            'file_path' => $givenContextFilePath,
            'thumbnail_file_path' => $givenContextThumbnailPath,
        ];

        // Action
        $this->pipe->handle($givenData, function ($actualData) use ($expectedOutputDataResult) {
            // Assert
            $this->assertEquals($expectedOutputDataResult, $actualData['result']);
        });
    }
}
