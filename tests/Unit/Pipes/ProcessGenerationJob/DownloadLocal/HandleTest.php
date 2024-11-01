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
        $expectedOutputDataUser = $givenContextUserId;
        $expectedOutputDataGeneration = [
            'id' => $givenContextGenerationId,
        ];
        $expectedOutputDataResult = [
            'file_path' => $givenGenerationFilePath,
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
