<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use OpenAI\Laravel\Facades\OpenAI;

class GenerateImage
{
    use AsAction;

    public function handle(string $prompt, string $imageSize, string $imageQuality): string
    {
        $response = OpenAI::images()->create([
            'model' => 'dall-e-3',
            'prompt' => $prompt,
            'size' => $imageSize,
            'quality' => $imageQuality,
            'n' => 1,
            'response_format' => 'url',
        ]);

        return $response->data[0]->url;
    }
}
