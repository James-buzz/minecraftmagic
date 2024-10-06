<?php

namespace App\Contracts;

interface ArtRepositoryInterface
{
    public function getArtTypes(): array;

    public function getArtStylesForArtType(string $typeIdentifier): array;

    public function getArtTypeByIdentifier(string $typeIdentifier): ?array;

    public function getArtStyleForArtType(string $typeIdentifier, string $styleIdentifier): ?array;
}
