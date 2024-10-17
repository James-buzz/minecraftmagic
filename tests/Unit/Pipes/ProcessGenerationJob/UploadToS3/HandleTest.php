<?php

namespace Tests\Unit\Pipes\ProcessGenerationJob\UploadToS3;

use Illuminate\Support\Facades\Storage;

class HandleTest extends BaseUploadToS3
{
    public function testWhenPassedThenReturnCorrectData(): void
    {
        // Given
        $givenContextUserId = '1';
        $givenContextGenerationId = '11';
        $givenContextFilePath = 'path/to/file';
        $givenGenerationThumbnailFilePath = 'path/to/generation/thumbnail';
        $givenFileContent = 'file content';
        $givenThumbnailFileContent = 'thumbnail file content';
        $givenData = [
            'user' => $givenContextUserId,
            'generation' => [
                'id' => $givenContextGenerationId,
            ],
            'result' => [
                'file_path' => $givenContextFilePath,
                'thumbnail_file_path' => $givenGenerationThumbnailFilePath,
            ],
        ];

        // Mock
        Storage::shouldReceive('disk')
            ->with('s3')
            ->andReturnSelf();
        Storage::shouldReceive('disk')
            ->with('local')
            ->andReturnSelf();
        Storage::shouldReceive('get')
            ->with($givenContextFilePath)
            ->andReturn($givenFileContent);
        Storage::shouldReceive('get')
            ->with($givenGenerationThumbnailFilePath)
            ->andReturn($givenThumbnailFileContent);
        Storage::shouldReceive('put')
            ->with($givenContextFilePath, $givenFileContent)
            ->andReturnTrue();
        Storage::shouldReceive('put')
            ->with($givenGenerationThumbnailFilePath, $givenThumbnailFileContent)
            ->andReturnTrue();
        Storage::shouldReceive('put')
            ->with($givenGenerationThumbnailFilePath, $givenThumbnailFileContent)
            ->andReturnTrue();

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
