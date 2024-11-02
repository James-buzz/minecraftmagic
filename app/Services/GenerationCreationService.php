<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ArtRepositoryInterface;
use App\Contracts\GenerationCreationServiceInterface;
use App\Models\Generation;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GenerationCreationService implements GenerationCreationServiceInterface
{
    public function __construct(
        protected ArtRepositoryInterface $artRepository,
    ) {}

    public function createGeneration(int $userId, string $artTypeId, string $artStyleId, array $metadata): string
    {
        $record = Generation::create([
            'user_id' => $userId,
            'art_type' => $artTypeId,
            'art_style' => $artStyleId,
            'metadata' => $metadata,
        ]);

        return $record->id;
    }

    public function updateStatusAsProcessing(string $generationId): void
    {
        $generation = Generation::find($generationId);

        if (! $generation) {
            throw new ModelNotFoundException("Generation with ID {$generationId} not found");
        }

        $generation->update(['status' => 'processing']);
    }

    public function updateStatusAsFailed(string $generationId, ?string $failedMessage): void
    {
        $generation = Generation::find($generationId);

        if (! $generation) {
            throw new ModelNotFoundException("Generation with ID {$generationId} not found");
        }

        $modelData = ['status' => 'failed'];

        if ($failedMessage !== null) {
            $modelData['failed_reason'] = $failedMessage;
        }

        Generation::findOrFail($generationId)
            ->update($modelData);
    }

    public function updateStatusAsCompleted(
        string $generationId,
        string $filePath,
        string $thumbnailFilePath
    ): void {
        $generation = Generation::find($generationId);

        if (! $generation) {
            throw new ModelNotFoundException("Generation with ID {$generationId} not found");
        }

        $generation->update([
            'status' => 'completed',
            'file_path' => $filePath,
            'thumbnail_file_path' => $thumbnailFilePath,
        ]);
    }

    public function getGenerationFilePath(string $userId, string $generationId): string
    {
        return sprintf(
            '/generations/%s/%s/original.png',
            $userId,
            $generationId
        );
    }

    public function getGenerationThumbnailFilePath(string $userId, string $generationId): string
    {
        return sprintf(
            '/generations/%s/%s/thumbnail.png',
            $userId,
            $generationId
        );
    }
}
