<?php

namespace App\Concerns;

use App\Models\Feedback;

trait HasFeedback
{
    /**
     * Get all feedback for this model.
     */
    public function feedback()
    {
        return $this->morphMany(Feedback::class, 'feedbackable');
    }

    /**
     * Add feedback to this model.
     */
    public function addFeedback(array $data)
    {
        return $this->feedback()->create(array_merge($data, [
            'user_id' => auth()->id(),
        ]));
    }
}
