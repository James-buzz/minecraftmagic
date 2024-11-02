<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\ModelNotFoundException;

interface GenerationCreationServiceInterface
{
    /**
     * Get the file path for the generation.
     */
    public function getGenerationFilePath(string $userId, string $generationId): string;

    /**
     * Get the thumbnail file path for the generation.
     */
    public function getGenerationThumbnailFilePath(string $userId, string $generationId): string;

    /**
     * Request and create a new generation.
     * Return the generation ID.
     *
     * @param  array<string, mixed>  $metadata
     */
    public function createGeneration(
        int $userId,
        string $artTypeId,
        string $artStyleId,
        array $metadata
    ): string;

    /**
     * Set the status of Generation as processing.
     *
     * @throws ModelNotFoundException
     */
    public function updateStatusAsProcessing(string $generationId): void;

    /**
     * Set the generation as completed.
     * Save the file path and thumbnail file path.
     *
     * @throws ModelNotFoundException
     */
    public function updateStatusAsCompleted(
        string $generationId,
        string $filePath,
        string $thumbnailFilePath
    ): void;

    /**
     * Set the generation as failed.
     *
     * @throws ModelNotFoundException
     */
    public function updateStatusAsFailed(string $generationId, ?string $failedMessage): void;
}
