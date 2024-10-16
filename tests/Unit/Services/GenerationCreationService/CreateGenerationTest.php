<?php

namespace Tests\Unit\Services\GenerationCreationService;

use App\Exceptions\ArtStyleNotFoundException;
use App\Exceptions\UserNotFoundException;
use App\Jobs\ProcessGenerationJob;

class CreateGenerationTest extends BaseGenerationCreationService
{
    /**
     * @throws ArtStyleNotFoundException
     * @throws UserNotFoundException
     */
    public function testWhenDataThenSuccess(): void
    {
        // Given
        $givenUserId = 1324342;
        $givenArtTypeId = 'art_type_id';
        $givenArtStyleId = 'art_style_id';
        $givenMetadata = [
            'key' => 'value',
        ];
        $givenGenerationId = 'generation_id';

        // Mock
        $this->generationRepository->shouldReceive('create')
            ->once()
            ->with($givenUserId, $givenArtTypeId, $givenArtStyleId, $givenMetadata)
            ->andReturn($givenGenerationId);

        $this->mock('alias:'.ProcessGenerationJob::class)
            ->shouldReceive('dispatch')
            ->once()
            ->with($givenUserId, $givenGenerationId);

        // Expected
        $expectedGenerationId = $givenGenerationId;

        // Action
        $this->service->createGeneration($givenUserId, $givenArtTypeId, $givenArtStyleId, $givenMetadata);

        // Assert
        $this->assertEquals($expectedGenerationId, $givenGenerationId);
    }
}
