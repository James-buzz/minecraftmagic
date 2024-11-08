<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ArtRepositoryInterface;
use App\Contracts\GenerationServiceInterface;
use App\Models\Archive\ArtStyle;
use App\Models\Archive\ArtType;
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
