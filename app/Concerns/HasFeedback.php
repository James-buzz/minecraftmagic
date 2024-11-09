<?php

namespace App\Concerns;

use App\Models\Feedback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Add feedback to a model.
 *
 * @template TModel of Model
 */
trait HasFeedback
{
    /**
     * Get all feedback for this model.
     *
     * @return MorphMany<Feedback, $this>
     */
    public function feedback(): MorphMany
    {
        return $this->morphMany(Feedback::class, 'feedbackable');
    }

    /**
     * Add feedback to this model.
     *
     * @param  array<string, mixed>  $data
     */
    public function addFeedback(array $data): Feedback
    {
        return $this->feedback()->create(array_merge($data, [
            'user_id' => auth()->id(),
        ]));
    }
}
