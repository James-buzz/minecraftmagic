<?php

namespace App\Http\Controllers;

use App\Services\GenerationRetrievalService;
use Inertia\Inertia;

class StatusController extends Controller
{
    public function __construct(protected readonly GenerationRetrievalService $retrievalService) {}

    /**
     * Display the status page.
     */
    public function show(string $id): \Inertia\Response
    {
        $userId = auth()->id();

        $status = $this->retrievalService->getGeneration($userId, $id);

        return Inertia::render('status', ['status' => $status]);
    }
}
