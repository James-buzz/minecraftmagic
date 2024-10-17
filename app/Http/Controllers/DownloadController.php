<?php

namespace App\Http\Controllers;

use App\Services\GenerationRetrievalService;
use Illuminate\Http\JsonResponse;

class DownloadController extends Controller
{
    public function __construct(protected readonly GenerationRetrievalService $retrievalService) {}

    /**
     * Retrieve the download URL for a generation.
     */
    public function show(string $generationId): JsonResponse
    {
        $userId = auth()->id();

        return response()->json([
            'url' => $this->retrievalService->getGenerationFileUrl($userId, $generationId),
        ]);
    }
}
