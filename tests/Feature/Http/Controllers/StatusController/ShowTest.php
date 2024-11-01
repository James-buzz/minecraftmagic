<?php

namespace Tests\Feature\Http\Controllers\StatusController;

use App\Models\Generation;
use App\Models\User;

class ShowTest extends BaseStatusController
{
    public function testWhenStatusIsFoundThenReturn(): void
    {
        // Given
        $givenUserId = 101;

        $givenArtType = 'server_logo';
        $givenArtStyle = 'dragons-lair';

        $givenGenerationId = 99;
        $givenGenerationStatus = 'completed';
        $givenGenerationFilePath = 'some_random_file_path';
        $givenGenerationThumbnailPath = 'some_random_thumbnail_path';

        // Precondition
        $preconditionUser = User::factory()->create([
            'id' => $givenUserId,
        ]);

        Generation::factory()->create([
            'id' => $givenGenerationId,
            'user_id' => $givenUserId,
            'status' => $givenGenerationStatus,
            'art_type' => $givenArtType,
            'art_style' => $givenArtStyle,
            'file_path' => $givenGenerationFilePath,
            'thumbnail_file_path' => $givenGenerationThumbnailPath,
        ]);

        // Expected
        $expectedStatus = [
            'id' => $givenGenerationId,
            'status' => $givenGenerationStatus,
            'art_type' => $givenArtType,
            'art_style' => $givenArtStyle,
            'file_path' => $givenGenerationFilePath,
            'thumbnail_file_path' => $givenGenerationThumbnailPath,
        ];

        // Action
        $this->actingAs($preconditionUser)
            ->get(route($this->route, ['id' => $givenGenerationId]))
            // Assert
            ->assertInertia(
                fn ($assert) => $assert
                    ->component('Status', [
                        'status' => $expectedStatus,
                    ])
            );
    }
}
