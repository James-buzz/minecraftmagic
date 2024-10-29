<?php

namespace Tests\Unit\Repositories\ArtRepository;

class GetTypesTest extends BaseArtRepository
{
    public function testWhenTypesThenCorrectFormat(): void
    {
        // Precondition
        $jsonArtContent = $this->preconditionJsonArtFile();
        $jsonExpectedTypes = $this->preconditionExtractTypes($jsonArtContent);

        // Expected
        $expectedTypes = $jsonExpectedTypes;

        // Action
        $actualTypes = $this->repository->getTypes();

        // Assert
        $this->assertEquals($expectedTypes, $actualTypes);
    }
}
