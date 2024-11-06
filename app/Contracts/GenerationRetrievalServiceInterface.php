<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Generation;
use App\Models\User;

interface GenerationRetrievalServiceInterface
{
    /**
     * Get the temporary URL for the file associated with a generation.
     */
    public function getGenerationFileUrl(Generation $generation): string;

    /**
     * Get a paginated list of generations for a user.
     *
     * @return array{
     *     data: array<array{
     *         id: int,
     *         art_type: string,
     *         art_style: string,
     *         thumbnail_url: string
     *     }>,
     *     meta: array{
     *         current_page: int,
     *         per_page: int,
     *         total: int,
     *         last_page: int
     *     }
     * }
     */
    public function getPaginatedGenerations(User $user, int $page = 1, int $perPage = 15): array;
}
