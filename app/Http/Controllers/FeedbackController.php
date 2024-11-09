<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use App\Models\Generation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class FeedbackController extends Controller
{
    public function store(Generation $generation, FeedbackRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = auth()->user();

        $generation->feedback()->create([
            'user_id' => $user->id,
            'comment' => $validated['comment'] ?? null,
            'rating' => $validated['rating'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Your feedback has been submitted successfully.');
    }
}
