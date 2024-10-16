<?php

namespace Tests\Unit\Services\GenerationCreationService;

use App\Exceptions\GenerationNotFoundException;

class SetGenerationAsProcessingTest extends BaseGenerationCreationService
{
    /**
     * @throws GenerationNotFoundException
     */
    public function testWhenInputThenUpdate(): void
    {
        // Given
        $givenGenerationId = 'generation_id';
        $givenStatus = 'processing';

        // Mock
        $this->generationRepository->shouldReceive('update')
            ->once()
            ->with(
                $givenGenerationId,
                [
                    'status' => $givenStatus,
                ]
            );

        // Action
        $this->service->setGenerationAsProcessing($givenGenerationId);
    }
}
