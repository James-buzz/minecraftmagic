<?php

namespace Tests\Feature\Models\Generation;

use App\Models\Generation;
use App\Models\User;
use Tests\Feature\FeatureTestCase;

class UserTest extends FeatureTestCase
{
    public function testWhenGenerationBelongsToThenTrue(): void
    {
        // Given
        $givenUserId = 1;
        $givenGenerationId = 2;

        // Precondition
        User::factory()->create([
            'id' => $givenUserId,
        ]);

        Generation::factory()->create([
            'id' => $givenGenerationId,
            'user_id' => $givenUserId,
        ]);

        // Expected
        $expectedUserId = $givenUserId;

        // Action
        $actualUser = Generation::find($givenGenerationId)->user;

        // Assert
        $this->assertEquals($expectedUserId, $actualUser->id);
    }
}
