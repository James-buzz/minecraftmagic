<?php

namespace Tests\Unit\Services\GenerationCreationService;

use App\Models\User;

class CreateGenerationTest extends BaseGenerationCreationService
{
    public function testWhenDataThenSuccess(): void
    {
        // Given
        $givenUserId = 133;
        $givenArtTypeId = 'server_logo';
        $givenArtStyleId = 'dragons-lair';

        // Precondition
        User::factory()->create(['id' => $givenUserId]);

        // Action
        $this->service->createGeneration(
            $givenUserId,
            $givenArtTypeId,
            $givenArtStyleId,
            []
        );

        // Assert
        $this->assertDatabaseHas('generations', [
            'user_id' => $givenUserId,
            'art_type' => $givenArtTypeId,
            'art_style' => $givenArtStyleId,
        ]);
    }
}
