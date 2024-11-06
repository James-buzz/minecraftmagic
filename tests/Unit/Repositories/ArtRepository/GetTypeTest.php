<?php

namespace Tests\Unit\Repositories\ArtRepository;

class GetTypeTest extends BaseArtRepository
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
        $actualStyle = $this->repository->getType($givenTypeId);

        // Assert
        $this->assertEquals($expectedTypeId, $actualStyle->id);
        $this->assertEquals($expectedTypeName, $actualStyle->name);
    }
}
