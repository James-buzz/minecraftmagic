<?php

namespace App\Contracts;

interface ArtRepositoryInterface
{
    /**
     * Get all art types
     *
     * @return array<array{identifier: string, displayName: string}>
     */
    public function getArtTypes(): array;

    /**
     * Get all art styles for a given art type
     *
     * @param string $typeIdentifier
     * @return array<array{identifier: string, displayName: string}>
     */
    public function getArtStylesForArtType(string $typeIdentifier): array;

    /**
     * Get an art type by its identifier
     *
     * @param string $typeIdentifier
     * @return array{identifier: string, displayName: string}|null
     */
    public function getArtTypeByIdentifier(string $typeIdentifier): ?array;

    /**
     * Get an art style for a given art type
     *
     * @param string $typeIdentifier
     * @param string $styleIdentifier
     * @return array{identifier: string, displayName: string}|null
     */
    public function getArtStyleForArtType(string $typeIdentifier, string $styleIdentifier): ?array;
}
