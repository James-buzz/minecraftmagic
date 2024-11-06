<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use App\Contracts\GenerationServiceInterface;
use App\Models\Generation;
use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

readonly class DownloadLocal
{
    public function __construct(protected GenerationServiceInterface $creationService) {}

    public function handle(mixed $data, Closure $next): mixed
    {
        $stepStartTime = microtime(true);

        $contextGeneration = $data['generation'];
        $contextUrl = $data['url'];

        Log::info('Queue downloading generation image', ['generation_id' => $contextGeneration['id'], 'url' => $contextUrl]);

        $generation = Generation::find($contextGeneration['id']);
        $filePath = $this->creationService->getGenerationFilePath($generation);

        $data['result'] = [];
        $data['result']['file_path'] = $filePath;

        $imageContent = Http::get($contextUrl)->body();

        Storage::disk('local')->put($filePath, $imageContent);

        $stepEndTime = microtime(true);
        $data['steps']['download'] = $stepEndTime - $stepStartTime;

        return $next($data);
    }
}
