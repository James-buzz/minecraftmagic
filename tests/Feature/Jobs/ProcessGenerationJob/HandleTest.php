<?php

namespace Tests\Feature\Jobs\ProcessGenerationJob;

use App\Jobs\ProcessGenerationJob;
use App\Models\Generation;
use App\Models\User;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Images\CreateResponse;

class HandleTest extends BaseProcessGenerationJob
{
    public function testWhenFileThenSuccess(): void
    {
        // Given
        $givenStatus = 'pending';

        // Precondition
        $preconditionUser = User::factory()->create();
        $preconditionGeneration = Generation::factory()->create([
            'user_id' => $preconditionUser->id,
            'status' => $givenStatus,
        ]);

        $this->actingAs($preconditionUser);

        // Mock
        OpenAI::fake([CreateResponse::fake([
            'data' => [
                [
                    'url' => 'https://example.com/image.jpg',
                ],
            ],
        ])]);

        // Action
        $job = new ProcessGenerationJob($preconditionGeneration);

        $job->handle();

        // Assert
    }
}
