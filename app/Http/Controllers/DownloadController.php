<?php

namespace App\Http\Controllers;

use App\Models\Generation;
use App\Services\GenerationRetrievalService;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class DownloadController extends Controller
{
    use HandlesAuthorization;

    public function __construct(protected readonly GenerationRetrievalService $retrievalService) {}

    /**
     * Retrieve the download URL for a generation.
     */
    public function show(Generation $generation): JsonResponse
    {
        if (! Gate::allows('view', $generation)) {
            abort(403);
        }

        Log::info('User requested download', ['user_id' => auth()->id(), 'generation_id' => $generation->id]);

        $fileUrl = $this->retrievalService->getGenerationFileUrl($generation);

        return response()->json(['url' => $fileUrl]);
    }
}
