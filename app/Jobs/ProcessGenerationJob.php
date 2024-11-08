<?php

namespace App\Jobs;

use App\Actions\DeleteLocal;
use App\Actions\DownloadUrl;
use App\Actions\GenerateImage;
use App\Actions\Thumbnail;
use App\Actions\UploadToS3;
use App\Concerns\CalculatesFilePaths;
use App\Events\Generation\GenerationFailed;
use App\Events\Generation\GenerationStarted;
use App\Helpers\TimeFormatter;
use App\Models\Generation;
use ErrorException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Sentry\Laravel\Integration;
use Spatie\RateLimitedMiddleware\RateLimited;
use Throwable;

class ProcessGenerationJob implements ShouldQueue
{
    use CalculatesFilePaths;
    use Dispatchable;
    use Queueable;
    use SerializesModels;

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
            $this->buildPrompt(),
            $this->generation->metadata['image_size'],
            $this->generation->metadata['image_quality']
        );

        Log::info('Queue downloading image to local', ['url' => $imageUrl]);

        $generationFilePath = $this->calculatePath(
            $this->generation->user,
            $this->generation,
            'original.png'
        );

        DownloadUrl::run($imageUrl, $generationFilePath);

        $generationThumbnailPath = $this->calculatePath(
            $this->generation->user,
            $this->generation,
            'thumbnail.png'
        );

        Log::info('Queue generating thumbnail', ['path' => $generationThumbnailPath]);
        Thumbnail::run($generationFilePath, $generationThumbnailPath);

        Log::info('Queue uploading to S3', ['path' => $generationFilePath]);
        UploadToS3::run($generationFilePath);

        Log::info('Queue uploading thumbnail to S3', ['path' => $generationThumbnailPath]);
        UploadToS3::run($generationThumbnailPath);

        Log::info('Deleting local files');
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
            $this->generation,
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
     * Builds a prompt by replacing placeholders with field values from metadata
     * Example metadata format:
     * {
     *     "fields": {
     *         "server_name": "spooky"
     *     },
     *     "image_size": "1024x1024",
     *     "image_quality": "standard"
     * }
     *
     * @return string The processed prompt with replaced placeholders
     */
    private function buildPrompt(): string
    {
        $metadata = $this->generation->metadata;
        $prompt = $this->generation->style->prompt;

        $fields = $metadata['fields'] ?? [];

        foreach ($fields as $key => $value) {
            $placeholder = "<{$key}>";
            $prompt = str_replace($placeholder, $value, $prompt);
        }

        return $prompt;
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
    public function retryUntil(): \Illuminate\Support\Carbon
    {
        return now()->addDay();
    }
}
