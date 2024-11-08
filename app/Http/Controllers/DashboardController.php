<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GenerationRetrievalService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        protected readonly GenerationRetrievalService $generationService
    ) {}

    /**
     * Display the dashboard.
     */
    public function index(): Response
    {
        /** @var int $currentPage */
        $currentPage = request()->query('page', '1');

        /** @var User $user */
        $user = auth()->user();

        // TODO: fix
        $paginatedGenerations = $this->generationService->getPaginatedGenerations(
            $user,
            $currentPage
        );

        return Inertia::render('Dashboard', [
            'paginatedGenerations' => $paginatedGenerations,
        ]);
    }
}
