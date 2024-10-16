<?php

namespace Tests\Unit\Pipes\ProcessGenerationJob\DownloadLocal;

class HandleTest extends BaseDownloadLocal
{
    public function testWhenPassedThenReturnCorrectData(): void
    {
        // Given
        $givenContextUserId = '1';
        $givenContextGenerationId = '11';
        $givenContextUrl = 'http://openai.com/image.jpg';
        $givenData = [
            'user' => $givenContextUserId,
            'generation' => [
                'id' => $givenContextGenerationId,
            ],
            'url' => $givenContextUrl,
        ];
        $givenGenerationFilePath = '/generations/1/11/original.jpg';

        // Mock
        $this->mockCreationService
            ->shouldReceive('getGenerationFilePath')
            ->with($givenContextUserId, $givenContextGenerationId)
            ->andReturn($givenGenerationFilePath);

        // Expected
        $expectedOutputData = [
            'user' => $givenContextUserId,
            'generation' => [
                'id' => $givenContextGenerationId,
            ],
            'url' => $givenContextUrl,
            'result' => [
                'file_path' => $givenGenerationFilePath,
            ],
        ];

        // Action
        $this->pipe->handle($givenData, function ($data) use ($expectedOutputData) {

            // Assert
            $this->assertEquals($expectedOutputData, $data);

        });
    }
}
