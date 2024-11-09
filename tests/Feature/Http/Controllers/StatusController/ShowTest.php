<?php

namespace Tests\Feature\Http\Controllers\StatusController;

use App\Models\Generation;
use App\Models\User;

class ShowTest extends BaseStatusController
{
    public function testWhenStatusIsFoundThenReturn(): void
    {
        // Given
        $givenGenerationStatus = 'completed';
        $givenGenerationFilePath = 'some_random_file_path';
        $givenGenerationThumbnailPath = 'some_random_thumbnail_path';

        // Precondition
        $preconditionUser = User::factory()->create();

        $preconditionGeneration = Generation::factory()->create([
            'user_id' => $preconditionUser->id,
            'status' => $givenGenerationStatus,
            'file_path' => $givenGenerationFilePath,
            'thumbnail_file_path' => $givenGenerationThumbnailPath,
        ]);

        // Expected
        $expectedStatus = [
            'id' => $preconditionGeneration->id,
            'status' => $givenGenerationStatus,
            'file_path' => $givenGenerationFilePath,
            'thumbnail_file_path' => $givenGenerationThumbnailPath,
        ];

        // Action
        $this->actingAs($preconditionUser)
            ->get(route($this->route, ['generation' => $preconditionGeneration->id]))
            // Assert
            ->assertInertia(
                fn ($assert) => $assert
                    ->component('Status', [
                        'status' => $expectedStatus,
                    ])
            );
    }
}
