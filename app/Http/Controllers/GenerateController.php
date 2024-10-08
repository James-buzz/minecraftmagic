<?php

namespace App\Http\Controllers;

use App\Contracts\ArtServiceInterface;
use App\Http\Requests\GenerateStoreRequest;
use App\Services\GenerationCreationService;
use Inertia\Inertia;

class GenerateController extends Controller
{
    public function __construct(
        protected readonly ArtServiceInterface $artService,
        protected readonly GenerationCreationService $generationService
    ) {
    }

    public function index(): \Inertia\Response
    {
        return Inertia::render('generate', [
            'art_types' => $this->artService->getAllArtTypesWithStyles(),
        ]);
    }

    public function show(string $generationId): void
    {
        // TODO: download the generated art
    }

    public function store(GenerateStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        /** @var int $userId */
        $userId = auth()->id();

        /** @var string $artType */
        $artType = $validated['art_type'];
        /** @var string $artStyle */
        $artStyle = $validated['art_style'];
        /** @var array<string, string> $metadata */
        $metadata = $validated['metadata'];

        $recordId = $this->generationService->createGeneration(
            $userId,
            $artType,
            $artStyle,
            $metadata
        );

        return redirect()->route('status', ['id' => $recordId]);
    }
}
