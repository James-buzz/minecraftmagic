<?php

namespace Tests\Unit\Services\GenerationRetrievalService;

use DateTimeInterface;
use Illuminate\Support\Facades\Storage;
use Mockery as m;

class GetPaginatedGenerationsTest extends BaseGenerationRetrievalService
{
    public function testWhenFirstPageThenReturn(): void
    {
        // Given
        $givenUserId = 1;
        $givenPage = 1;
        $givenPerPage = 9;

        $givenGeneration1Id = 101;
        $givenGeneration1ArtTypeId = 'a_art_type_id';
        $givenGeneration1ArtStyleId = 'b_art_style_id';
        $givenGeneration1ThumbnailPath = 'path/to/thumbnail1';

        $givenGeneration2Id = 202;
        $givenGeneration2ArtTypeId = 'c_art_type_id';
        $givenGeneration2ArtStyleId = 'd_art_style_id';
        $givenGeneration2ThumbnailPath = 'path/to/thumbnail2';

        $givenArtType1Name = 'a_art_type_name';
        $givenArtStyle1Name = 'b_art_style_name';
        $givenArtType2Name = 'c_art_type_name';
        $givenArtStyle2Name = 'd_art_style_name';

        $givenGeneration1ThumbnailUrl = 'url://path/to/thumbnail1';
        $givenGeneration2ThumbnailUrl = 'url://path/to/thumbnail2';

        $givenCountCompleted = 20;
        $givenLastPage = ceil($givenCountCompleted / $givenPerPage);

        // Mock
        $this->mockGenerationRepository->shouldReceive('paginateCompleted')
            ->with($givenUserId, $givenPage, $givenPerPage)
            ->andReturn([
                [
                    'id' => $givenGeneration1Id,
                    'art_type' => $givenGeneration1ArtTypeId,
                    'art_style' => $givenGeneration1ArtStyleId,
                    'thumbnail_file_path' => $givenGeneration1ThumbnailPath,
                ],
                [
                    'id' => $givenGeneration2Id,
                    'art_type' => $givenGeneration2ArtTypeId,
                    'art_style' => $givenGeneration2ArtStyleId,
                    'thumbnail_file_path' => $givenGeneration2ThumbnailPath,
                ],
            ]);

        $this->mockGenerationRepository->shouldReceive('countCompleted')
            ->with($givenUserId)
            ->andReturn($givenCountCompleted);

        Storage::shouldReceive('disk')
            ->with('s3')
            ->andReturnSelf();

        Storage::shouldReceive('temporaryUrl')
            ->with($givenGeneration1ThumbnailPath, m::type(DateTimeInterface::class))
            ->andReturn($givenGeneration1ThumbnailUrl);

        Storage::shouldReceive('temporaryUrl')
            ->with($givenGeneration2ThumbnailPath, m::type(DateTimeInterface::class))
            ->andReturn($givenGeneration2ThumbnailUrl);

        $this->mockArtRepository->shouldReceive('getStyle')
            ->with($givenGeneration1ArtTypeId, $givenGeneration1ArtStyleId)
            ->andReturn([
                'name' => $givenArtStyle1Name,
            ]);

        $this->mockArtRepository->shouldReceive('getStyle')
            ->with($givenGeneration2ArtTypeId, $givenGeneration2ArtStyleId)
            ->andReturn([
                'name' => $givenArtStyle2Name,
            ]);

        $this->mockArtRepository->shouldReceive('getType')
            ->with($givenGeneration1ArtTypeId)
            ->andReturn([
                'name' => $givenArtType1Name,
            ]);

        $this->mockArtRepository->shouldReceive('getType')
            ->with($givenGeneration2ArtTypeId)
            ->andReturn([
                'name' => $givenArtType2Name,
            ]);

        // Expected
        $expectedDataGeneration1 = [
            'id' => $givenGeneration1Id,
            'art_type' => $givenArtType1Name,
            'art_style' => $givenArtStyle1Name,
            'thumbnail_url' => $givenGeneration1ThumbnailUrl,
        ];
        $expectedDataGeneration2 = [
            'id' => $givenGeneration2Id,
            'art_type' => $givenArtType2Name,
            'art_style' => $givenArtStyle2Name,
            'thumbnail_url' => $givenGeneration2ThumbnailUrl,
        ];
        $expectedMetaCurrentPage = $givenPage;
        $expectedMetaPerPage = $givenPerPage;
        $expectedTotal = $givenCountCompleted;
        $expectedLastPage = $givenLastPage;

        // Action
        $actualData = $this->service->getPaginatedGenerations($givenUserId, $givenPage, $givenPerPage);

        // Assert
        $this->assertIsArray($actualData);
        $this->assertEquals($actualData['data'][0], $expectedDataGeneration1);
        $this->assertEquals($actualData['data'][1], $expectedDataGeneration2);
        $this->assertEquals($actualData['meta']['current_page'], $expectedMetaCurrentPage);
        $this->assertEquals($actualData['meta']['per_page'], $expectedMetaPerPage);
        $this->assertEquals($actualData['meta']['total'], $expectedTotal);
        $this->assertEquals($actualData['meta']['last_page'], $expectedLastPage);

    }
}
