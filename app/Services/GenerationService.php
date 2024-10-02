<?php

namespace App\Services;

use App\Contracts\ArtRepositoryInterface;
use App\Contracts\GenerationRepositoryInterface;
use App\Contracts\GenerationServiceInterface;
use App\Jobs\ProcessGenerationJob;

readonly class GenerationService implements GenerationServiceInterface
{
    public function __construct(
        protected readonly GenerationRepositoryInterface $generationRepository,
        protected readonly ArtRepositoryInterface $artRepository
    )
    {
    }



    public function getGenerationFilePath(string $generationId): string
    {
        return sprintf(
            '/generations/%s/%s.%s',
            auth()->id(),
            $generationId,
            'png'
        );
    }

    public function requestGeneration(int $userId, string $artType, string $artStyle, array $metadata): string
    {
       $generationID = $this->generationRepository->create($userId, $artType, $artStyle, $metadata);

       ProcessGenerationJob::dispatch($generationID);

       return $generationID;
    }

    public function getGeneration(string $generationId): array
    {
        return $this->generationRepository->find($generationId);
    }

    public function getGenerationStyle(string $generationId): array
    {
        $record = $this->generationRepository->find($generationId);

        $artType = $this->artRepository->getTypeByIdentifier($record['art_type']);
        $artStyle = $this->artRepository->getStyleForType($record['art_type'], $record['art_style']);

        return [
            'id' => $record['id'],
            'status' => $record['status'],
            'art_type' => $artType['name'],
            'art_style' => $artStyle['name'],
            'metadata' => $record['metadata'],
        ];
    }

    public function updateStatus(string $generationId, string $status): void
    {
        $this->generationRepository->update($generationId, ['status' => $status]);
    }

    public function updateFilePath(string $generationId, string $filePath): void
    {
        $this->generationRepository->update($generationId, ['file_path' => $filePath]);
    }
}
