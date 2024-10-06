<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use App\Contracts\ArtServiceInterface;
use Closure;
use OpenAI\Laravel\Facades\OpenAI;

readonly class RequestGeneration
{
    public function __construct(protected ArtServiceInterface $artService)
    {
    }

    public function handle(mixed $data, Closure $next)
    {
        $generation = $data['generation'];
        $metadata = $generation['metadata'];

        $prompt = $this->buildPrompt($generation, $metadata);

        $imageUrl = $this->generateImage($prompt, $metadata);

        $data['url'] = $imageUrl;

        return $next($data);
    }

    private function generateImage(string $prompt, array $metadata): string
    {
        // todo: move to a manager to support other models
        $response = OpenAI::images()->create([
            'model' => 'dall-e-3',
            'prompt' => $prompt,
            'size' => $metadata['image_size'],
            'quality' => $metadata['image_quality'],
            'n' => 1,
            'response_format' => 'url',
        ]);

        return $response->data[0]->url;
    }

    private function buildPrompt(array $generation, array $metadata): string
    {
        $artStyle = $this->artService->getArtStyle(
            $generation['art_type'],
            $generation['art_style']
        );

        $fields = $metadata['fields'] ?? [];
        $prompt = $artStyle['prompt'];
        foreach ($fields as $key => $value) {
            if ($value === null) {
                continue;
            }
            $prompt = str_replace("<$key>", $value, $prompt);
        }

        return $prompt;
    }
}
