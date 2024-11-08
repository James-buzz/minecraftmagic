<?php

namespace App\Http\Controllers;

use App\Actions\SignS3URL;
use App\Models\Generation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class DownloadController extends Controller
{
    public function __construct() {}

    /**
     * Retrieve the download URL for a generation.
     */
    public function show(Generation $generation): JsonResponse
    {
        abort_if(! Gate::allows('view', $generation), 403);

        Log::info('User requested download', ['user_id' => auth()->id(), 'generation_id' => $generation->id]);

        $temporarySignedURL = SignS3URL::run(
            $generation->file_path,
            now()->addMinutes(5)
        );

        return response()->json([
            'url' => $temporarySignedURL,
        ]);
    }
}
