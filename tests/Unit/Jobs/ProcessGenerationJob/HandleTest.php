<?php

namespace Tests\Unit\Jobs\ProcessGenerationJob;

use App\Contracts\GenerationServiceInterface;
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
        $givenGenerationFilePath = 'path/to/generation/file';
        $givenGenerationThumbnailFilePath = 'path/to/generation/thumbnail/file';
        $givenGenerationArtType = 'server_logo';
        $givenGenerationArtStyle = 'dragons-lair';

        // Precondition
        $preconditionUser = User::factory()->create();

        $preconditionGeneration = Generation::factory()->create([
            'user_id' => $preconditionUser->id,
            'art_type' => $givenGenerationArtType,
            'art_style' => $givenGenerationArtStyle,
            'metadata' => [],
        ]);

        $preconditionContext = [
            'generation' => [
                'id' => $preconditionGeneration->id,
                'art_type' => $preconditionGeneration->art_type,
                'art_style' => $preconditionGeneration->art_style,
                'metadata' => $preconditionGeneration->metadata,
            ],
            'user' => [
                'id' => $preconditionUser->id,
                'email' => $preconditionUser->email,
                'name' => $preconditionUser->name,
            ],
        ];

        // Mock
        /** @var MockInterface|GenerationServiceInterface $mockGenerationCreationService */
        $mockGenerationCreationService = $this->mock(GenerationServiceInterface::class);
        $mockGenerationCreationService->shouldReceive('updateStatusAsProcessing')
            ->with($preconditionGeneration)
            ->once();
        $mockGenerationCreationService->shouldReceive('updateStatusAsCompleted')
            ->with($preconditionGeneration, $givenGenerationFilePath, $givenGenerationThumbnailFilePath)
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
            ->withArgs(function ($closure) use ($preconditionGeneration, $givenGenerationFilePath, $givenGenerationThumbnailFilePath) {
                $context = [
                    'generation' => [
                        'id' => $preconditionGeneration->id,
                        'user_id' => $preconditionGeneration->user_id,
                        'art_type' => $preconditionGeneration->art_type,
                        'art_style' => $preconditionGeneration->art_style,
                    ],
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
        $job = new ProcessGenerationJob(
            $preconditionUser,
            $preconditionGeneration,
        );
        $job->handle(
            $mockGenerationCreationService,
            $mockPipeline,
        );
    }
}
