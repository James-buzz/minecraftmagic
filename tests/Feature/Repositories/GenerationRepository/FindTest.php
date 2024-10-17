<?php

namespace Tests\Feature\Repositories\GenerationRepository;

use App\Models\Generation;
use App\Models\User;

class FindTest extends BaseGenerationRepository
{
    public function testCanFindGeneration(): void
    {
        // Given
        $givenUserId = 1;
        $givenGenerationId = 36;
        $givenGenerationStatus = 'completed';
        $givenGenerationArtType = 'a_art_type_id';
        $givenGenerationArtStyle = 'b_art_style_id';
        $givenGenerationFilePath = 'some_random_file_path';
        $givenGenerationThumbnailPath = 'some_random_thumbnail_path';

        // Precondition
        User::factory()->create([
            'id' => $givenUserId,
        ]);
        Generation::factory()->create([
            'id' => $givenGenerationId,
            'user_id' => $givenUserId,
            'status' => $givenGenerationStatus,
            'art_type' => $givenGenerationArtType,
            'art_style' => $givenGenerationArtStyle,
            'file_path' => $givenGenerationFilePath,
            'thumbnail_file_path' => $givenGenerationThumbnailPath,
        ]);

        // Expected
        $expectedGenerationId = $givenGenerationId;
        $expectedGenerationStatus = $givenGenerationStatus;
        $expectedGenerationArtType = $givenGenerationArtType;
        $expectedGenerationArtStyle = $givenGenerationArtStyle;
        $expectedGenerationFilePath = $givenGenerationFilePath;
        $expectedGenerationThumbnailPath = $givenGenerationThumbnailPath;

        // Action
        $result = $this->generationRepository->find((string) $givenUserId, (string) $givenGenerationId);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals($expectedGenerationId, $result['id']);
        $this->assertEquals($expectedGenerationStatus, $result['status']);
        $this->assertEquals($expectedGenerationArtType, $result['art_type']);
        $this->assertEquals($expectedGenerationArtStyle, $result['art_style']);
        $this->assertEquals($expectedGenerationFilePath, $result['file_path']);
        $this->assertEquals($expectedGenerationThumbnailPath, $result['thumbnail_file_path']);
    }

    public function testCannotFindGeneration(): void
    {
        // Given
        $givenGenerationId = 36;
        $givenUserId = 1;

        // Action
        $result = $this->generationRepository->find((string) $givenUserId, (string) $givenGenerationId);

        // Assert
        $this->assertNull($result);
    }
}
