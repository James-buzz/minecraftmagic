<?php

namespace App\Http\Controllers;

use App\Contracts\ArtServiceInterface;
use App\Events\Generation\GenerationQueued;
use App\Http\Requests\GenerateStoreRequest;
use App\Jobs\ProcessGenerationJob;
use App\Models\ArtType;
use App\Models\User;
use App\Services\GenerationService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class GenerateController extends Controller
{
    public function __construct(
        protected readonly ArtServiceInterface $artService,
        protected readonly GenerationService $generationService
    ) {}

    /**
     * Show the generation form.
     */
    public function index(): Response
    {
        $artTypes = $this->artService->getAllArtTypesWithStyles();

        // ArtTypes is a key value array
        // loop and then return an array of object with id, name, and styles as the values


        return Inertia::render('Generate', [
            'art_types' => $artTypes,
        ]);
    }

    /**
     * Store a new generation.
     */
    public function store(GenerateStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = auth()->user();

        /** @var string $artTypeId */
        $artTypeId = $validated['art_type'];
        /** @var string $artStyleId */
        $artStyleId = $validated['art_style'];
        /** @var array<string, string> $metadata */
        $metadata = $validated['metadata'];

        $artType = $this->artService->getArtType($artTypeId);

        $artStyle = $this->artService->getArtStyle($artTypeId, $artStyleId);

        $record = $this->generationService->createGeneration(
            $user,
            $artType,
            $artStyle,
            $metadata
        );

        ProcessGenerationJob::dispatch($user, $record);

        event(new GenerationQueued($artType->id, $artStyle->id));

        Log::info('User generated art', ['user_id' => $user->id, 'generation_id' => $record->id]);

        return redirect()->route('status', ['generation' => $record->id]);
    }
}
