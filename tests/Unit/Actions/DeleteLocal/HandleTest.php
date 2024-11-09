<?php

namespace Tests\Unit\Actions\DeleteLocal;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class HandleTest extends BaseDeleteLocal
{
    public function testWhenFileThenSuccess(): void
    {
        // Given
        $givenFilePath = 'file.txt';

        // Mock
        Storage::shouldReceive('disk')
            ->once()
            ->with('local')
            ->andReturnSelf();

        Storage::shouldReceive('delete')
            ->once()
            ->with($givenFilePath)
            ->andReturnTrue();

        // Action
        $this->action->handle($givenFilePath);
    }

    public function testWhenFileFailsToDeleteThenFail(): void
    {
        // Given
        $givenFilePath = 'file.txt';

        // Mock
        Storage::shouldReceive('disk')
            ->with('local')
            ->andReturnSelf();

        Storage::shouldReceive('delete')
            ->with($givenFilePath)
            ->andReturnFalse();

        // Expected
        $this->expectException(FileException::class);

        // Action
        $this->action->handle($givenFilePath);
    }
}
