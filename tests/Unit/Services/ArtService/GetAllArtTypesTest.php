<?php

namespace Tests\Unit\Services\ArtService;

/**
 * @group ArtService
 */
class GetAllArtTypesTest extends BaseArtService
{
    public function testWhenTypesProvidedThenReturnTypes(): void
    {
        // Given
        $givenArtType1Id = 'art_type_id_1';
        $givenArtType1Name = 'Art Type 1';
        $givenArtType2Id = 'art_type_id_2';
        $givenArtType2Name = 'Art Type 2';

        // Mock
        $this->repository->shouldReceive('getTypes')
            ->once()
            ->withNoArgs()
            ->andReturn([
                [
                    'id' => $givenArtType1Id,
                    'name' => $givenArtType1Name,
                ],
                [
                    'id' => $givenArtType2Id,
                    'name' => $givenArtType2Name,
                ],
            ]);

        // Expected
        $expectedArtType1Id = $givenArtType1Id;
        $expectedArtType1Name = $givenArtType1Name;
        $expectedArtType2Id = $givenArtType2Id;
        $expectedArtType2Name = $givenArtType2Name;

        // Action
        $actualTypes = $this->service->getAllArtTypes();

        // Assert
        $this->assertEquals($expectedArtType1Id, $actualTypes[0]['id']);
        $this->assertEquals($expectedArtType1Name, $actualTypes[0]['name']);
        $this->assertEquals($expectedArtType2Id, $actualTypes[1]['id']);
        $this->assertEquals($expectedArtType2Name, $actualTypes[1]['name']);
    }
}
