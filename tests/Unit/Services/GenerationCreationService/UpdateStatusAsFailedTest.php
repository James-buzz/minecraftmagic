<?php

namespace Tests\Unit\Services\GenerationCreationService;

use App\Models\Generation;
use App\Models\User;
use Illuminate\Support\Str;

class UpdateStatusAsFailedTest extends BaseGenerationCreationService
{
    public function testWhenErrorReasonThenUpdateToFailed(): void
    {
        // Given
        $givenUserId = 303;
        $givenGenerationId = Str::ulid();
        $givenPrevStatus = 'processing';
        $givenStatus = 'failed';
        $givenFailedReason = 'A reason this failed';

        // Precondition
        User::factory()->create([
            'id' => $givenUserId,
        ]);

        Generation::factory()->create([
            'id' => $givenGenerationId,
            'user_id' => $givenUserId,
            'status' => $givenPrevStatus,
        ]);

        // Expected
        $expectedGenerationId = $givenGenerationId;
        $expectedStatus = $givenStatus;
        $expectedErrorReason = $givenFailedReason;

        // Action
        $this->service->updateStatusAsFailed($givenGenerationId, $givenFailedReason);

        // Assert
        $this->assertDatabaseHas('generations', [
            'id' => $expectedGenerationId,
            'status' => $expectedStatus,
            'failed_reason' => $expectedErrorReason,
        ]);
    }
}
