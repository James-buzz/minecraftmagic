<?php

namespace Tests\Unit\Services\GenerationCreationService;

use App\Models\Generation;
use App\Models\User;

class UpdateStatusAsFailedTest extends BaseGenerationCreationService
{
    public function testWhenErrorReasonThenUpdateToFailed(): void
    {
        // Given
        $givenPrevStatus = 'processing';
        $givenStatus = 'failed';
        $givenFailedReason = 'A reason this failed';

        // Precondition
        $preconditionUser = User::factory()->create();

        $preconditionGeneration = Generation::factory()->create([
            'user_id' => $preconditionUser->id,
            'status' => $givenPrevStatus,
        ]);

        // Expected
        $expectedStatus = $givenStatus;
        $expectedErrorReason = $givenFailedReason;
        $expectedGenerationId = $preconditionGeneration->id;

        // Action
        $this->service->updateStatusAsFailed($preconditionGeneration, $givenFailedReason);

        // Assert
        $this->assertDatabaseHas('generations', [
            'id' => $expectedGenerationId,
            'status' => $expectedStatus,
            'failed_reason' => $expectedErrorReason,
        ]);
    }
}
