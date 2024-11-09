<?php

namespace Tests\Unit\Actions\UploadToS3;

use Illuminate\Support\Facades\Storage;

class HandleTest extends BaseUploadToS3
{
    public function testWhenFileThenUploadToS3(): void
    {
        // Given
        $givenFilePath = 'fake/path/to/file.png';

        // Mock
        $mockFileContent = 'fake file content';

        Storage::shouldReceive('disk')
            ->with('s3')
            ->once()
            ->andReturnSelf();

        Storage::shouldReceive('disk')
            ->with('local')
            ->once()
            ->andReturnSelf();

        Storage::shouldReceive('get')
            ->with($givenFilePath)
            ->once()
            ->andReturn($mockFileContent);

        Storage::shouldReceive('put')
            ->with($givenFilePath, $mockFileContent)
            ->once();

        // Action
        $this->action->handle($givenFilePath);
    }
}
