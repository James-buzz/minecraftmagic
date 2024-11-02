<?php

namespace Tests\Unit\Jobs\ProcessGenerationJob;

use App\Jobs\ProcessGenerationJob;
use App\Models\Generation;
use App\Models\User;
use Illuminate\Support\Str;
use OpenAI\Exceptions\ErrorException;

class FailedTest extends BaseProcessGenerationJob
{
    public function testWhenJobIsFailedWithGenericExceptionThenUpdateGenerationWithoutMessage(): void
    {
        // Given
        $givenGenerationId = Str::ulid();
        $givenUserId = 3;
        $givenGenerationStatus = 'processing';

        // Precondition
        $user = User::factory()->create([
            'id' => $givenUserId,
        ]);

        Generation::factory()->create([
            'id' => $givenGenerationId,
            'user_id' => $givenUserId,
            'status' => $givenGenerationStatus,
        ]);

        $preconditionThrowable = new \Exception('test');

        // Action
        $job = new ProcessGenerationJob(
            $givenUserId,
            $givenGenerationId
        );

        $job->failed(
            $preconditionThrowable
        );

        // Assert
        $this->assertDatabaseHas('generations', [
            'id' => $givenGenerationId,
            'status' => 'failed',
            'failed_reason' => null,
        ]);
    }

    public function testWhenJobIsFailedWithOpenAIExceptionThenUpdateGenerationWithMessage(): void
    {
        // Given
        $givenGenerationId = Str::ulid();
        $givenUserId = 3;
        $givenGenerationStatus = 'processing';
        $givenExceptionMessage = 'This has failed';

        // Precondition
        $user = User::factory()->create([
            'id' => $givenUserId,
        ]);

        Generation::factory()->create([
            'id' => $givenGenerationId,
            'user_id' => $givenUserId,
            'status' => $givenGenerationStatus,
        ]);

        $preconditionThrowable = new ErrorException([
            'message' => $givenExceptionMessage,
        ]);

        // Action
        $job = new ProcessGenerationJob(
            $givenUserId,
            $givenGenerationId
        );

        // Expected
        $expectedGenerationId = $givenGenerationId;
        $expectedStatus = 'failed';
        $expectedFailedReason = $givenExceptionMessage;

        // Action
        $job->failed(
            $preconditionThrowable
        );

        // Assert
        $this->assertDatabaseHas('generations', [
            'id' => $expectedGenerationId,
            'status' => $expectedStatus,
            'failed_reason' => $expectedFailedReason,
        ]);
    }
}
