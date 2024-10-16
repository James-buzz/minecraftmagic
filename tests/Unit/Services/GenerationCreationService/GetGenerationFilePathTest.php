<?php

namespace Tests\Unit\Services\GenerationCreationService;

class GetGenerationFilePathTest extends BaseGenerationCreationService
{
    public function testWhenDataThenSuccess(): void
    {
        // Given
        $givenUserId = 23;
        $givenGenerationId = 'generation-id';

        // Expected
        $expectedFormat = '/generations/%s/%s/original.png';
        $expectedThumbnailFilePath = sprintf($expectedFormat, $givenUserId, $givenGenerationId);

        // Action
        $actualThumbnailFilePath = $this->service->getGenerationFilePath((string) $givenUserId, $givenGenerationId);

        // Assert
        $this->assertEquals($expectedThumbnailFilePath, $actualThumbnailFilePath);
    }
}
