<?php

declare(strict_types=1);

namespace App\Contracts;

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
     * @param string $artType
     * @param string $artStyle
     * @param array<mixed> $metadata
     * @return string
     */
    public function createGeneration(
        int $userId,
        string $artType,
        string $artStyle,
        array $metadata
    ): string;

    /**
     * Set the generation as processing.
     *
     * @param string $generationId
     * @return void
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
     */
    public function setGenerationAsFailed(string $generationId): void;
}
