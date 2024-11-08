<?php

namespace App\Jobs;

use App\Actions\DownloadUrl;
use App\Actions\GenerateImage;
use App\Actions\Thumbnail;
use App\Actions\UploadToS3;
use App\Concerns\CalculatesFilePaths;
use App\Events\Generation\GenerationFailed;
use App\Events\Generation\GenerationStarted;
use App\Models\Generation;
use ErrorException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\RateLimitedMiddleware\RateLimited;
use Throwable;

class ProcessGenerationJob implements ShouldQueue
{
    use Queueable;
    use SerializesModels;
    use CalculatesFilePaths;

    /**
     * @var int The max exceptions that can be thrown before failing the job.
     */
    public int $maxExceptions = 2;

    private float $startTime;

    /**
     * Create a new job instance.
     */
    public function __construct(protected readonly Generation $generation) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->startTime = microtime(true);

        $this->generation->markAsProcessing();

        event(new GenerationStarted($this->generation));

        Log::info('Queue requesting OpenAI image generation');

        $imageUrl = GenerateImage::run(
            $this->generation->artStyle()->id,
            $this->generation->metadata['image_size'],
            $this->generation->metadata['image_quality']
        );

        Log::info('Queue downloading image to local', ['url' => $imageUrl]);

        $generationFilePath = $this->calculatePath(
            $this->generation->user,
            $this->generation,
            basename($imageUrl)
        );

        DownloadUrl::run($imageUrl, $generationFilePath);

        $generationThumbnailPath = $this->calculatePath(
            $this->generation->user,
            $this->generation,
            'thumbnail_'.basename($imageUrl)
        );

        Thumbnail::run($generationFilePath, $generationThumbnailPath, 300);

        UploadToS3::run($generationFilePath);
        UploadToS3::run($generationThumbnailPath);

        DeleteLocal::run($generationFilePath);
        DeleteLocal::run($generationThumbnailPath);

        $this->generation->markAsCompleted(
            $generationFilePath,
            $generationThumbnailPath
        );
    }

    /**
     * When the job fails.
     *
     * @throws Throwable
     */
    public function failed(?Throwable $exception): void
    {
        $failedMessage = null;
        if ($exception instanceof ErrorException) {
            $failedMessage = substr($exception->getMessage(), 0, 255);
        }

        $this->generation->markAsFailed($failedMessage);

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
