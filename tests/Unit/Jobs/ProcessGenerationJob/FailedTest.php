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
        $givenGenerationStatus = 'processing';

        // Precondition
        $preconditionUser = User::factory()->create();

        $preconditionGeneration = Generation::factory()->create([
            'user_id' => $preconditionUser->id,
            'status' => $givenGenerationStatus,
        ]);

        $preconditionThrowable = new \Exception('test');

        // Action
        $job = new ProcessGenerationJob(
            $preconditionUser,
            $preconditionGeneration
        );

        $job->failed(
            $preconditionThrowable
        );

        // Assert
        $this->assertDatabaseHas('generations', [
            'id' => $preconditionGeneration->id,
            'user_id' => $preconditionUser->id,
            'status' => 'failed',
            'failed_reason' => null,
        ]);
    }

    public function testWhenJobIsFailedWithOpenAIExceptionThenUpdateGenerationWithMessage(): void
    {
        // Given
        $givenGenerationStatus = 'processing';
        $givenExceptionMessage = 'This has failed';

        // Precondition
        $preconditionUser = User::factory()->create();

        $preconditionGeneration = Generation::factory()->create([
            'user_id' => $preconditionUser->id,
            'status' => $givenGenerationStatus,
        ]);

        $preconditionThrowable = new ErrorException([
            'message' => $givenExceptionMessage,
        ]);

        // Action
        $job = new ProcessGenerationJob(
            $preconditionUser,
            $preconditionGeneration
        );

        // Expected
        $expectedGenerationId = $preconditionGeneration->id;
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
