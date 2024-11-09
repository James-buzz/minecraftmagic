<?php

namespace Tests\Unit\Actions\DownloadUrl;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class HandleTest extends BaseDownloadUrl
{
    public function testWhenFileAndUrlThenSuccess(): void
    {
        // Given
        $givenUrl = 'http://example.com/file.txt';
        $givenFilePath = 'path/to/file.txt';

        // Mock
        $mockUrlResponse = 'file content';

        Http::shouldReceive('get')
            ->once()
            ->with($givenUrl)
            ->andReturnSelf();

        Http::shouldReceive('body')
            ->once()
            ->andReturn($mockUrlResponse);

        Storage::shouldReceive('put')
            ->once()
            ->with($givenFilePath, $mockUrlResponse)
            ->andReturnTrue();

        // Action
        $this->action->handle(
            $givenUrl,
            $givenFilePath
        );
    }

    public function testWhenFileFailsToPutThenFail(): void
    {
        // Given
        $givenUrl = 'http://example.com/file.txt';
        $givenFilePath = 'path/to/file.txt';

        // Mock
        $mockUrlResponse = 'file content';

        Http::shouldReceive('get')
            ->once()
            ->with($givenUrl)
            ->andReturnSelf();

        Http::shouldReceive('body')
            ->once()
            ->andReturn($mockUrlResponse);

        Storage::shouldReceive('put')
            ->once()
            ->with($givenFilePath, $mockUrlResponse)
            ->andReturnFalse();

        // Expected
        $this->expectException(FileException::class);

        // Action
        $this->action->handle(
            $givenUrl,
            $givenFilePath
        );
    }
}
