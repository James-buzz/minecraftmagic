<?php

namespace Tests\Unit\Services\GenerationCreationService;

use App\Exceptions\GenerationNotFoundException;

class SetGenerationAsCompletedTest extends BaseGenerationCreationService
{
    /**
     * @throws GenerationNotFoundException
     */
    public function testWhenInputThenUpdate(): void
    {
        // Given
        $givenGenerationId = 'generation_id';
        $givenFilePath = 'file_path';
        $givenThumbnailFilePath = 'thumbnail_file_path';
        $givenStatus = 'completed';

        // Mock
        $this->generationRepository->shouldReceive('update')
            ->once()
            ->with(
                $givenGenerationId,
                [
                    'status' => $givenStatus,
                    'file_path' => $givenFilePath,
                    'thumbnail_file_path' => $givenThumbnailFilePath,
                ]
            );

        // Action
        $this->service->setGenerationAsCompleted($givenGenerationId, $givenFilePath, $givenThumbnailFilePath);
    }
}
