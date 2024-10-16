<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Exceptions\ArtStyleNotFoundException;
use App\Exceptions\GenerationNotFoundException;
use App\Exceptions\UserNotFoundException;

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
     * @param  array<mixed>  $metadata
     *
     * @throws UserNotFoundException
     * @throws ArtStyleNotFoundException
     */
    public function createGeneration(
        int $userId,
        string $artTypeId,
        string $artStyleId,
        array $metadata
    ): string;

    /**
     * Set the generation as processing.
     *
     *
     * @throws GenerationNotFoundException
     */
    public function setGenerationAsProcessing(string $generationId): void;

    /**
     * Set the generation as completed.
     * Save the file path and thumbnail file path.
     *
     *
     * @throws GenerationNotFoundException
     */
    public function setGenerationAsCompleted(
        string $generationId,
        string $filePath,
        string $thumbnailFilePath
    ): void;

    /**
     * Set the generation as failed.
     *
     *
     * @throws GenerationNotFoundException
     */
    public function setGenerationAsFailed(string $generationId): void;
}
