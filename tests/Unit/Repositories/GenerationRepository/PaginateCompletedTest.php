<?php

namespace Tests\Unit\Repositories\GenerationRepository;

use App\Models\Generation;
use App\Models\User;

class PaginateCompletedTest extends BaseGenerationRepository
{
    public function testPaginateShowCorrect(): void
    {
        // Given
        $givenUserId = 1;
        $givenGenerations = 1;
        $givenPage = 1;
        $givenPerPage = 1;
        $givenStatus = 'completed';
        $givenFilePath = 'some_random_file_path';
        $givenThumbnailPath = 'some_random_thumbnail_path';
        $givenArtStyle = 'some_random_art_style_id';
        $givenArtType = 'some_random_art_type_id';

        // Precondition
        User::factory()->create([
            'id' => $givenUserId,
        ]);
        Generation::factory()
            ->count($givenGenerations)
            ->create([
                'user_id' => $givenUserId,
                'file_path' => $givenFilePath,
                'status' => $givenStatus,
                'thumbnail_file_path' => $givenThumbnailPath,
                'art_style' => $givenArtStyle,
                'art_type' => $givenArtType,
            ]);

        // Expected
        $expectedGenerationsCount = $givenPerPage;
        $expectedThumbnailPath = $givenThumbnailPath;
        $expectedArtStyle = $givenArtStyle;
        $expectedArtType = $givenArtType;

        // Action
        $result = $this->generationRepository->paginateCompleted(
            $givenUserId,
            $givenPage,
            $givenPerPage
        );

        // Assert
        $this->assertCount($expectedGenerationsCount, $result);
        $this->assertEquals($expectedThumbnailPath, $result[0]['thumbnail_file_path']);
        $this->assertEquals($expectedArtStyle, $result[0]['art_style']);
        $this->assertEquals($expectedArtType, $result[0]['art_type']);
    }

    public function testPaginateShowAll(): void
    {
        // Given
        $givenUserId = 1;
        $givenGenerations = 15;
        $givenPage = 1;
        $givenPerPage = 100;
        $givenStatus = 'completed';
        $givenFilePath = 'file_path';

        // Precondition
        User::factory()->create([
            'id' => $givenUserId,
        ]);
        Generation::factory()
            ->count($givenGenerations)
            ->create([
                'user_id' => $givenUserId,
                'file_path' => $givenFilePath,
                'status' => $givenStatus,
            ]);

        // Expected
        $expectedGenerationsCount = $givenGenerations;

        // Action
        $result = $this->generationRepository->paginateCompleted(
            $givenUserId,
            $givenPage,
            $givenPerPage
        );

        // Assert
        $this->assertCount($expectedGenerationsCount, $result);
    }

    public function testPaginateNoResults(): void
    {
        // Given
        $givenUserId = 1;
        $givenPage = 1;
        $givenPerPage = 10;

        // Precondition
        User::factory()->create([
            'id' => $givenUserId,
        ]);
        // No generations created for this user

        // Expected
        $expectedGenerationsCount = 0;

        // Action
        $result = $this->generationRepository->paginateCompleted(
            $givenUserId,
            $givenPage,
            $givenPerPage
        );

        // Assert
        $this->assertCount($expectedGenerationsCount, $result);
        $this->assertEmpty($result);
    }
}
