<?php

declare(strict_types=1);

namespace App\Contracts;

interface GenerationRepositoryInterface
{
    /**
     * Find a generation by ID.
     *
     * @return array{id: string, status: string, art_type: string, art_style: string, file_path: string, thumbnail_file_path: string}|null
     */
    public function find(string $userId, string $generationId): ?array;

    /**
     * Update a generation by ID with data.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(string $generationId, array $data): void;

    /**
     * Create a new generation.
     *
     * @param  array<string, mixed>  $metadata
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
     * @return array<array{id: string, art_type: string, art_style: string, thumbnail_file_path: string}>
     */
    public function paginateCompleted(int $userId, int $page, int $perPage): array;

    /**
     * Count completed generations for a user.
     */
    public function countCompleted(int $userId): int;
}
