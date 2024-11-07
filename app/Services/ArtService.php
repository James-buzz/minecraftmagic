<?php

namespace App\Services;

use App\Contracts\ArtRepositoryInterface;
use App\Contracts\ArtServiceInterface;
use App\Models\ArtStyle;
use App\Models\ArtType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

readonly class ArtService implements ArtServiceInterface
{
    public function __construct(protected ArtRepositoryInterface $artRepository) {}

    public function getArtType(string $artTypeId): ArtType
    {
        $type = $this->artRepository->getType($artTypeId);

        if ($type === null) {
            throw new ModelNotFoundException("Art type {$artTypeId} not found");
        }

        return $type;
    }

    public function getArtStyle(string $artTypeId, string $artStyleId): ArtStyle
    {
        $style = $this->artRepository->getStyle($artTypeId, $artStyleId);

        if ($style === null) {
            throw new ModelNotFoundException("Art style {$artStyleId} not found");
        }

        return $style;
    }

    public function getAllArtTypesWithStyles(): array
    {
        $types = $this->artRepository->getTypes();

        return array_map(
            fn ($type) => [
                'id' => $type->id,
                'name' => $type->name,
                'styles' => $this->artRepository->getStyles($type->id),
            ],
            $types
        );
    }

    public function getAllArtTypes(): array
    {
        return $this->artRepository->getTypes();
    }
}
