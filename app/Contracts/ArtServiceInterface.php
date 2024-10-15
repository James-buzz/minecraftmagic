<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Exceptions\ArtStyleNotFoundException;

interface ArtServiceInterface
{
    /**
     * Get art style for a given art type and art style identifier.
     *
     * @return array{id: string, name: string, description: string, prompt: string}
     *
     * @throws ArtStyleNotFoundException
     */
    public function getArtStyle(string $artTypeId, string $artStyleId): array;

    /**
     * Get all art types with their styles.
     *
     * @return array<array{id: string, name: string, description: string, styles: array<array{id: string, name: string, description: string}>}>
     */
    public function getAllArtTypesWithStyles(): array;

    /**
     * Get all art types.
     *
     * @return array<array{id: string, name: string}>
     */
    public function getAllArtTypes(): array;
}
