<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ArtRepositoryInterface;
use App\Contracts\GenerationRepositoryInterface;
use App\Contracts\GenerationRetrievalServiceInterface;
use Illuminate\Support\Facades\Storage;

readonly class GenerationRetrievalService implements GenerationRetrievalServiceInterface
{
    public function __construct(
        protected GenerationRepositoryInterface $generationRepository,
        protected ArtRepositoryInterface $artRepository,
    ) {}

    public function getPaginatedGenerations(
        int $userId,
        int $page = 1,
        int $perPage = 9
    ): array {
        $generations = $this->generationRepository->paginateCompleted($userId, $page, $perPage);

        $totalCount = $this->generationRepository->countCompleted($userId);

        $generations = array_map(function ($generation) {
            $generation['thumbnail_url'] = Storage::disk('s3')->temporaryUrl(
                $generation['thumbnail_file_path'],
                now()->addMinutes(5)
            );
            $generation['art_style'] = $this->artRepository->getStyle($generation['art_type'], $generation['art_style'])['name'];
            $generation['art_type'] = $this->artRepository->getType($generation['art_type'])['name'];
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

    public function getGeneration(string $userId, string $generationId): array
    {
        return $this->generationRepository->find($userId, $generationId);
    }

    public function getGenerationFileUrl(string $userId, string $generationId): string
    {
        return Storage::disk('s3')->temporaryUrl(
            $this->generationRepository->find($userId, $generationId)['file_path'],
            now()->addMinutes(5)
        );
    }
}
