<?php

declare(strict_types=1);

namespace App\Contracts;

interface GenerationRetrievalServiceInterface
{
    /**
     * Get a generation by its ID.
     *
     * @param string $generationId
     * @return array<mixed>
     */
    public function getGeneration(string $generationId): array;

    /**
     * Get the temporary URL for the file associated with a generation.
     *
     * @param string $generationId
     * @return string
     */
    public function getGenerationFileUrl(string $generationId): string;

    /**
     * Get a paginated list of generations for a user.
     *
     * @param int $userId
     * @param int $page
     * @param int $perPage
     * @return array<mixed>
     */
    public function getPaginatedGenerations(
        int $userId,
        int $page = 1,
        int $perPage = 15
    ): array;
}
