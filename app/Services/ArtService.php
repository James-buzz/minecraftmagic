<?php

namespace App\Services;

use App\Contracts\ArtRepositoryInterface;
use App\Contracts\ArtServiceInterface;

readonly class ArtService implements ArtServiceInterface
{
    public function __construct(protected ArtRepositoryInterface $artRepository)
    {
    }

    public function getArtStyle(string $artType, string $artStyle): array
    {
        return $this->artRepository->getArtStyleForArtType($artType, $artStyle);
    }

    public function getAllArtTypesWithStyles(): array
    {
        $types = $this->artRepository->getArtTypes();
        $result = [];
        foreach ($types as $type) {
            $type['styles'] = $this->artRepository->getArtStylesForArtType($type['identifier']);
            $result[] = $type;
        }

        return $result;
    }

    public function getAllArtTypes(): array
    {
        return $this->artRepository->getArtTypes();
    }
}
