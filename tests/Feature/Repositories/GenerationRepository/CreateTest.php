<?php

namespace Tests\Feature\Repositories\GenerationRepository;

use App\Models\Generation;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;

class CreateTest extends BaseGenerationRepository
{
    public function testCanCreate(): void
    {
        // Given
        $givenUserId = 1;
        $givenArtType = 'a_art_type_id';
        $givenArtStyle = 'b_art_style_id';
        $givenMetadata = [
            'random' => 123,
        ];

        // Precondition
        User::factory()->create([
            'id' => $givenUserId,
        ]);

        // Expected
        $expectedUserId = $givenUserId;
        $expectedArtType = $givenArtType;
        $expectedArtStyle = $givenArtStyle;

        // Pre-assert
        $this->assertDatabaseMissing('generations', [
            'user_id' => $expectedUserId,
            'art_type' => $expectedArtType,
            'art_style' => $expectedArtStyle,
        ]);

        // Action
        $result = $this->generationRepository->create(
            $givenUserId,
            $givenArtType,
            $givenArtStyle,
            $givenMetadata
        );

        // Assert
        $this->assertNotNull($result);
        $this->assertIsString($result);
        $this->assertDatabaseHas('generations', [
            'id' => $result,
            'user_id' => $expectedUserId,
            'art_type' => $expectedArtType,
            'art_style' => $expectedArtStyle,
        ]);
    }

    public function testCanGoWrong(): void
    {
        // Given
        $givenUserId = 1;
        $givenArtType = 'a_art_type_id';
        $givenArtStyle = 'b_art_style_id';
        $givenMetadata = [
            'random' => 123,
        ];

        // Expected
        $this->expectException(QueryException::class);

        // Action
        $this->generationRepository->create(
            $givenUserId,
            $givenArtType,
            $givenArtStyle,
            $givenMetadata
        );
    }
}
