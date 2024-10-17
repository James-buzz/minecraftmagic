<?php

namespace App\Http\Controllers;

use App\Contracts\ArtServiceInterface;
use App\Http\Requests\GenerateStoreRequest;
use App\Jobs\ProcessGenerationJob;
use App\Services\GenerationCreationService;
use Inertia\Inertia;
use Inertia\Response;

class GenerateController extends Controller
{
    public function __construct(
        protected readonly ArtServiceInterface $artService,
        protected readonly GenerationCreationService $generationService
    ) {}

    /**
     * Show the generation form.
     */
    public function index(): Response
    {
        return Inertia::render('generate', [
            'art_types' => $this->artService->getAllArtTypesWithStyles(),
        ]);
    }

    public function show(string $generationId): void
    {
        // TODO: page to preview the generated art
        // comment, like, share, etc.
    }

    /**
     * Store a new generation.
     */
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

        ProcessGenerationJob::dispatch($userId, $recordId);

        return redirect()->route('status', ['id' => $recordId]);
    }
}
