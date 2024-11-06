<?php

namespace Tests\Unit\Repositories\ArtRepository;

class GetStylesTest extends BaseArtRepository
{
    public function testWhenServerLogoTypeThenReturnCorrectFormat(): void
    {
        // Given
        $givenType = 'server_logo';

        // Precondition
        $jsonArtContent = $this->preconditionJsonArtFile();
        $jsonExpectedStyles = $this->preconditionExtractStyles($jsonArtContent, $givenType);

        // Expected
        $expectedStyles = $jsonExpectedStyles;

        // Action
        $actualStyles = $this->repository->getStyles($givenType);

        // Assert
        $this->assertCount(count($expectedStyles), $actualStyles);

        foreach ($expectedStyles as $index => $expectedStyle) {
            $this->assertArrayHasKey($index, $actualStyles);
            $actualStyle = $actualStyles[$index];

            $this->assertSame($expectedStyle['id'], $actualStyle->id);
            $this->assertSame($expectedStyle['name'], $actualStyle->name);
            $this->assertSame($expectedStyle['description'], $actualStyle->description);
        }
    }
}
