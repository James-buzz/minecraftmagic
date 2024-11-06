<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ArtRepositoryInterface;
use App\Contracts\GenerationServiceInterface;
use App\Models\ArtStyle;
use App\Models\ArtType;
use App\Models\Generation;
use App\Models\User;

readonly class GenerationService implements GenerationServiceInterface
{
    public function __construct(
        protected ArtRepositoryInterface $artRepository,
    ) {}

    public function createGeneration(User $user, ArtType $artType, ArtStyle $artStyle, array $metadata): Generation
    {
        return Generation::create([
            'user_id' => $user->id,
            'art_type' => $artType->id,
            'art_style' => $artStyle->id,
            'metadata' => $metadata,
        ]);
    }

    public function updateStatusAsProcessing(Generation $generation): void
    {
        $generation->update(['status' => 'processing']);
    }

    public function updateStatusAsFailed(Generation $generation, ?string $failedMessage): void
    {
        $modelData = ['status' => 'failed'];

        if ($failedMessage !== null) {
            $modelData['failed_reason'] = $failedMessage;
        }

        $generation->update($modelData);
    }

    public function updateStatusAsCompleted(Generation $generation, string $filePath, string $thumbnailFilePath): void
    {
        $generation->update([
            'status' => 'completed',
            'file_path' => $filePath,
            'thumbnail_file_path' => $thumbnailFilePath,
        ]);
    }

    public function getGenerationFilePath(Generation $generation): string
    {
        return sprintf(
            '/generations/%s/%s/original.png',
            $generation->user_id,
            $generation->id
        );
    }

    public function getGenerationThumbnailFilePath(Generation $generation): string
    {
        return sprintf(
            '/generations/%s/%s/thumbnail.png',
            $generation->user_id,
            $generation->id
        );
    }
}
