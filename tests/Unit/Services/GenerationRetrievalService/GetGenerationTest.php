<?php

namespace Tests\Unit\Services\GenerationRetrievalService;

class GetGenerationTest extends BaseGenerationRetrievalService
{
    public function testWhenGenerationThenSuccess(): void
    {
        // Given
        $givenGenerationId = '1';
        $givenGenerationUserId = '1';
        $givenGenerationStatus = 'completed';
        $givenGenerationArtType = 'art_type_id_1';
        $givenGenerationArtStyle = 'art_style_id_1';
        $givenGenerationFilePath = 'file_path_1';
        $givenGenerationThumbnailFilePath = 'thumbnail_file_path_1';

        // Mock
        $this->generationRepository->shouldReceive('find')
            ->with($givenGenerationUserId, $givenGenerationId)
            ->andReturn([
                'user_id' => $givenGenerationUserId,
                'status' => $givenGenerationStatus,
                'art_type' => $givenGenerationArtType,
                'art_style' => $givenGenerationArtStyle,
                'file_path' => $givenGenerationFilePath,
                'thumbnail_file_path' => $givenGenerationThumbnailFilePath,
            ]);

        // Expected
        $expectedGeneration = [
            'user_id' => $givenGenerationUserId,
            'status' => $givenGenerationStatus,
            'art_type' => $givenGenerationArtType,
            'art_style' => $givenGenerationArtStyle,
            'file_path' => $givenGenerationFilePath,
            'thumbnail_file_path' => $givenGenerationThumbnailFilePath,
        ];

        // Action
        $actualGeneration = $this->service->getGeneration($givenGenerationUserId, $givenGenerationId);

        // Assert
        $this->assertEquals($expectedGeneration, $actualGeneration);
    }
}
