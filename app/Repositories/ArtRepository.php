<?php

namespace App\Repositories;

use App\Contracts\ArtRepositoryInterface;
use Illuminate\Support\Facades\File;

class ArtRepository implements ArtRepositoryInterface
{
    private array $data;

    public function __construct()
    {
        $jsonPath = resource_path('art.json');
        $jsonContent = File::get($jsonPath);
        $this->data = json_decode($jsonContent, true);
    }

    public function getArtTypes(): array
    {
        return array_map(function ($type) {
            return [
                'identifier' => $type['identifier'],
                'displayName' => $type['name'],
            ];
        }, $this->data['types']);
    }

    public function getArtStylesForArtType(string $typeIdentifier): array
    {
        $type = $this->getArtTypeByIdentifier($typeIdentifier);
        if (! $type) {
            return [];
        }

        return array_values($type['styles']);
    }

    public function getArtTypeByIdentifier(string $typeIdentifier): ?array
    {
        foreach ($this->data['types'] as $type) {
            if ($type['identifier'] === $typeIdentifier) {
                return $type;
            }
        }

        return null;
    }

    public function getArtStyleForArtType(string $typeIdentifier, string $styleIdentifier): ?array
    {
        $type = $this->getArtTypeByIdentifier($typeIdentifier);
        if (! $type) {
            return null;
        }

        foreach ($type['styles'] as $style) {
            if ($style['identifier'] === $styleIdentifier) {
                return $style;
            }
        }

        return null;
    }
}
