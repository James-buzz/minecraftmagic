<?php

namespace App\Repositories;

use App\Contracts\GenerationRepositoryInterface;
use App\Models\Generation;

class GenerationRepository implements GenerationRepositoryInterface
{
    public function find(string $generationId): array
    {
        return Generation::findOrFail($generationId)->toArray();
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
}
