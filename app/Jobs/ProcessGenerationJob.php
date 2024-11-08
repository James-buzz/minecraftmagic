<?php

namespace App\Jobs;

use App\Contracts\GenerationServiceInterface;
use App\Events\Generation\GenerationCompleted;
use App\Events\Generation\GenerationFailed;
use App\Events\Generation\GenerationStarted;
use App\Helpers\TimeFormatter;
use App\Models\Generation;
use App\Models\User;
use App\Pipes\ProcessGenerationJob\CleanupLocal;
use App\Pipes\ProcessGenerationJob\DownloadLocal;
use App\Pipes\ProcessGenerationJob\RequestGeneration;
use App\Pipes\ProcessGenerationJob\ThumbnailGeneration;
use App\Pipes\ProcessGenerationJob\UploadToS3;
use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use OpenAI\Exceptions\ErrorException;
use Sentry\Laravel\Integration;
use Spatie\RateLimitedMiddleware\RateLimited;
use Throwable;

class ProcessGenerationJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var int The max exceptions that can be thrown before failing the job.
     */
    public int $maxExceptions = 2;

    private float $startTime;

    private string $artType;

    private string $artStyle;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected readonly User $user,
        protected readonly Generation $generation,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        GenerationServiceInterface $generationCreationService,
        Pipeline $pipeline,
    ): void {
        $this->startTime = microtime(true);

        $generationCreationService->updateStatusAsProcessing($this->generation);

        Log::info('Queue started art generation', ['generation_id' => $this->generation->id]);

        $this->artType = $this->generation->art_type;
        $this->artStyle = $this->generation->art_style;

        event(new GenerationStarted(
            $this->artType,
            $this->artStyle,
        ));

        $context = [
            'generation' => [
                'id' => $this->generation->id,
                'art_type' => $this->generation->art_type,
                'art_style' => $this->generation->art_style,
                'metadata' => $this->generation->metadata,
            ],
            'user' => [
                'id' => $this->user->id,
                'email' => $this->user->email,
                'name' => $this->user->name,
            ],
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

                $generationCreationService->updateStatusAsCompleted(
                    $this->generation,
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

                Log::info('Queue completed art generation', [
                    'generation_id' => $contextGenerationId,
                    'duration' => TimeFormatter::formatPeriod(microtime(true), $this->startTime),
                ]);
            });
    }

    /**
     * When the job fails.
     *
     * @throws Throwable
     */
    public function failed(?Throwable $exception): void
    {
        $generationCreationService = app(GenerationServiceInterface::class);

        $failedMessage = null;
        if ($exception instanceof ErrorException) {
            $failedMessage = substr($exception->getMessage(), 0, 255);
        }

        $generationCreationService->updateStatusAsFailed($this->generation, $failedMessage);

        $totalDuration = microtime(true) - ($this->startTime ?? microtime(true));

        event(new GenerationFailed(
            $this->artType ?? 'unknown',
            $this->artStyle ?? 'unknown',
            $exception,
            $totalDuration
        ));

        Log::info('Queue failed art generation', [
            'generation_id' => $this->generation->id,
            'duration' => TimeFormatter::formatPeriod(microtime(true), $this->startTime ?? microtime(true)),
            'exception' => $exception,
        ]);

        // Send to Sentry
        Integration::captureUnhandledException($exception);
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<mixed>
     */
    public function middleware(): array
    {
        $rateLimitedMiddleware = (new RateLimited)
            ->allow(5)
            ->everySeconds(60)
            ->releaseAfterSeconds(30);

        return [$rateLimitedMiddleware];
    }

    /*
     * Determine the time at which the job should timeout.
     *
     */
    public function retryUntil(): DateTime
    {
        return now()->addDay();
    }
}
