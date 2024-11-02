<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ArtRepositoryInterface;
use App\Contracts\GenerationRetrievalServiceInterface;
use App\Models\Generation;
use Illuminate\Support\Facades\Storage;

class GenerationRetrievalService implements GenerationRetrievalServiceInterface
{
    public function __construct(protected ArtRepositoryInterface $artRepository) {}

    public function getPaginatedGenerations(int $userId, int $page = 1, int $perPage = 9): array
    {
        $offset = ($page - 1) * $perPage;

        $foundGenerations = Generation::select(['id', 'art_type', 'art_style', 'thumbnail_file_path'])
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->whereNotNull('file_path')
            ->offset($offset)
            ->limit($perPage)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        $countCompleted = Generation::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereNotNull('file_path')
            ->count();

        $generations = array_map(function ($generation) {
            $generation['thumbnail_url'] = Storage::disk('s3')->temporaryUrl(
                $generation['thumbnail_file_path'],
                now()->addMinutes(5)
            );
            $generation['art_style'] = $this->artRepository->getStyle($generation['art_type'], $generation['art_style'])['name'];
            $generation['art_type'] = $this->artRepository->getType($generation['art_type'])['name'];
            unset($generation['thumbnail_file_path']);

            return $generation;
        }, $foundGenerations);

        return [
            'data' => $generations,
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $countCompleted,
                'last_page' => (int) ceil($countCompleted / $perPage),
            ],
        ];
    }

    public function getGeneration(string $userId, string $generationId): array
    {
        return Generation::select(['id', 'art_type', 'art_style', 'file_path', 'metadata', 'status'])
            ->where('user_id', $userId)
            ->where('id', $generationId)
            ->firstOrFail()
            ->toArray();
    }

    public function getGenerationFileUrl(string $userId, string $generationId): string
    {
        $generation = Generation::select(['file_path'])
            ->where('user_id', $userId)
            ->where('id', $generationId)
            ->where('status', 'completed')
            ->firstOrFail();

        return Storage::disk('s3')->temporaryUrl(
            $generation['file_path'],
            now()->addMinutes(5)
        );
    }
}
