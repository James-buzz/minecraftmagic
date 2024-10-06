<?php

declare(strict_types=1);

namespace App\Contracts;

interface GenerationCreationServiceInterface
{
    public function getGenerationFilePath(string $userId, string $generationId): string;

    public function getGenerationThumbnailFilePath(string $userId, string $generationId): string;

    public function createGeneration(
        int $userId,
        string $artType,
        string $artStyle,
        array $metadata
    ): string;

    public function setGenerationAsProcessing(string $generationId): void;

    public function setGenerationAsCompleted(
        string $generationId,
        string $filePath,
        string $thumbnailFilePath
    ): void;

    public function setGenerationAsFailed(string $generationId): void;
}
