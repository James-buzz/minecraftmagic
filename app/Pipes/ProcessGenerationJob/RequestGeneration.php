<?php
declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use App\Contracts\ArtServiceInterface;
use Closure;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

readonly class RequestGeneration
{
    public function __construct(protected ArtServiceInterface $artService){}

    public function handle(mixed $data, Closure $next) {
        $generation = $data['generation'];

        $artStyle = $this->artService->getArtStyle($generation['art_type'], $generation['art_style']);

        $metadata = $generation['metadata'];

        ray($metadata);
        ray($generation);

        $fields = $metadata['fields'] ?? [];
        $prompt = $artStyle['prompt'];
        foreach ($fields as $key => $value) {
            if ($value === null) {
                continue;
            }
            $prompt = str_replace("<$key>", $value, $prompt);
        }

        Log::info('Asking OpenAI to generate image with style: ' . $artStyle['name']);

        $response = OpenAI::images()->create([
            'model' => 'dall-e-3',
            'prompt' => $prompt,
            'size' => $metadata['image_size'],
            'quality' => $metadata['image_quality'],
            'n' => 1,
            'response_format' => 'url',
        ]);

        $result = $response->data[0];
        $url = $result->url;

        $data['url'] = $url;

        return $next($data);
    }
}
