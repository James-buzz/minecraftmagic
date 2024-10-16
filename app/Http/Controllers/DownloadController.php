<?php

namespace App\Http\Controllers;

use App\Services\GenerationRetrievalService;

class DownloadController extends Controller
{
    public function __construct(protected readonly GenerationRetrievalService $retrievalService) {}

    /**
     * Retrieve the download URL for a generation.
     */
    public function show(string $generationId): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'url' => $this->retrievalService->getGenerationFileUrl($generationId),
        ]);
    }
}
