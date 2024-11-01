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
        $givenFailedMessage = null;

        // Precondition
        $preconditionThrowable = new \Exception('test');

        // Mock
        /** @var MockInterface|GenerationCreationServiceInterface $mockGenerationCreationService */
        $mockGenerationCreationService = $this->mock(GenerationCreationServiceInterface::class);
        $mockGenerationCreationService->shouldReceive('setGenerationAsFailed')
            ->with($givenGenerationId, $givenFailedMessage)
            ->once();

        // Action
        $this->job->failed(
            $preconditionThrowable
        );
    }
}
