<?php

namespace Tests\Unit\Services\GenerationCreationService;

use App\Models\Generation;

class GetGenerationThumbnailFilePathTest extends BaseGenerationCreationService
{
    public function testWhenDataThenSuccess(): void
    {
        // Given
        $givenGeneration = new Generation([
            'id' => 1,
            'user_id' => 101,
        ]);

        // Expected
        $expectedFormat = '/generations/%s/%s/thumbnail.png';
        $expectedThumbnailFilePath = sprintf($expectedFormat, $givenGeneration->user_id, $givenGeneration->id);

        // Action
        $actualThumbnailFilePath = $this->service->getGenerationThumbnailFilePath($givenGeneration);

        // Assert
        $this->assertEquals($expectedThumbnailFilePath, $actualThumbnailFilePath);
    }
}
