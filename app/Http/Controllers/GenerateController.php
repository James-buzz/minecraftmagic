<?php

namespace App\Http\Controllers;

use App\Events\Generation\GenerationQueued;
use App\Http\Requests\GenerateStoreRequest;
use App\Jobs\ProcessGenerationJob;
use App\Models\ArtType;
use App\Models\Generation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class GenerateController extends Controller
{
    public function __construct(
    ) {}

    /**
     * Show the generation form.
     */
    public function index(): Response
    {
        $allArtTypes = ArtType::all()->each->load('styles');

        // ArtTypes is a key value array
        // loop and then return an array of object with id, name, and styles as the values

        return Inertia::render('Generate', [
            'art_types' => $allArtTypes,
        ]);
    }

    /**
     * Store a new generation.
     */
    public function store(GenerateStoreRequest $request): RedirectResponse
    {
        abort_if(! Gate::allows('create', Generation::class), 403);

        $generation = $request->user()->generations()->create([
            'art_style_id' => $request->art_style,
            'metadata' => $request->metadata,
        ]);

        ProcessGenerationJob::dispatch($generation);

        event(new GenerationQueued($generation));

        Log::info('User generated art', ['user_id' => $generation->user_id, 'generation_id' => $generation->id]);

        return redirect()->route('status', ['generation' => $generation->id]);
    }
}
