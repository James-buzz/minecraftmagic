<?php

namespace App\Jobs;

use App\Contracts\ArtServiceInterface;
use App\Contracts\GenerationServiceInterface;
use App\Models\Generation;
use App\Pipes\ProcessGenerationJob\DownloadResult;
use App\Pipes\ProcessGenerationJob\RequestGeneration;
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
        protected readonly string $generationID,
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(
        GenerationServiceInterface $generationService,
        ArtServiceInterface        $artService
    ): void
    {
        $generationService->updateStatus($this->generationID, 'processing');

        $generation = $generationService->getGeneration($this->generationID);

        $context = [
            'generation' => $generation,
        ];

        app(Pipeline::class)
            ->send($context)
            ->through([
                RequestGeneration::class,
                DownloadResult::class,
            ])
            ->then(function ($context) use ($generationService) {
                $generation = $context['generation'];

                $generationService->updateStatus($generation['id'], 'completed');
            });
    }
}
