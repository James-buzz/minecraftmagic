<?php

namespace Tests\Unit\Services\GenerationRetrievalService;

use DateTimeInterface;
use Illuminate\Support\Facades\Storage;
use Mockery;

class GetGenerationFileUrlTest extends BaseGenerationRetrievalService
{
    public function testWhenGenerationThenTemporaryUrl(): void
    {
        // Given
        $givenGenerationId = '1';
        $givenGenerationUserId = '1';
        $givenGenerationStatus = 'completed';
        $givenGenerationArtType = 'art_type_id_1';
        $givenGenerationArtStyle = 'art_style_id_1';
        $givenGenerationFilePath = 'file_path_1';
        $givenGenerationThumbnailFilePath = 'thumbnail_file_path_1';
        $givenTemporaryURL = 'temporary_url_1';

        // Mock
        $this->generationRepository->shouldReceive('find')
            ->with($givenGenerationId)
            ->andReturn([
                'user_id' => $givenGenerationUserId,
                'status' => $givenGenerationStatus,
                'art_type' => $givenGenerationArtType,
                'art_style' => $givenGenerationArtStyle,
                'file_path' => $givenGenerationFilePath,
                'thumbnail_file_path' => $givenGenerationThumbnailFilePath,
            ]);

        $mockStorageFacade = $this->mock('alias:'.Storage::class);
        $mockStorageFacade->shouldReceive('disk')
            ->with('s3')
            ->andReturnSelf();
        $mockStorageFacade->shouldReceive('temporaryUrl')
            ->with($givenGenerationFilePath, Mockery::type(DateTimeInterface::class))
            ->andReturn($givenTemporaryURL);

        // Expected
        $expectedURL = $givenTemporaryURL;

        // Action
        $actualURL = $this->service->getGenerationFileUrl($givenGenerationId);

        // Assert
        $this->assertEquals($expectedURL, $actualURL);
    }
}
