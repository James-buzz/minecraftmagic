<?php

namespace Tests\Unit\Pipes\ProcessGenerationJob\ThumbnailGeneration;

use App\Models\Generation;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Mockery as m;
use Spatie\Image\Image;

class HandleTest extends BaseThumbnailGeneration
{
    public function testWhenPassedThenReturnCorrectData(): void
    {
        // Given
        $givenContextFilePath = 'path/to/file';
        $givenGenerationThumbnailFilePath = 'path/to/generation/thumbnail';

        // Precondition
        $preconditionUser = User::factory()->create();

        $preconditionGeneration = Generation::factory()->create([
            'user_id' => $preconditionUser->id,
            'status' => 'pending',
        ]);

        $givenData = [
            'user' => [
                'id' => $preconditionUser->id,
            ],
            'generation' => [
                'id' => $preconditionGeneration->id,
                'status' => $preconditionGeneration->status,
            ],
            'result' => [
                'file_path' => $givenContextFilePath,
            ],
        ];

        // Mock
        $this->mockCreationService
            ->shouldReceive('getGenerationThumbnailFilePath')
            ->once()
            ->with(m::type(Generation::class))
            ->andReturn($givenGenerationThumbnailFilePath);

        Storage::shouldReceive('disk')
            ->with('local')
            ->andReturnSelf();

        Storage::shouldReceive('path')
            ->with($givenContextFilePath)
            ->andReturn($givenContextFilePath);

        Storage::shouldReceive('path')
            ->with($givenGenerationThumbnailFilePath)
            ->andReturn($givenGenerationThumbnailFilePath);

        $imageFacade = $this->mock('alias:'.Image::class);
        $imageFacade
            ->shouldReceive('load')
            ->with($givenContextFilePath)
            ->andReturnSelf();
        $imageFacade
            ->shouldReceive('width')
            ->with(300)
            ->andReturnSelf();

        $imageFacade
            ->shouldReceive('save')
            ->with($givenGenerationThumbnailFilePath)
            ->andReturnSelf();

        // Expected
        $expectedOutputDataUser = [
            'id' => $preconditionUser->id,
        ];
        $expectedOutputDataGeneration = [
            'id' => $preconditionGeneration->id,
            'status' => $preconditionGeneration->status,
        ];
        $expectedOutputDataResult = [
            'file_path' => $givenContextFilePath,
            'thumbnail_file_path' => $givenGenerationThumbnailFilePath,
        ];

        // Action
        $this->pipe->handle($givenData, function ($actualData) use ($expectedOutputDataUser, $expectedOutputDataGeneration, $expectedOutputDataResult) {

            // Assert
            $this->assertEquals($expectedOutputDataUser, $actualData['user']);
            $this->assertEquals($expectedOutputDataGeneration, $actualData['generation']);
            $this->assertEquals($expectedOutputDataResult, $actualData['result']);

        });
    }
}
