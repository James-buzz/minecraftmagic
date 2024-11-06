<?php

namespace App\Contracts;

use App\Models\ArtStyle;
use App\Models\ArtType;

interface ArtRepositoryInterface
{
    /**
     * Get all art types (without styles)
     *
     * @return ArtType[]
     */
    public function getTypes(): array;

    /**
     * Get an art type by its identifier (without styles)
     */
    public function getType(string $typeId): ?ArtType;

    /**
     * Get all art styles for a given art type
     *
     * @return ArtStyle[]
     */
    public function getStyles(string $typeId): array;

    /**
     * Get an art style for a given art type
     */
    public function getStyle(string $typeId, string $styleId): ?ArtStyle;
}
