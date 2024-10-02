<?php

namespace App\Http\Controllers;

use App\Contracts\GenerationServiceInterface;
use Inertia\Inertia;

class StatusController extends Controller
{
    public function __construct(protected readonly GenerationServiceInterface $generationService) {}

    public function show(string $id): \Inertia\Response
    {
        $status = $this->generationService->getGeneration($id);

        return Inertia::render('status', ['status' => $status]);
    }
}
