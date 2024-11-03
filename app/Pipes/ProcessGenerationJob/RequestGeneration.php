<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use App\Contracts\ArtServiceInterface;
use Closure;
use Illuminate\Support\Facades\Log;
use OpenAI\Exceptions\ErrorException;
use OpenAI\Laravel\Facades\OpenAI;

readonly class RequestGeneration
{
    public function __construct(protected ArtServiceInterface $artService) {}

    public function handle(mixed $data, Closure $next): mixed
    {
        $stepStartTime = microtime(true);

        $generation = $data['generation'];
        $metadata = $generation['metadata'];

        $prompt = $this->buildPrompt($generation, $metadata);

        Log::info('Queue requesting OpenAI', ['generation_id' => $generation['id']]);

        $imageUrl = $this->generateImage($prompt, $metadata);

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
        try {
            $response = OpenAI::images()->create([
                'model' => 'dall-e-3',
                'prompt' => $prompt,
                'size' => $metadata['image_size'],
                'quality' => $metadata['image_quality'],
                'n' => 1,
                'response_format' => 'url',
            ]);
        } catch (ErrorException $e) {
            Log::error('Failed to generate image', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'type' => $e->getErrorType(),
            ]);
            throw $e;
        }

        $meta = $response->meta();

        Log::debug(json_encode($meta));

        return $response->data[0]->url;
    }

    /**
     * @param  array{art_style: string, art_type: string}  $generation
     * @param  array<mixed>  $metadata
     */
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
