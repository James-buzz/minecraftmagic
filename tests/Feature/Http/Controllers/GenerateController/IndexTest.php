<?php

namespace Tests\Feature\Http\Controllers\GenerateController;

use App\Models\User;

class IndexTest extends BaseGenerateController
{
    public function testWhenIndexThenResolve(): void
    {
        // Precondition
        $preconditionUser = User::factory()->create();

        // Action
        $actualResponse = $this->actingAs($preconditionUser)
            ->get(route($this->indexRoute));

        // Assert
        $actualResponse->assertOk();
        $actualResponse->assertInertia(
            fn ($assert) => $assert
                ->component('generate')
                ->has('art_types')
        );
    }
}
