<?php

namespace Tests\Unit\Services\GenerationRetrievalService;

use App\Models\Generation;
use App\Models\User;
use Illuminate\Support\Str;

class GetGenerationTest extends BaseGenerationRetrievalService
{
    public function testWhenGenerationThenSuccess(): void
    {
        // Given
        $givenGenerationId = Str::ulid();
        $givenUserId = 101;
        $givenGenerationStatus = 'completed';
        $givenGenerationArtType = 'server_logo';
        $givenGenerationArtStyle = 'dragons-lair';
        $givenGenerationFilePath = 'file/to/path';
        $givenGenerationThumbnailFilePath = 'thumbnail/to/path';

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
            'thumbnail_file_path' => $givenGenerationThumbnailFilePath,
        ]);

        // Expected
        $expectedGeneration = [
            'id' => $givenGenerationId,
            'metadata' => [],
            'status' => $givenGenerationStatus,
            'art_type' => $givenGenerationArtType,
            'art_style' => $givenGenerationArtStyle,
            'file_path' => $givenGenerationFilePath,
        ];

        // Action
        $actualGeneration = $this->service->getGeneration($givenUserId, $givenGenerationId);

        // Assert
        $this->assertEquals($expectedGeneration, $actualGeneration);
    }
}
