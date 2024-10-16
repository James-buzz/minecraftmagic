<?php

namespace Tests\Unit\Services\GenerationCreationService;

use App\Exceptions\GenerationNotFoundException;

class SetGenerationAsFailedTest extends BaseGenerationCreationService
{
    /**
     * @throws GenerationNotFoundException
     */
    public function testWhenInputThenUpdate(): void
    {
        // Given
        $givenGenerationId = 'generation_id';
        $givenStatus = 'failed';

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
        $this->service->setGenerationAsFailed($givenGenerationId);
    }
}
