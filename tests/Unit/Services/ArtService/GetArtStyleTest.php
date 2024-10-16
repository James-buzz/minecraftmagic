<?php

namespace Tests\Unit\Services\ArtService;

use App\Exceptions\ArtStyleNotFoundException;

class GetArtStyleTest extends BaseArtService
{
    /**
     * @throws ArtStyleNotFoundException
     */
    public function testWhenArtStyleThenSuccess(): void
    {
        // Given
        $givenArtTypeId = 'art_type_id_1';
        $givenArtStyleId = 'art_style_id_1';
        $givenArtStyleName = 'Art Style 1';
        $givenArtStyleDescription = 'Art Style 1 Description';
        $givenArtStylePrompt = 'Art Style 1 Prompt';

        // Mock
        $this->repository->shouldReceive('getStyle')
            ->once()
            ->with($givenArtTypeId, $givenArtStyleId)
            ->andReturn([
                'id' => $givenArtStyleId,
                'name' => $givenArtStyleName,
                'description' => $givenArtStyleDescription,
                'prompt' => $givenArtStylePrompt,
            ]);

        // Expected
        $expectedArtStyleId = $givenArtStyleId;
        $expectedArtStyleName = $givenArtStyleName;
        $expectedArtStyleDescription = $givenArtStyleDescription;
        $expectedArtStylePrompt = $givenArtStylePrompt;

        // Action
        $actualArtStyle = $this->service->getArtStyle($givenArtTypeId, $givenArtStyleId);

        // Assert
        $this->assertEquals($expectedArtStyleId, $actualArtStyle['id']);
        $this->assertEquals($expectedArtStyleName, $actualArtStyle['name']);
        $this->assertEquals($expectedArtStyleDescription, $actualArtStyle['description']);
        $this->assertEquals($expectedArtStylePrompt, $actualArtStyle['prompt']);
    }

    /**
     * @throws ArtStyleNotFoundException
     */
    public function testWhenArtStyleNotFoundThenFail(): void
    {
        // Given
        $givenArtTypeId = 'art_type_id_x';
        $givenArtStyleId = 'art_style_id_x';

        // Mock
        $this->repository->shouldReceive('getStyle')
            ->once()
            ->with($givenArtTypeId, $givenArtStyleId)
            ->andReturn(null);

        // Expected
        $this->expectException(ArtStyleNotFoundException::class);

        // Action
        $actualArtStyle = $this->service->getArtStyle($givenArtTypeId, $givenArtStyleId);

        // Assert
        $this->assertNull($actualArtStyle);
    }
}
