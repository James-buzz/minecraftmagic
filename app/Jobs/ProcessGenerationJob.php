<?php

namespace App\Jobs;

use App\Contracts\ArtServiceInterface;
use App\Models\Generation;
use App\Pipes\ProcessGenerationJob\CleanupLocal;
use App\Pipes\ProcessGenerationJob\DownloadLocal;
use App\Pipes\ProcessGenerationJob\RequestGeneration;
use App\Pipes\ProcessGenerationJob\ThumbnailGeneration;
use App\Pipes\ProcessGenerationJob\UploadToS3;
use App\Services\GenerationCreationService;
use App\Services\GenerationRetrievalService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Pipeline\Pipeline;

class ProcessGenerationJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected readonly string $userId,
        protected readonly string $generationID,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(
        GenerationRetrievalService $generationRetrivalService,
        GenerationCreationService $generationCreationService,
        ArtServiceInterface $artService
    ): void {
        $generationCreationService->setGenerationAsProcessing($this->generationID);

        $generation = $generationRetrivalService->getGeneration($this->generationID);

        $context = [
            'generation' => $generation,
            'user' => $this->userId,
        ];

        app(Pipeline::class)
            ->send($context)
            ->through([
                RequestGeneration::class,
                DownloadLocal::class,
                ThumbnailGeneration::class,
                UploadToS3::class,
                CleanupLocal::class,
            ])
            ->then(function ($context) use ($generationCreationService) {
                $contextGenerationId = $context['generation']['id'];
                $contextFilePath = $context['result']['file_path'];
                $contextThumbnailPath = $context['result']['thumbnail_file_path'];

                $generationCreationService->setGenerationAsCompleted(
                    $contextGenerationId,
                    $contextFilePath,
                    $contextThumbnailPath
                );
            });
    }

    public function failed(): void
    {
        // temporary
        Generation::findOrFail($this->generationID)->update([
            'status' => 'failed',
        ]);
    }
}
