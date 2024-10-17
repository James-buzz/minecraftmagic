<?php

namespace Tests\Feature\Http\Controllers\Legal\PrivacyController;

use Inertia\Testing\AssertableInertia as AssertInertia;

class IndexTest extends BasePrivacyController
{
    public function testIndex(): void
    {
        // Given
        $givenRoute = 'privacy';
        $givenInertiaAsset = 'legal/Privacy';

        // Action
        $response = $this->get(route($givenRoute));

        // Assert
        $response->assertOk();
        $response->assertInertia(
            fn (AssertInertia $page) => $page
                ->component($givenInertiaAsset)
        );
    }
}
