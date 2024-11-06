<?php

namespace Tests\Unit\Services\GenerationCreationService;

use App\Models\ArtStyle;
use App\Models\ArtType;
use App\Models\User;

class CreateGenerationTest extends BaseGenerationCreationService
{
    public function testWhenDataThenSuccess(): void
    {
        // Given
        $givenUserId = 133;
        $givenArtType = new ArtType(
            'dragons',
            'Dragons',
        );
        $givenArtStyle = new ArtStyle(
            'dragons_styles',
            'Dragons',
            'Dragon style',
            'Draw a dragon',
        );

        // Precondition
        $preconditionUser = User::factory()->create(['id' => $givenUserId]);

        // Expected
        $expectedUserId = $givenUserId;
        $expectedArtTypeId = $givenArtType->id;
        $expectedArtStyleId = $givenArtStyle->id;

        // Action
        $this->service->createGeneration(
            $preconditionUser,
            $givenArtType,
            $givenArtStyle,
            []
        );

        // Assert
        $this->assertDatabaseHas('generations', [
            'user_id' => $expectedUserId,
            'art_type' => $expectedArtTypeId,
            'art_style' => $expectedArtStyleId,
        ]);
    }
}
