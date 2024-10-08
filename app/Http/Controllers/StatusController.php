<?php

namespace App\Http\Controllers;

use App\Services\GenerationRetrievalService;
use Inertia\Inertia;

class StatusController extends Controller
{
    public function __construct(protected readonly GenerationRetrievalService $retrievalService)
    {
    }

    /**
     * Display the status page.
     *
     * @param string $id
     * @return \Inertia\Response
     */
    public function show(string $id): \Inertia\Response
    {
        $status = $this->retrievalService->getGeneration($id);

        return Inertia::render('status', ['status' => $status]);
    }
}
