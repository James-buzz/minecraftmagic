<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\ArtStyle;
use App\Models\ArtType;
use App\Models\Generation;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface GenerationServiceInterface
{
    /**
     * Get the file path for the generation.
     */
    public function getGenerationFilePath(Generation $generation): string;

    /**
     * Get the thumbnail file path for the generation.
     */
    public function getGenerationThumbnailFilePath(Generation $generation): string;

    /**
     * Request and create a new generation.
     * Return the generation ID.
     *
     * @param  array<string, mixed>  $metadata
     */
    public function createGeneration(User $user, ArtType $artType, ArtStyle $artStyle, array $metadata): Generation;

    /**
     * Set the status of Generation as processing.
     *
     * @throws ModelNotFoundException
     */
    public function updateStatusAsProcessing(Generation $generation): void;

    /**
     * Set the generation as completed.
     * Save the file path and thumbnail file path.
     *
     * @throws ModelNotFoundException
     */
    public function updateStatusAsCompleted(Generation $generation, string $filePath, string $thumbnailFilePath): void;

    /**
     * Set the generation as failed.
     *
     * @throws ModelNotFoundException
     */
    public function updateStatusAsFailed(Generation $generation, ?string $failedMessage): void;
}
