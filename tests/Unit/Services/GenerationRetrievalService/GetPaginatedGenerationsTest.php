<?php

namespace Tests\Unit\Services\GenerationRetrievalService;

use App\Models\ArtStyle;
use App\Models\ArtType;
use App\Models\Generation;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Support\Facades\Storage;
use Mockery as m;

class GetPaginatedGenerationsTest extends BaseGenerationRetrievalService
{
    public function testWhenFirstPageThenReturn(): void
    {
        // Given
        $givenArtTypeId = 'server_logo';
        $givenArtTypeName = 'Server Logo';
        $givenArtStyleId = 'dragons-lair';
        $givenArtStyleName = 'Dragons Lair';
        $givenArtStyleDescription = 'A fantasy theme with dragons and knights.';
        $givenArtStylePrompt = 'A fantasy theme with dragons and knights.';

        $givenPage = 1;
        $givenPerPage = 9;
        $givenFilePath = 'file/to/path';
        $givenThumbnailFilePath = 'thumbnail/file/to/path';
        $givenTemporaryURL = 'temporary/url';
        $givenCount = 50;

        // Precondition
        $preconditionUser = User::factory()->create();

        Generation::factory()
            ->count($givenCount)
            ->create([
                'user_id' => $preconditionUser->id,
                'status' => 'completed',
                'file_path' => $givenFilePath,
                'thumbnail_file_path' => $givenThumbnailFilePath,
                'art_type' => $givenArtTypeId,
                'art_style' => $givenArtStyleId,
            ]);

        // Mock
        $this->mockArtRepository->shouldReceive('getType')
            ->with($givenArtTypeId)
            ->andReturn(new ArtType(
                $givenArtTypeId,
                $givenArtTypeName,
            ));

        $this->mockArtRepository->shouldReceive('getStyle')
            ->with($givenArtTypeId, $givenArtStyleId)
            ->andReturn(new ArtStyle(
                $givenArtStyleId,
                $givenArtStyleName,
                $givenArtStyleDescription,
                $givenArtStylePrompt,
            ));

        Storage::shouldReceive('disk')
            ->with('s3')
            ->andReturnSelf();

        Storage::shouldReceive('temporaryUrl')
            ->with($givenThumbnailFilePath, m::type(DateTimeInterface::class))
            ->andReturn($givenTemporaryURL);

        // Expected
        $expectedCurrentPage = $givenPage;
        $expectedPerPage = $givenPerPage;
        $expectedTotal = $givenCount;
        $expectedLastPage = ceil($givenCount / $givenPerPage);

        // Action
        $actualPagination = $this->service->getPaginatedGenerations(
            $preconditionUser,
            $givenPage,
            $givenPerPage
        );

        // Assert
        $this->assertEquals($expectedCurrentPage, $actualPagination['meta']['current_page']);
        $this->assertEquals($expectedPerPage, $actualPagination['meta']['per_page']);
        $this->assertEquals($expectedTotal, $actualPagination['meta']['total']);
        $this->assertEquals($expectedLastPage, $actualPagination['meta']['last_page']);

        $this->assertCount($givenPerPage, $actualPagination['data']);
    }
}
