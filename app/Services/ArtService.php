<?php

namespace App\Services;

use App\Contracts\ArtRepositoryInterface;
use App\Contracts\ArtServiceInterface;
use App\Exceptions\ArtStyleNotFoundException;

readonly class ArtService implements ArtServiceInterface
{
    public function __construct(protected ArtRepositoryInterface $artRepository) {}

    public function getArtStyle(string $artTypeId, string $artStyleId): array
    {
        $style = $this->artRepository->getStyle($artTypeId, $artStyleId);

        if ($style === null) {
            throw new ArtStyleNotFoundException($artTypeId, $artStyleId);
        }

        return $style;
    }

    public function getAllArtTypesWithStyles(): array
    {
        $types = $this->artRepository->getTypes();
        $result = [];
        foreach ($types as $type) {
            $type['styles'] = $this->artRepository->getStyles($type['id']);
            $result[] = $type;
        }

        return $result;
    }

    public function getAllArtTypes(): array
    {
        return $this->artRepository->getTypes();
    }
}
