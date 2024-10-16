<?php

namespace Tests\Feature\Http\Controllers\DashboardController;

use App\Models\Generation;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as AssertInertia;

class IndexTest extends BaseDashboardController
{
    public function testWhenThenRoute(): void
    {
        // Given
        $givenRoute = 'dashboard';
        $givenInertiaAsset = 'dashboard';
        $givenGenerationCount = 2;
        $givenUserId = 118;

        // Precondition
        Storage::fake('s3');

        $user = User::factory()->create([
            'id' => $givenUserId,
        ]);

        Generation::factory()
            ->count($givenGenerationCount)
            ->create([
                'user_id' => $givenUserId,
            ]);

        // Action
        $response = $this
            ->actingAs($user)
            ->get(route($givenRoute));

        // Assert
        $response->assertOk();
        $response->assertInertia(
            fn (AssertInertia $page) => $page
                ->component($givenInertiaAsset)
                ->has('paginatedGenerations')
        );
    }
}
