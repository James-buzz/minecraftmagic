<?php

namespace Tests\Unit\Services\ArtService;

class GetAllArtTypesWithStylesTest extends BaseArtService
{
    public function testWhenSingleTypeThenSuccess(): void
    {
        // Given
        $givenArtTypeId = 'art_type_id_1';
        $givenArtTypeName = 'Art Type 1';
        $givenArtTypeDescription = 'Art Type 1 Description';
        $givenArtStyle1Id = 'art_style_id_1';
        $givenArtStyle1Name = 'Art Style 1';
        $givenArtStyle1Description = 'Art Style 1 Description';

        // Mock
        $this->repository->shouldReceive('getTypes')
            ->once()
            ->withNoArgs()
            ->andReturn([
                [
                    'id' => $givenArtTypeId,
                    'name' => $givenArtTypeName,
                    'description' => $givenArtTypeDescription,
                ],
            ]);

        $this->repository->shouldReceive('getStyles')
            ->once()
            ->with($givenArtTypeId)
            ->andReturn([
                [
                     'id' => $givenArtStyle1Id,
                    'name' => $givenArtStyle1Name,
                    'description' => $givenArtStyle1Description,
                ],
            ]);

        // Expected
        $expectedArtTypeId = $givenArtTypeId;
        $expectedArtTypeName = $givenArtTypeName;
        $expectedArtTypeDescription = $givenArtTypeDescription;
        $expectedArtStyle1Id = $givenArtStyle1Id;
        $expectedArtStyle1Name = $givenArtStyle1Name;
        $expectedArtStyle1Description = $givenArtStyle1Description;

        // Action
        $actualTypesWithStyles = $this->service->getAllArtTypesWithStyles();

        // Assert
        $this->assertEquals($expectedArtTypeId, $actualTypesWithStyles[0]['id']);
        $this->assertEquals($expectedArtTypeName, $actualTypesWithStyles[0]['name']);
        $this->assertEquals($expectedArtTypeDescription, $actualTypesWithStyles[0]['description']);
        $this->assertEquals($expectedArtStyle1Id, $actualTypesWithStyles[0]['styles'][0]['id']);
        $this->assertEquals($expectedArtStyle1Name, $actualTypesWithStyles[0]['styles'][0]['name']);
        $this->assertEquals($expectedArtStyle1Description, $actualTypesWithStyles[0]['styles'][0]['description']);
    }

    public function testWhenSingleTypeNoStylesThenEmptyStyles(): void
    {
        // Given
        $givenArtTypeId = 'art_type_id_1';
        $givenArtTypeName = 'Art Type 1';
        $givenArtTypeDescription = 'Art Type 1 Description';

        // Mock
        $this->repository->shouldReceive('getTypes')
            ->once()
            ->withNoArgs()
            ->andReturn([
                [
                    'id' => $givenArtTypeId,
                    'name' => $givenArtTypeName,
                    'description' => $givenArtTypeDescription,
                ],
            ]);

        $this->repository->shouldReceive('getStyles')
            ->once()
            ->with($givenArtTypeId)
            ->andReturn([]);

        // Expected
        $expectedArtTypeId = $givenArtTypeId;
        $expectedArtTypeName = $givenArtTypeName;
        $expectedArtTypeDescription = $givenArtTypeDescription;
        $expectedArtTypeStyles = [];

        // Action
        $actualTypesWithStyles = $this->service->getAllArtTypesWithStyles();

        // Assert
        $this->assertEquals($expectedArtTypeId, $actualTypesWithStyles[0]['id']);
        $this->assertEquals($expectedArtTypeName, $actualTypesWithStyles[0]['name']);
        $this->assertEquals($expectedArtTypeDescription, $actualTypesWithStyles[0]['description']);
        $this->assertEquals($expectedArtTypeStyles, $actualTypesWithStyles[0]['styles']);
    }
}
