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
            'result' => [
                'file_path' => $givenContextFilePath,
                'thumbnail_file_path' => $givenContextThumbnailPath,
            ],
        ];
        $givenOutputData = $givenData;

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
        $expectedOutputData = $givenOutputData;

        // Action
        $this->pipe->handle($givenData, function ($data) use ($expectedOutputData) {
            // Assert
            $this->assertEquals($expectedOutputData, $data);
        });
    }
}
