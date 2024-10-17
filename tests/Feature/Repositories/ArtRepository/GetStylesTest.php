<?php

namespace Tests\Feature\Repositories\ArtRepository;

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

            $this->assertArrayHasKey('id', $actualStyle);
            $this->assertArrayHasKey('name', $actualStyle);
            $this->assertArrayHasKey('description', $actualStyle);
            $this->assertArrayNotHasKey('prompt', $actualStyle);

            $this->assertEquals($expectedStyle['id'], $actualStyle['id']);
            $this->assertEquals($expectedStyle['name'], $actualStyle['name']);
            $this->assertEquals($expectedStyle['description'], $actualStyle['description']);

            $this->assertIsString($actualStyle['id']);
            $this->assertIsString($actualStyle['name']);
            $this->assertIsString($actualStyle['description']);
        }
    }
}
