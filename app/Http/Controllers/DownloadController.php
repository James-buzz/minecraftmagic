<?php

namespace App\Http\Controllers;

use App\Services\GenerationRetrievalService;

class DownloadController extends Controller
{
    public function __construct(protected readonly GenerationRetrievalService $retrievalService)
    {
    }

    public function __invoke(string $generationId): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'url' => $this->retrievalService->getGenerationFileUrl($generationId)
        ]);
    }
}
