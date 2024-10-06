<?php

declare(strict_types=1);

namespace App\Contracts;

interface ArtServiceInterface
{
    public function getArtStyle(string $artType, string $artStyle): array;

    public function getAllArtTypesWithStyles(): array;

    public function getAllArtTypes(): array;
}
