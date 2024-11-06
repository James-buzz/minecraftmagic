<?php

namespace Tests\Unit\Repositories\ArtRepository;

class GetStyleTest extends BaseArtRepository
{
    public function testWhenServerLogoTypeThenReturnCorrectFormat(): void
    {
        // Given
        $givenTypeId = 'server_logo';
        $givenStyleId = 'end-explorer';

        // Precondition
        $jsonArtContent = $this->preconditionJsonArtFile();
        $jsonArtStyle = $this->preconditionExtractStyle($jsonArtContent, $givenTypeId, $givenStyleId);

        // Expected
        $expectedArtStyleId = $jsonArtStyle['id'];
        $expectedArtStyleName = $jsonArtStyle['name'];
        $expectedArtStyleDescription = $jsonArtStyle['description'];
        $expectedArtStylePrompt = $jsonArtStyle['prompt'];

        // Action
        $actualStyle = $this->repository->getStyle($givenTypeId, $givenStyleId);

        // Assert
        $this->assertEquals($expectedArtStyleId, $actualStyle->id);
        $this->assertEquals($expectedArtStyleName, $actualStyle->name);
        $this->assertEquals($expectedArtStyleDescription, $actualStyle->description);
        $this->assertEquals($expectedArtStylePrompt, $actualStyle->prompt);
    }
}
