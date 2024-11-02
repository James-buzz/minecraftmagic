<?php

namespace Tests\Unit\Services\GenerationRetrievalService;

use App\Models\Generation;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mockery;

class GetGenerationFileUrlTest extends BaseGenerationRetrievalService
{
    public function testWhenGenerationThenTemporaryUrl(): void
    {
        // Given
        $givenUserId = 101;
        $givenGenerationId = Str::ulid();
        $givenGenerationStatus = 'completed';
        $givenGenerationArtType = 'server_logo';
        $givenGenerationArtStyle = 'dragons-lair';
        $givenGenerationFilePath = 'path/to/file';
        $givenGenerationThumbnailFilePath = 'path/to/thumbnail';
        $givenTemporaryURL = 'temporary/url';

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

        // Mock
        Storage::shouldReceive('disk')
            ->with('s3')
            ->andReturnSelf();

        Storage::shouldReceive('temporaryUrl')
            ->with($givenGenerationFilePath, Mockery::type(DateTimeInterface::class))
            ->andReturn($givenTemporaryURL);

        // Expected
        $expectedURL = $givenTemporaryURL;

        // Action
        $actualURL = $this->service->getGenerationFileUrl($givenUserId, $givenGenerationId);

        // Assert
        $this->assertEquals($expectedURL, $actualURL);
    }
}
