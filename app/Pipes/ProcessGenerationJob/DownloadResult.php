<?php

declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use App\Services\GenerationCreationService;
use Closure;
use Illuminate\Support\Facades\Storage;

readonly class DownloadResult
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

        $imageContent = file_get_contents($contextUrl);
        Storage::put($filePath, $imageContent);

        $data['result'] = [];
        $data['result']['file_path'] = $filePath;

        return $next($data);
    }
}
