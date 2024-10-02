<?php
declare(strict_types=1);

namespace App\Contracts;

interface GenerationRepositoryInterface
{
    /**
     * Find a generation by ID.
     *
     * @param string $generationId
     * @return array<{id: string, art_style: string, art_type: string, metadata: array, status: string}>
     */
    public function find(string $generationId): array;

    /**
     * Create a new generation.
     *
     * @param int $userId
     * @param string $artType
     * @param string $artStyle
     * @param array $metadata
     * @return string
     */
    public function create(
        int $userId,
        string $artType,
        string $artStyle,
        array $metadata
    ): string;

    /**
     * Update assignable data for a generation.
     *
     * @param string $generationId
     * @param array<mixed> $data
     * @return void
     */
    public function update(string $generationId, array $data): void;
}
