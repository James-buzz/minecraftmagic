<?php

namespace Tests\Unit\Services\GenerationRetrievalService;

use App\Models\Generation;
use App\Models\User;

class GetPaginatedGenerationsTest extends BaseGenerationRetrievalService
{
    public function testWhenFirstPageThenReturn(): void
    {
        // Given
        $givenUserId = 101;
        $givenArtType = 'server_logo';
        $givenArtStyle = 'dragons-lair';
        $givenPage = 1;
        $givenPerPage = 9;
        $givenFilePath = 'file/to/path';
        $givenThumbnailFilePath = 'thumbnail/file/to/path';
        $givenCount = 50;

        // Precondition
        User::factory()->create([
            'id' => $givenUserId,
        ]);

        Generation::factory()
            ->count($givenCount)
            ->create([
                'user_id' => $givenUserId,
                'status' => 'completed',
                'file_path' => $givenFilePath,
                'thumbnail_file_path' => $givenThumbnailFilePath,
                'art_type' => $givenArtType,
                'art_style' => $givenArtStyle,
            ]);

        // Mock
        $this->mockArtRepository->shouldReceive('getType')
            ->with($givenArtType)
            ->andReturn([
                'name' => 'Server Logo',
            ]);

        $this->mockArtRepository->shouldReceive('getStyle')
            ->with($givenArtType, $givenArtStyle)
            ->andReturn([
                'name' => 'Dragons Lair',
                'description' => 'A fiery dragon lair',
            ]);

        // Expected
        $expectedCurrentPage = $givenPage;
        $expectedPerPage = $givenPerPage;
        $expectedTotal = $givenCount;
        $expectedLastPage = ceil($givenCount / $givenPerPage);

        // Action
        $actualPagination = $this->service->getPaginatedGenerations(
            $givenUserId,
            $givenPage,
            $givenPerPage
        );

        // Assert
        $this->assertEquals($expectedCurrentPage, $actualPagination['meta']['current_page']);
        $this->assertEquals($expectedPerPage, $actualPagination['meta']['per_page']);
        $this->assertEquals($expectedTotal, $actualPagination['meta']['total']);
        $this->assertEquals($expectedLastPage, $actualPagination['meta']['last_page']);
    }
}
