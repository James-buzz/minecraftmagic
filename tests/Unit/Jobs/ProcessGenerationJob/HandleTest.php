<?php

namespace Tests\Unit\Jobs\ProcessGenerationJob;

use App\Contracts\GenerationCreationServiceInterface;
use App\Contracts\GenerationRetrievalServiceInterface;
use App\Pipes\ProcessGenerationJob\CleanupLocal;
use App\Pipes\ProcessGenerationJob\DownloadLocal;
use App\Pipes\ProcessGenerationJob\RequestGeneration;
use App\Pipes\ProcessGenerationJob\ThumbnailGeneration;
use App\Pipes\ProcessGenerationJob\UploadToS3;
use Illuminate\Pipeline\Pipeline;
use Mockery\MockInterface;

class HandleTest extends BaseProcessGenerationJob
{
    public function testWhenJobIsHandledThenSuccess(): void
    {
        // Given
        $givenUserId = $this->userId;
        $givenGenerationId = $this->generationId;
        $givenGenerationFilePath = 'path/to/generation/file';
        $givenGenerationThumbnailFilePath = 'path/to/generation/thumbnail/file';

        // Mock
        /** @var MockInterface|GenerationCreationServiceInterface $mockGenerationCreationService */
        $mockGenerationCreationService = $this->mock(GenerationCreationServiceInterface::class);
        $mockGenerationCreationService->shouldReceive('setGenerationAsProcessing')
            ->with($givenGenerationId)
            ->once();
        $mockGenerationCreationService->shouldReceive('setGenerationAsCompleted')
            ->with($givenGenerationId, $givenGenerationFilePath, $givenGenerationThumbnailFilePath)
            ->once();

        /** @var MockInterface|GenerationRetrievalServiceInterface $mockGenerationRetrievalService */
        $mockGenerationRetrievalService = $this->mock(GenerationRetrievalServiceInterface::class);
        $mockGenerationRetrievalService->shouldReceive('getGeneration')
            ->with($givenUserId, $givenGenerationId)
            ->andReturn([
                'id' => $givenGenerationId,
                'file_path' => $givenGenerationFilePath,
                'thumbnail_file_path' => $givenGenerationThumbnailFilePath,
            ])
            ->once();

        /** @var MockInterface|Pipeline $mockPipeline */
        $mockPipeline = $this->mock(Pipeline::class);
        $mockPipeline->shouldReceive('send')->andReturnSelf();
        $mockPipeline->shouldReceive('through')
            ->with([
                RequestGeneration::class,
                DownloadLocal::class,
                ThumbnailGeneration::class,
                UploadToS3::class,
                CleanupLocal::class,
            ])
            ->andReturnSelf();
        $mockPipeline->shouldReceive('then')
            ->withArgs(function ($closure) use ($givenGenerationId, $givenGenerationFilePath, $givenGenerationThumbnailFilePath) {
                $context = [
                    'generation' => ['id' => $givenGenerationId],
                    'result' => [
                        'file_path' => $givenGenerationFilePath,
                        'thumbnail_file_path' => $givenGenerationThumbnailFilePath,
                    ],
                ];
                $closure($context);

                return true;
            });

        // Action
        $this->job->handle(
            $mockGenerationRetrievalService,
            $mockGenerationCreationService,
            $mockPipeline,
        );
    }
}
