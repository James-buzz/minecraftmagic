<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\ArtRepositoryInterface;
use App\Models\Archive\ArtStyle;
use App\Models\Archive\ArtType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

readonly class ArtRepository implements ArtRepositoryInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $data;

    public function __construct()
    {
        $this->data = Cache::remember('art_data', 3600, function (): array {
            try {
                $jsonPath = resource_path('art.json');
                $jsonContent = File::get($jsonPath);

                return json_decode($jsonContent, true, flags: JSON_THROW_ON_ERROR) ?? [];
            } catch (\Exception $exception) {
                report($exception);

                return [];
            }
        });
    }

    /**
     * @return ArtType[]
     */
    public function getTypes(): array
    {
        return array_map(
            fn (array $type): ArtType => new ArtType(
                id: $type['id'],
                name: $type['name'],
            ),
            $this->data['types'] ?? []
        );
    }

    public function getType(string $typeId): ?ArtType
    {
        $type = $this->findType($typeId);

        if ($type === null) {
            return null;
        }

        return new ArtType(
            id: $type['id'],
            name: $type['name'],
        );
    }

    /**
     * @return ArtStyle[]
     */
    public function getStyles(string $typeId): array
    {
        $type = $this->findType($typeId, includeStyles: true);

        if ($type === null) {
            return [];
        }

        return array_map(
            fn (array $style): ArtStyle => new ArtStyle(
                id: $style['id'],
                name: $style['name'],
                description: $style['description'],
                prompt: $style['prompt'],
            ),
            $type['styles'] ?? []
        );
    }

    public function getStyle(string $typeId, string $styleId): ?ArtStyle
    {
        $style = $this->findStyleWithPrompt($typeId, $styleId);

        if ($style === null) {
            return null;
        }

        return new ArtStyle(
            id: $style['id'],
            name: $style['name'],
            description: $style['description'],
            prompt: $style['prompt'],
        );
    }

    /**
     * Find an art type by its identifier
     *
     * @return array{id: string, name: string, styles?: array<array{id: string, name: string, description: string, prompt: string}>}|null
     */
    private function findType(string $typeId, bool $includeStyles = false): ?array
    {
        $type = array_values(array_filter(
            $this->data['types'] ?? [],
            fn (array $type): bool => $type['id'] === $typeId
        ))[0] ?? null;

        if ($type === null) {
            return null;
        }

        return $includeStyles ? $type : ['id' => $type['id'], 'name' => $type['name']];
    }

    /**
     * Find a style with its prompt
     *
     * @return array{id: string, name: string, description: string, prompt: string}|null
     */
    private function findStyleWithPrompt(string $typeId, string $styleId): ?array
    {
        $type = $this->findType($typeId, includeStyles: true);

        if ($type === null) {
            return null;
        }

        return array_values(array_filter(
            $type['styles'] ?? [],
            fn (array $style): bool => $style['id'] === $styleId
        ))[0] ?? null;
    }
}
