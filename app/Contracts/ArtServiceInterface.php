<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\ArtStyle;
use App\Models\ArtType;

interface ArtServiceInterface
{
    /**
     * Get art type for a given art type identifier.
     */
    public function getArtType(string $artTypeId): ArtType;

    /**
     * Get art style for a given art type and art style identifier.
     */
    public function getArtStyle(string $artTypeId, string $artStyleId): ArtStyle;

    /**
     * Get all art types with their associated styles.
     *
     * @return array<array{
     *     id: string,
     *     name: string,
     *     styles: array<ArtStyle>
     * }>
     */
    public function getAllArtTypesWithStyles(): array;

    /**
     * Get all art types.
     *
     * @return ArtType[]
     */
    public function getAllArtTypes(): array;
}
