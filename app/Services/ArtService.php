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
        return $this->artRepository->getStyleForType($artType, $artStyle);
    }

    public function getAllTypesWithStyles(): array
    {
        $types = $this->artRepository->getTypes();
        $result = [];
        foreach ($types as $type) {
            $type['styles'] = $this->artRepository->getStylesForType($type['identifier']);
            $result[] = $type;
        }
        return $result;
    }


    public function getAllTypes(): array
    {
        return $this->artRepository->getTypes();
    }
}
