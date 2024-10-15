<?php

namespace Tests\Feature\Repositories\GenerationRepository;

use App\Models\Generation;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;

class CountCompletedTest extends BaseGenerationRepository
{
    public function testCanCountCompleted(): void
    {
        // Given
        $givenUserId = 1;
        $givenStatus = 'completed';
        $givenStatus2 = 'pending';
        $givenCount = 2;
        $givenCount2 = 5;

        // Precondition
        User::factory()->create([
            'id' => $givenUserId,
        ]);

        Generation::factory()
            ->count($givenCount)
            ->create([
                'user_id' => $givenUserId,
                'status' => $givenStatus,
                'file_path' => 'a_file_path',
            ]);

        Generation::factory()
           ->count($givenCount2)
            ->create([
                'user_id' => $givenUserId,
                'status' => $givenStatus2,
                'file_path' => 'b_file_path',
            ]);

        // Expected
        $expectedCount = $givenCount;

        // Action
        $result = $this->generationRepository->countCompleted($givenUserId);

        // Assert
        $this->assertEquals($expectedCount, $result);
    }
}
