<?php

namespace App\Http\Controllers;

use App\Services\GenerationRetrievalService;
use Inertia\Inertia;
use Inertia\Response;

class StatusController extends Controller
{
    public function __construct(protected readonly GenerationRetrievalService $retrievalService) {}

    /**
     * Display the status page.
     */
    public function show(string $id): Response
    {
        $userId = auth()->id();

        $status = $this->retrievalService->getGeneration($userId, $id);

        return Inertia::render('Status', ['status' => $status]);
    }
}
