<?php

namespace Tests\Unit\Actions\SignS3Url;

use Illuminate\Support\Facades\Storage;

class HandleTest extends BaseSignS3Url
{
    public function testWhenDataThenSuccess(): void
    {
        // Given
        $givenFilePath = 'fake/path/to/file.png';
        $givenDateExpiry = now()->addMinutes(5);

        // Mock
        $mockedUrl = 'https://s3.amazonaws.com/fake/path/to/file.png';

        Storage::shouldReceive('disk')
            ->with('s3')
            ->once()
            ->andReturnSelf();

        Storage::shouldReceive('temporaryUrl')
            ->with($givenFilePath, $givenDateExpiry)
            ->once()
            ->andReturn($mockedUrl);

        // Expected
        $expectedUrl = $mockedUrl;

        // Action
        $response = $this->action->handle(
            $givenFilePath,
            $givenDateExpiry
        );

        // Assert
        $this->assertEquals($expectedUrl, $response);
    }
}
