<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use App\Services\GenerationCreationService;
use Closure;
use Illuminate\Support\Facades\Storage;

readonly class DownloadLocal
{
    public function __construct(protected readonly GenerationCreationService $creationService)
    {
    }

    public function handle(mixed $data, Closure $next)
    {
        $contextUserId = $data['user'];
        $contextGenerationId = $data['generation']['id'];
        $contextUrl = $data['url'];

        $filePath = $this->creationService->getGenerationFilePath($contextUserId, $contextGenerationId);

        $data['result'] = [];
        $data['result']['file_path'] = $filePath;

        $imageContent = file_get_contents($contextUrl);

        Storage::disk('local')
            ->put($filePath, $imageContent);

        return $next($data);
    }
}
