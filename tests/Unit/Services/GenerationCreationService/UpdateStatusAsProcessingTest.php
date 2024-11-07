<?php

namespace Tests\Unit\Services\GenerationCreationService;

use App\Models\Generation;

class UpdateStatusAsProcessingTest extends BaseGenerationCreationService
{
    public function testWhenInputThenUpdate(): void
    {
        // Given
        $givenPrevStatus = 'pending';
        $givenStatus = 'processing';

        // Precondition
        $preconditionGeneration = Generation::factory()->create([
            'status' => $givenPrevStatus,
        ]);

        // Expected
        $expectedGenerationId = $preconditionGeneration->id;
        $expectedStatus = $givenStatus;

        // Action
        $this->service->updateStatusAsProcessing($preconditionGeneration);

        // Assert
        $this->assertDatabaseHas('generations', [
            'id' => $expectedGenerationId,
            'status' => $expectedStatus,
        ]);
    }
}
