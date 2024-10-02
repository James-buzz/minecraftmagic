<?php
declare(strict_types=1);

namespace App\Pipes\ProcessGenerationJob;

use App\Contracts\GenerationServiceInterface;
use Closure;
use Illuminate\Support\Facades\Storage;

readonly class DownloadResult
{
    public function __construct(protected GenerationServiceInterface $generationService) {}

    public function handle(mixed $data, Closure $next) {
        $generationID = $data['generation']['id'];

        $url = $data['url'];

        $filePath = $this->generationService->getGenerationFilePath($generationID);

        $imageContent = file_get_contents($url);

        Storage::put($filePath, $imageContent);

        $this->generationService->updateFilePath($generationID, $filePath);

        return $next($data);
    }
}
