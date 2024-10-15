<?php

namespace Tests\Feature\Repositories\GenerationRepository;

use App\Models\Generation;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;

class UpdateTest extends BaseGenerationRepository
{
    public function testCanCreate(): void
    {
        // Given
        $givenUserId = 1;
        $givenData = [
            'file_path' => 'a_file_path',
        ];
        $givenGenerationId = 2;

        // Precondition
        User::factory()->create([
            'id' => $givenUserId,
        ]);
        Generation::factory()->create([
            'id' => $givenGenerationId,
            'user_id' => $givenUserId,
            'file_path' => null,
        ]);

        // Expected
        $expectedUserId = $givenUserId;
        $expectedFilePath = $givenData['file_path'];

        // Pre-assert
        $this->assertDatabaseHas('generations', [
            'id' => $givenGenerationId,
            'user_id' => $givenUserId,
            'file_path' => null,
        ]);

        // Action
        $this->generationRepository->update(
            (string) $givenGenerationId,
            $givenData
        );

        // Assert
        $this->assertDatabaseHas('generations', [
            'id' => $givenGenerationId,
            'user_id' => $expectedUserId,
            'file_path' => $expectedFilePath,
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
