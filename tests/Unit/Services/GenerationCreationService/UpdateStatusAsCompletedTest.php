<?php

namespace Tests\Unit\Services\GenerationCreationService;

use App\Models\Generation;
use Illuminate\Support\Str;

class UpdateStatusAsCompletedTest extends BaseGenerationCreationService
{
    public function testWhenInputThenUpdate(): void
    {
        // Given
        $givenGenerationId = Str::ulid();
        $givenFilePath = 'path/to/file';
        $givenThumbnailFilePath = 'path/to/thumbnail';
        $givenPrevStatus = 'processing';
        $givenStatus = 'completed';

        // Precondition
        $preconditionGeneration = Generation::factory()->create([
            'id' => $givenGenerationId,
            'status' => $givenPrevStatus,
        ]);

        // Expected
        $expectedGenerationId = $givenGenerationId;
        $expectedStatus = $givenStatus;
        $expectedFilePath = $givenFilePath;
        $expectedThumbnailFilePath = $givenThumbnailFilePath;

        // Action
        $this->service->updateStatusAsCompleted($preconditionGeneration, $givenFilePath, $givenThumbnailFilePath);

        // Assert
        $this->assertDatabaseHas('generations', [
            'id' => $expectedGenerationId,
            'status' => $expectedStatus,
            'file_path' => $expectedFilePath,
            'thumbnail_file_path' => $expectedThumbnailFilePath,
        ]);
    }
}
