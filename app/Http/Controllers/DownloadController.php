<?php

namespace App\Http\Controllers;

use App\Services\GenerationRetrievalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DownloadController extends Controller
{
    public function __construct(protected readonly GenerationRetrievalService $retrievalService) {}

    /**
     * Retrieve the download URL for a generation.
     */
    public function show(string $id): JsonResponse
    {
        $userId = auth()->id();

        Log::info('User requested download', ['user_id' => $userId, 'generation_id' => $id]);

        return response()->json([
            'url' => $this->retrievalService->getGenerationFileUrl($userId, $id),
        ]);
    }
}
