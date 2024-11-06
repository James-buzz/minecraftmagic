<?php

namespace App\Http\Controllers;

use App\Models\Generation;
use App\Models\User;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Generation $generation, Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'comment' => 'nullable|string|max:255',
            'rating' => 'required|integer|between:1,5',
        ]);

        /** @var User $user */
        $user = auth()->user();

        $generation->feedback()->create([
            'user_id' => $user->id,
            'comment' => $request->comment ?? null,
            'rating' => $request->rating,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Your feedback has been submitted successfully.');
    }
}
