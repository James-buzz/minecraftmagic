<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use App\Contracts\ArtServiceInterface;
use Closure;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

readonly class RequestGeneration
{
    public function __construct(protected ArtServiceInterface $artService) {}

    public function handle(mixed $data, Closure $next): mixed
    {
        $stepStartTime = microtime(true);

        $contextGeneration = $data['generation'];

        $prompt = $this->buildPrompt($contextGeneration);

        Log::info('Queue requesting OpenAI', ['generation_id' => $contextGeneration['id']]);

        $imageUrl = $this->generateImage($prompt, $contextGeneration['metadata']);

        $data['url'] = $imageUrl;

        $stepEndTime = microtime(true);
        $data['steps']['request'] = $stepEndTime - $stepStartTime;

        return $next($data);
    }

    /**
     * @param  array<mixed>  $metadata
     */
    private function generateImage(string $prompt, array $metadata): string
    {
        $response = OpenAI::images()->create([
            'model' => 'dall-e-3',
            'prompt' => $prompt,
            'size' => $metadata['image_size'],
            'quality' => $metadata['image_quality'],
            'n' => 1,
            'response_format' => 'url',
        ]);

        $meta = $response->meta();

        Log::debug(json_encode($meta));

        return $response->data[0]->url;
    }

    /**
     * @param  array<mixed>  $generation
     */
    private function buildPrompt(array $generation): string
    {
        $artType = $generation['art_type'];
        $artStyle = $generation['art_style'];
        $metadata = $generation['metadata'];

        $artStyle = $this->artService->getArtStyle($artType, $artStyle);

        $fields = $metadata['fields'] ?? [];
        $prompt = $artStyle->prompt;
        foreach ($fields as $key => $value) {
            if ($value === null) {
                continue;
            }

            $prompt = str_replace("<$key>", $value, $prompt);
        }

        return $prompt;
    }
}
