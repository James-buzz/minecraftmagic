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
        /** @var int $currentPage */
        $currentPage = request()->query('page', '1');

        /** @var int $userId */
        $userId = auth()->id();

        $paginatedGenerations = $this->generationService->getPaginatedGenerations(
            $userId,
            $currentPage
        );

        return Inertia::render('dashboard', [
            'paginatedGenerations' => $paginatedGenerations,
        ]);
    }
}
