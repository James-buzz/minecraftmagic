<?php

declare(strict_types=1);

namespace App\Contracts;

interface ArtServiceInterface
{
    /**
     * Get art style for a given art type and art style identifier.
     *
     * @param string $artTypeId
     * @param string $artStyleId
     * @return array{identifier: string, name: string, description: string}
     */
    public function getArtStyle(string $artTypeId, string $artStyleId): array;

    /**
     * Get all art types with their styles.
     *
     * @return array<array{identifier: string, name: string, description: string, styles: array<array{identifier: string, name: string, description: string}>}>
     */
    public function getAllArtTypesWithStyles(): array;

    /**
     * Get all art types.
     *
     * @return array<array{identifier: string, name: string, description: string}>
     */
    public function getAllArtTypes(): array;
}
