<?php

declare(strict_types=1);

namespace App\Contracts;

interface GenerationRepositoryInterface
{
    /**
     * Find a generation by ID.
     *
     * @param string $generationId
     * @return array{id: string, status: string, art_type: string, art_style: string, file_path: string, thumbnail_file_path: string}
     */
    public function find(string $generationId): array;

    /**
     * Update a generation by ID with data.
     *
     * @param string $generationId
     * @param array<string, mixed> $data
     * @return void
     */
    public function update(string $generationId, array $data): void;

    /**
     * Create a new generation.
     *
     * @param int $userId
     * @param string $artType
     * @param string $artStyle
     * @param array<string, mixed> $metadata
     * @return string
     */
    public function create(
        int $userId,
        string $artType,
        string $artStyle,
        array $metadata
    ): string;

    /**
     * Paginate completed generations for a user.
     *
     * @param int $userId
     * @param int $page
     * @param int $perPage
     * @return array<mixed>
     */
    public function paginateCompleted(int $userId, int $page, int $perPage): array;

    /**
     * Count completed generations for a user.
     *
     * @param int $userId
     * @return int
     */
    public function countCompleted(int $userId): int;
}
