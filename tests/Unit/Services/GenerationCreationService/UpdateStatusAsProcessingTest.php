<?php

namespace Tests\Unit\Services\GenerationCreationService;

use App\Models\Generation;
use Illuminate\Support\Str;

class UpdateStatusAsProcessingTest extends BaseGenerationCreationService
{
    public function testWhenInputThenUpdate(): void
    {
        // Given
        $givenGenerationId = Str::ulid();
        $givenPrevStatus = 'pending';
        $givenStatus = 'processing';

        // Precondition
        Generation::factory()->create([
            'id' => $givenGenerationId,
            'status' => $givenPrevStatus,
        ]);

        // Expected
        $expectedGenerationId = $givenGenerationId;
        $expectedStatus = $givenStatus;

        // Action
        $this->service->updateStatusAsProcessing($givenGenerationId);

        // Assert
        $this->assertDatabaseHas('generations', [
            'id' => $expectedGenerationId,
            'status' => $expectedStatus,
        ]);
    }
}
