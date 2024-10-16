<?php

namespace App\Repositories;

use App\Contracts\GenerationRepositoryInterface;
use App\Models\Generation;

class GenerationRepository implements GenerationRepositoryInterface
{
    public function find(string $generationId): ?array
    {
        $generation = Generation::find($generationId);

        if ($generation === null) {
            return null;
        }

        return $generation->toArray();
    }

    public function create(int $userId, string $artType, string $artStyle, array $metadata): string
    {
        $record = Generation::create([
            'user_id' => $userId,
            'art_type' => $artType,
            'art_style' => $artStyle,
            'metadata' => $metadata,
        ]);

        return $record->id;
    }

    public function update(string $generationId, array $data): void
    {
        Generation::findOrFail($generationId)->update($data);
    }

    public function paginateCompleted(int $userId, int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;

        return Generation::select(['id', 'art_type', 'art_style', 'thumbnail_file_path'])
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->whereNotNull('file_path')
            ->offset($offset)
            ->limit($perPage)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function countCompleted(int $userId): int
    {
        return Generation::where('user_id', $userId)
            ->where('status', 'completed')
            ->whereNotNull('file_path')
            ->count();
    }
}
