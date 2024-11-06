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
        $this->assertCount(count($expectedTypes), $actualTypes);
        foreach ($expectedTypes as $index => $expectedType) {
            $this->assertArrayHasKey($index, $actualTypes);
            $actualType = $actualTypes[$index];

            $this->assertSame($expectedType['id'], $actualType->id);
            $this->assertSame($expectedType['name'], $actualType->name);
        }
    }
}
