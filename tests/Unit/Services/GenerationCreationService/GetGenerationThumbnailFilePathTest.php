<?php

namespace Tests\Unit\Services\GenerationCreationService;

class GetGenerationThumbnailFilePathTest extends BaseGenerationCreationService
{
    public function testWhenDataThenSuccess(): void
    {
        // Given
        $givenUserId = 23;
        $givenGenerationId = 'generation-id';

        // Expected
        $expectedFormat = '/generations/%s/%s/thumbnail.png';
        $expectedThumbnailFilePath = sprintf($expectedFormat, $givenUserId, $givenGenerationId);

        // Action
        $actualThumbnailFilePath = $this->service->getGenerationThumbnailFilePath((string) $givenUserId, $givenGenerationId);

        // Assert
        $this->assertEquals($expectedThumbnailFilePath, $actualThumbnailFilePath);
    }
}
