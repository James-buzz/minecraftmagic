<?php

declare(strict_types=1);

namespace App\Contracts;

interface GenerationRetrievalServiceInterface
{
    public function getGeneration(string $generationId);

    public function getGenerationFileUrl(string $generationId);

    public function getPaginatedGenerations(
        int $userId,
        int $page = 1,
        int $perPage = 15
    );
}
