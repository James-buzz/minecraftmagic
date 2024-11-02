<?php

namespace Tests\Unit\Jobs\ProcessGenerationJob;

use App\Contracts\GenerationCreationServiceInterface;
use App\Contracts\GenerationRetrievalServiceInterface;
use App\Jobs\ProcessGenerationJob;
use App\Models\Generation;
use App\Models\User;
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
        $givenUserId = 101;
        $givenGenerationId = 201;
        $givenGenerationFilePath = 'path/to/generation/file';
        $givenGenerationThumbnailFilePath = 'path/to/generation/thumbnail/file';
        $givenGenerationArtType = 'server_logo';
        $givenGenerationArtStyle = 'dragons-lair';

        // Precondition
        User::factory()->create(['id' => $givenUserId]);

        Generation::factory()->create([
            'id' => $givenGenerationId,
            'user_id' => $givenUserId,
            'file_path' => $givenGenerationFilePath,
            'thumbnail_file_path' => $givenGenerationThumbnailFilePath,
            'art_type' => $givenGenerationArtType,
            'art_style' => $givenGenerationArtStyle,
        ]);

        // Mock
        /** @var MockInterface|GenerationCreationServiceInterface $mockGenerationCreationService */
        $mockGenerationCreationService = $this->mock(GenerationCreationServiceInterface::class);
        $mockGenerationCreationService->shouldReceive('updateStatusAsProcessing')
            ->with($givenGenerationId)
            ->once();
        $mockGenerationCreationService->shouldReceive('updateStatusAsCompleted')
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
                'art_type' => $givenGenerationArtType,
                'art_style' => $givenGenerationArtStyle,
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
                    'steps' => [
                        'RequestGeneration' => 0.1,
                        'DownloadLocal' => 0.2,
                        'ThumbnailGeneration' => 0.3,
                        'UploadToS3' => 0.4,
                        'CleanupLocal' => 0.5,
                    ],
                ];
                $closure($context);

                return true;
            });

        // Action
        $job = new ProcessGenerationJob((string) $givenUserId, (string) $givenGenerationId);
        $job->handle(
            $mockGenerationRetrievalService,
            $mockGenerationCreationService,
            $mockPipeline,
        );
    }
}
