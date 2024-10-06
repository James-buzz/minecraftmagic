<?php

declare(strict_types=1);

namespace App\Contracts;

interface GenerationRepositoryInterface
{
    public function find(string $generationId): array;

    public function update(string $generationId, array $data): void;

    public function create(
        int $userId,
        string $artType,
        string $artStyle,
        array $metadata
    ): string;

    public function paginateCompleted(int $userId, int $page, int $perPage): array;

    public function countCompleted(int $userId): int;
}
