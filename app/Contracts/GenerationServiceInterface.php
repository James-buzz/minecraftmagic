<?php
declare(strict_types=1);

namespace App\Contracts;

interface GenerationServiceInterface
{
    /**
     * Update the status of a generation.
     *
     * @param string $generationId
     * @param string $status
     * @return void
     */
    public function updateStatus(string $generationId, string $status): void;

    /**
     * Update the file path of a generation.
     *
     * @param string $generationId
     * @param string $filePath
     * @return void
     */
    public function updateFilePath(string $generationId, string $filePath): void;

    /**
     * Request the generation of new art.
     * Return status ID.
     *
     * @param int $userId
     * @param string $artType
     * @param string $artStyle
     * @param array $metadata
     * @return string
     */
    public function requestGeneration(
        int $userId,
        string $artType,
        string $artStyle,
        array $metadata
    ): string;

    /**
     * Get a generation by ID.
     *
     * @param string $generationId
     * @return array<{id: string, art_style: string, art_type: string, metadata: array, status: string}>
     */
    public function getGeneration(string $generationId): array;

    /**
     * Get a displayable generation status.
     *
     * @param string $generationId
     * @return array<{id: string, art_style: string, art_type: string, metadata: array, status: string}>
     */
    public function getGenerationStyle(string $generationId): array;

    /**
     * Get the file path for a generation.
     *
     * @param string $generationId
     * @return string
     */
    public function getGenerationFilePath(string $generationId): string;
}
