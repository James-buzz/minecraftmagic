<?php

namespace Tests\Unit\Actions\GenerateImage;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Images\CreateResponse;

class HandleTest extends BaseGenerateImage
{
    public function testWhenFileAndUrlThenSuccess(): void
    {
        // Given
        $givenPrompt = 'An orange circle';
        $imageSize = '200x200';
        $imageQuality = 'hd';

        // Mock
        $fakeUrl = 'https://openai.com/fake-image.png';
        OpenAI::fake([
            CreateResponse::fake(),
        ]);

        // Expected
        $expectedUrl = $fakeUrl;

        // Action
        $response = $this->action->handle($givenPrompt, $imageSize, $imageQuality);

        // Assert
        $this->assertEquals($expectedUrl, $response);
    }
}
