<?php
declare(strict_types=1);

namespace App\Contracts;

interface ArtServiceInterface
{
    /**
     * @param string $artType
     * @param string $artStyle
     * @return array
     */
    public function getArtStyle(string $artType, string $artStyle): array;

    /**
     * Get all available art types with their styles.
     *
     * @return array<string, array{
     *     identifier: string,
     *     displayName: string,
     *     styles: array<string, array{name: string, description: string}>
     * }>
     */
    public function getAllTypesWithStyles(): array;

    /**
     * Get all available art types.
     *
     * @return array<string, array{identifier: string, displayName: string}>
     */
    public function getAllTypes(): array;
}
