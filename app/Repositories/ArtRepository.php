<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\ArtRepositoryInterface;
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
            } catch (\Exception $e) {
                report($e);
                return [];
            }
        });
    }

    public function getTypes(): array
    {
        return array_map(
            fn (array $type): array => [
                'id' => $type['id'],
                'name' => $type['name'],
            ],
            $this->data['types'] ?? []
        );
    }

    public function getType(string $typeId): ?array
    {
        return $this->findType($typeId, includeStyles: false);
    }

    public function getTypeWithStyles(string $typeId): ?array
    {
        $type = $this->findType($typeId, includeStyles: true);

        if ($type === null) {
            return null;
        }

        $type['styles'] = array_map(function ($style) {
            return [
                'id' => $style['id'],
                'name' => $style['name'],
                'description' => $style['description']
                // filter out prompt
            ];
        }, $type['styles'] ?? []);

        return $type;
    }

    public function getStyles(string $typeId): array
    {
        $type = $this->getTypeWithStyles($typeId);

        return $type['styles'] ?? [];
    }

    public function getStyle(string $typeId, string $styleId): ?array
    {
        $styles = $this->getStyles($typeId);
        $style = $styles[array_search($styleId, array_column($styles, 'id'), true)] ?? null;

        if ($style === null) {
            return null;
        }

        // Include the prompt only for this method
        $fullStyle = $this->findStyleWithPrompt($typeId, $styleId);
        return $fullStyle ? array_merge($style, ['prompt' => $fullStyle['prompt']]) : null;
    }

    /**
     * Find an art type by its identifier
     *
     * @param string $typeId
     * @param bool $includeStyles
     * @return array{id: string, name: string}|array{id: string, name: string, styles: array<array{id: string, name: string, description: string}>}|null
     */
    private function findType(string $typeId, bool $includeStyles): ?array
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
     * @param string $typeId
     * @param string $styleId
     * @return array<string, mixed>|null
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
