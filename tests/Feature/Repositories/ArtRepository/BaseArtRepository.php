<?php

namespace Tests\Feature\Repositories\ArtRepository;

use App\Repositories\ArtRepository;
use Tests\Feature\FeatureTestCase;

class BaseArtRepository extends FeatureTestCase
{
    protected ArtRepository $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new ArtRepository;
    }

    protected function preconditionJsonArtFile(): array
    {
        $jsonFile = resource_path('art.json');
        $jsonContent = file_get_contents($jsonFile);

        return json_decode($jsonContent, true);
    }

    protected function preconditionExtractTypes(array $jsonContent): array
    {
        $typeData = $jsonContent['types'];

        return array_map(function ($type) {
            unset($type['styles']);

            return $type;
        }, $typeData);
    }

    protected function preconditionExtractType(array $jsonContent, string $typeId, bool $withoutStyles = true): array
    {
        foreach ($jsonContent['types'] as $typeData) {
            if ($typeData['id'] === $typeId) {
                if ($withoutStyles) {
                    unset($typeData['styles']);
                }

                return $typeData;
            }
        }

        return [];
    }

    protected function preconditionExtractStyles(array $jsonContent, string $typeId): array
    {
        foreach ($jsonContent['types'] as $typeData) {
            if ($typeData['id'] === $typeId) {
                return $typeData['styles'];
            }
        }

        return [];
    }

    protected function preconditionExtractStyle(array $jsonContent, string $typeId, string $styleId): array
    {
        $styles = $this->preconditionExtractStyles($jsonContent, $typeId);

        foreach ($styles as $styleData) {
            if ($styleData['id'] === $styleId) {
                return $styleData;
            }
        }

        return [];
    }
}
