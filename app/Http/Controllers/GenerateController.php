<?php

namespace App\Http\Controllers;

use App\Contracts\ArtServiceInterface;
use App\Contracts\GenerationServiceInterface;
use App\Http\Requests\GenerateStoreRequest;
use Inertia\Inertia;

class GenerateController extends Controller
{
    public function __construct(
        protected readonly ArtServiceInterface $artService,
        protected readonly GenerationServiceInterface $generationService
    ) {}

    public function index(): \Inertia\Response
    {
        return Inertia::render('generate', [
            'art_types' => $this->artService->getAllTypesWithStyles(),
        ]);
    }

    public function show(): void
    {
        // TODO: show image / generation
    }

    public function store(GenerateStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        $recordId = $this->generationService->requestGeneration(
            auth()->id(),
            $validated['art_type'],
            $validated['art_style'],
            $validated['metadata']
        );

        return redirect()->route('status', ['id' => $recordId]);
    }
}
