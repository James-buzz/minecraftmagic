<?php

namespace App\Contracts;

interface ArtRepositoryInterface
{
    /**
     * Get all art types (without styles)
     *
     * @return array<array{id: string, name: string}>
     */
    public function getTypes(): array;

    /**
     * Get an art type by its identifier (without styles)
     *
     * @return array{id: string, name: string}|null
     */
    public function getType(string $typeId): ?array;

    /**
     * Get an art type by its identifier (including styles)
     *
     * @return array{id: string, name: string, styles: array<array{id: string, name: string, description: string}>}|null
     */
    public function getTypeWithStyles(string $typeId): ?array;

    /**
     * Get all art styles for a given art type
     *
     * @return array<array{id: string, name: string, description: string}>
     */
    public function getStyles(string $typeId): array;

    /**
     * Get an art style for a given art type
     *
     * @return array{id: string, name: string, description: string, prompt: string}|null
     */
    public function getStyle(string $typeId, string $styleId): ?array;
}
