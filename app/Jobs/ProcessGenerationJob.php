<?php

namespace App\Jobs;

use App\Contracts\GenerationCreationServiceInterface;
use App\Contracts\GenerationRetrievalServiceInterface;
use App\Events\Generation\GenerationCompleted;
use App\Events\Generation\GenerationFailed;
use App\Events\Generation\GenerationStarted;
use App\Exceptions\GenerationNotFoundException;
use App\Helpers\TimeFormatter;
use App\Pipes\ProcessGenerationJob\CleanupLocal;
use App\Pipes\ProcessGenerationJob\DownloadLocal;
use App\Pipes\ProcessGenerationJob\RequestGeneration;
use App\Pipes\ProcessGenerationJob\ThumbnailGeneration;
use App\Pipes\ProcessGenerationJob\UploadToS3;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Log;
use OpenAI\Exceptions\ErrorException;
use Throwable;

class ProcessGenerationJob implements ShouldQueue
{
    use Queueable;

    /**
     * @var int The number of times the job may be attempted.
     */
    public int $tries = 2;

    private float $startTime;

    private string $artType;

    private string $artStyle;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected readonly string $userId,
        protected readonly string $generationID,
    ) {}

    /**
     * Execute the job.
     *
     * @throws GenerationNotFoundException
     */
    public function handle(
        GenerationRetrievalServiceInterface $generationRetrievalService,
        GenerationCreationServiceInterface $generationCreationService,
        Pipeline $pipeline,
    ): void {
        $this->startTime = microtime(true);

        $generationCreationService->setGenerationAsProcessing($this->generationID);

        $generation = $generationRetrievalService->getGeneration($this->userId, $this->generationID);

        Log::info('Started art generation', ['generation_id' => $this->generationID]);

        $this->artType = $generation['art_type'];
        $this->artStyle = $generation['art_style'];

        event(new GenerationStarted(
            $this->artType,
            $this->artStyle,
        ));

        $context = [
            'generation' => $generation,
            'user' => $this->userId,
        ];

        $pipeline
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

                $stepTimes = $context['steps'];
                $duration = microtime(true) - $this->startTime;

                event(new GenerationCompleted(
                    $this->artType,
                    $this->artStyle,
                    $duration,
                    $stepTimes
                ));

                Log::info('Art generation completed', [
                    'generation_id' => $contextGenerationId,
                    'duration' => TimeFormatter::formatPeriod(microtime(true), $this->startTime),
                ]);
            });
    }

    public function failed(?Throwable $exception): void
    {
        $generationCreationService = app(GenerationCreationServiceInterface::class);

        $failedMessage = null;
        if ($exception instanceof ErrorException) {
            $failedMessage = substr($exception->getMessage(), 0, 255);
        }

        $generationCreationService->setGenerationAsFailed(
            $this->generationID,
            $failedMessage
        );

        $totalDuration = microtime(true) - ($this->startTime ?? microtime(true));

        event(new GenerationFailed(
            $this->artType ?? 'unknown',
            $this->artStyle ?? 'unknown',
            $exception,
            $totalDuration
        ));

        Log::info('Failed art generation', [
            'generation_id' => $this->generationID,
            'duration' => TimeFormatter::formatPeriod(microtime(true), $this->startTime ?? microtime(true)),
            'exception' => $exception,
        ]);
    }
}
