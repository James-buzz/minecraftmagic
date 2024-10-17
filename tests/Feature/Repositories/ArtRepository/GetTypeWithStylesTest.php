<?php

namespace Tests\Feature\Repositories\ArtRepository;

class GetTypeWithStylesTest extends BaseArtRepository
{
    public function testWhenServerLogoTypeThenReturnCorrectFormat(): void
    {
        // Given
        $givenTypeId = 'server_logo';

        // Precondition
        $jsonArtContent = $this->preconditionJsonArtFile();
        $jsonArtType = $this->preconditionExtractType($jsonArtContent, $givenTypeId);

        // Expected
        $expectedTypeId = $jsonArtType['id'];
        $expectedTypeName = $jsonArtType['name'];

        // Action
        $actualType = $this->repository->getTypeWithStyles($givenTypeId);

        // Assert
        $this->assertEquals($expectedTypeId, $actualType['id']);
        $this->assertEquals($expectedTypeName, $actualType['name']);
        $this->assertArrayHasKey('styles', $actualType);
    }
}
