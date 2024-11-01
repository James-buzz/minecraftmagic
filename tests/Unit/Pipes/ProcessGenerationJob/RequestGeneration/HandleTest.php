<?php

namespace Tests\Unit\Pipes\ProcessGenerationJob\RequestGeneration;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Images\CreateResponse;

class HandleTest extends BaseRequestGeneration
{
    public function testWhenPassedThenReturnCorrectData(): void
    {
        // Given
        $givenContextGenerationArtTypeId = 'a_art_type_id';
        $givenContextGenerationArtStyleId = 'c_art_style_id';
        $givenContextGenerationArtStylePrompt = 'art style prompt with';
        $givenContextGenerationMetadata = [
            'fields' => [],
            'image_size' => '1024x1024',
            'image_quality' => 'hd',
        ];

        $givenData = [
            'generation' => [
                'art_type' => $givenContextGenerationArtTypeId,
                'art_style' => $givenContextGenerationArtStyleId,
                'metadata' => $givenContextGenerationMetadata,
            ],
        ];

        // Mock
        $this->mockArtService->shouldReceive('getArtStyle')
            ->with($givenContextGenerationArtTypeId, $givenContextGenerationArtStyleId)
            ->andReturn([
                'id' => $givenContextGenerationArtStyleId,
                'prompt' => $givenContextGenerationArtStylePrompt,
            ]);

        OpenAI::fake([
            CreateResponse::fake(),
        ]);

        // Expected
        $expectedOutputDataUrl = 'https://openai.com/fake-image.png';

        // Action
        $this->pipe->handle($givenData, function ($actualOutputData) use ($expectedOutputDataUrl) {
            // Assert
            $this->assertEquals($expectedOutputDataUrl, $actualOutputData['url']);
        });
    }
}
