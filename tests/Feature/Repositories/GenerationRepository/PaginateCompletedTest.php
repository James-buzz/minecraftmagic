<?php

namespace Tests\Feature\Repositories\GenerationRepository;

use App\Models\Generation;
use App\Models\User;

class PaginateCompletedTest extends BaseGenerationRepository
{
    public function testPaginateCompleted(): void
    {
        // Given
        $givenUserId = 1;
        $givenPage = 1;
        $givenPerPage = 10;
        $givenCountGenerations = 5;

        // Precondition
        User::factory()->create([
            'id' => $givenUserId,
        ]);
        Generation::factory()
            ->count($givenCountGenerations)
            ->create([
                'user_id' => $givenUserId,
                'status' => 'completed',
            ]);

        // Expected
        $expectedCount = $givenCountGenerations;

        // Action
        $result = $this->generationRepository->paginateCompleted($givenUserId, $givenPage, $givenPerPage);

        // Assert
        dd($result);
    }
}
