<?php

namespace Tests\Unit\Jobs\ProcessGenerationJob;

use App\Contracts\GenerationCreationServiceInterface;
use Mockery\MockInterface;

class FailedTest extends BaseProcessGenerationJob
{
    public function testWhenJobIsFailedThenUpdateGeneration(): void
    {
        // Given
        $givenGenerationId = $this->generationId;

        // Mock
        /** @var MockInterface|GenerationCreationServiceInterface $mockGenerationCreationService */
        $mockGenerationCreationService = $this->mock(GenerationCreationServiceInterface::class);
        $mockGenerationCreationService->shouldReceive('setGenerationAsFailed')
            ->with($givenGenerationId)
            ->once();

        // Action
        $this->job->failed($mockGenerationCreationService);
    }
}
