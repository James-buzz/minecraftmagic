<?php

declare(strict_types=1);

namespace App\Contracts;

interface GenerationRetrievalServiceInterface
{
    /**
     * Get a generation by its ID.
     *
     * @return array<mixed>
     */
    public function getGeneration(string $userId, string $generationId): array;

    /**
     * Get the temporary URL for the file associated with a generation.
     */
    public function getGenerationFileUrl(string $userId, string $generationId): string;

    /**
     * Get a paginated list of generations for a user.
     *
     * @return array<mixed>
     */
    public function getPaginatedGenerations(
        int $userId,
        int $page = 1,
        int $perPage = 15
    ): array;
}
