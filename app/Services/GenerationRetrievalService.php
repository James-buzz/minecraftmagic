<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ArtRepositoryInterface;
use App\Contracts\GenerationRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use App\Contracts\GenerationRetrievalServiceInterface;

readonly class GenerationRetrievalService implements GenerationRetrievalServiceInterface
{
    public function __construct(
        protected readonly GenerationRepositoryInterface $generationRepository,
        protected readonly ArtRepositoryInterface $artRepository,
    ) {
    }

    public function getPaginatedGenerations(
        int $userId,
        int $page = 1,
        int $perPage = 9
    ): array {
        $generations = $this->generationRepository->paginateCompleted($userId, $page, $perPage);

        $totalCount = $this->generationRepository->countCompleted($userId);

        $generations = array_map(function ($generation) {
            $generation['thumbnail_url'] = Storage::temporaryUrl(
                $generation['thumbnail_file_path'],
                now()->addMinutes(5)
            );
            $generation['art_style'] = $this->artRepository->getArtStyleForArtType($generation['art_type'], $generation['art_style'])['name'];
            $generation['art_type'] = $this->artRepository->getArtTypeByIdentifier($generation['art_type'])['name'];
            unset($generation['thumbnail_file_path']);

            return $generation;
        }, $generations);

        return [
            'data' => $generations,
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $totalCount,
                'last_page' => ceil($totalCount / $perPage),
            ],
        ];
    }

    public function getGeneration(string $generationId): array
    {
        return $this->generationRepository->find($generationId);
    }

    public function getGenerationFileUrl(string $generationId): string
    {
        return Storage::temporaryUrl(
            $this->generationRepository->find($generationId)['file_path'],
            now()->addMinutes(5)
        );
    }
}