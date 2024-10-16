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
     *
     * @param string $userId
     * @param string $generationId
     * @return string
     */
    public function getGenerationFilePath(string $userId, string $generationId): string;

    /**
     * Get the thumbnail file path for the generation.
     *
     * @param string $userId
     * @param string $generationId
     * @return string
     */
    public function getGenerationThumbnailFilePath(string $userId, string $generationId): string;

    /**
     * Request and create a new generation.
     * Return the generation ID.
     *
     * @param int $userId
     * @param string $artTypeId
     * @param string $artStyleId
     * @param array<mixed> $metadata
     * @return string
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
     * @param string $generationId
     * @return void
     *
     * @throws GenerationNotFoundException
     */
    public function setGenerationAsProcessing(string $generationId): void;

    /**
     * Set the generation as completed.
     * Save the file path and thumbnail file path.
     *
     * @param string $generationId
     * @param string $filePath
     * @param string $thumbnailFilePath
     * @return void
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
     * @param string $generationId
     * @return void
     *
     * @throws GenerationNotFoundException
     */
    public function setGenerationAsFailed(string $generationId): void;
}
