<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ArtRepositoryInterface;
use App\Contracts\GenerationCreationServiceInterface;
use App\Contracts\GenerationRepositoryInterface;
use App\Jobs\ProcessGenerationJob;

readonly class GenerationCreationService implements GenerationCreationServiceInterface
{
    public function __construct(
        protected GenerationRepositoryInterface $generationRepository,
        protected ArtRepositoryInterface $artRepository,
    ) {}

    public function createGeneration(
        int $userId,
        string $artTypeId,
        string $artStyleId,
        array $metadata
    ): string {
        return $this->generationRepository->create($userId, $artTypeId, $artStyleId, $metadata);
    }

    public function setGenerationAsProcessing(string $generationId): void
    {
        $this->generationRepository->update($generationId, ['status' => 'processing']);
    }

    public function setGenerationAsFailed(string $generationId): void
    {
        $this->generationRepository->update($generationId, ['status' => 'failed']);
    }

    public function setGenerationAsCompleted(
        string $generationId,
        string $filePath,
        string $thumbnailFilePath
    ): void {
        $this->generationRepository->update(
            $generationId,
            [
                'status' => 'completed',
                'file_path' => $filePath,
                'thumbnail_file_path' => $thumbnailFilePath,
            ]
        );
    }

    public function getGenerationFilePath(string $userId, string $generationId): string
    {
        return sprintf(
            '/generations/%s/%s/original.png',
            $userId,
            $generationId
        );
    }

    public function getGenerationThumbnailFilePath(string $userId, string $generationId): string
    {
        return sprintf(
            '/generations/%s/%s/thumbnail.png',
            $userId,
            $generationId
        );
    }
}
