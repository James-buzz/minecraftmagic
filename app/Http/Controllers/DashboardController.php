<?php

namespace App\Http\Controllers;

use App\Services\GenerationRetrievalService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        protected readonly GenerationRetrievalService $generationService
    ) {
    }

    public function __invoke(): \Inertia\Response
    {
        $currentPage = request()->query('page', 1);

        $paginatedGenerations = $this->generationService->getPaginatedGenerations(
            auth()->id(),
            $currentPage
        );

        return Inertia::render('dashboard', [
            'paginatedGenerations' => $paginatedGenerations,
        ]);
    }
}
