<?php

namespace App\Contracts;

interface ArtRepositoryInterface
{
    /**
     * Get all available art types.
     *
     * @return array<string, array{identifier: string, name: string}>
     */
    public function getTypes(): array;

    /**
     * Get all available styles for a specific art type.
     *
     * @param string $typeIdentifier
     * @return array<string, array{name: string, description: string}>
     */
    public function getStylesForType(string $typeIdentifier): array;

    /**
     * Get a specific art type by its identifier.
     *
     * @param string $typeIdentifier
     * @return array{identifier: string, name: string}|null
     */
    public function getTypeByIdentifier(string $typeIdentifier): ?array;

    /**
     * Get a specific style for a given art type.
     *
     * @param string $typeIdentifier
     * @param string $styleIdentifier
     * @return array{identifier: string, name: string, prompt: string}|null
     */
    public function getStyleForType(string $typeIdentifier, string $styleIdentifier): ?array;
}
