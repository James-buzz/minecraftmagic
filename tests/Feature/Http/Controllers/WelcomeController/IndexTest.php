<?php

namespace Feature\Http\Controllers\WelcomeController;

use Inertia\Testing\AssertableInertia as AssertInertia;
use Tests\Feature\Http\Controllers\DashboardController\BaseDashboardController;

class IndexTest extends BaseDashboardController
{
    public function testWhenThenRoute(): void
    {
        // Given
        $givenRoute = 'welcome';
        $givenInertiaAsset = 'welcome';

        // Action
        $response = $this
            ->get(route($givenRoute));

        // Assert
        $response->assertOk();
        $response->assertInertia(
            fn (AssertInertia $page) => $page
                ->component($givenInertiaAsset)
        );
    }
}
