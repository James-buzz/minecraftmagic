<?php

namespace Tests\Feature\Http\Controllers\AboutController;

use Inertia\Testing\AssertableInertia as AssertInertia;

class IndexTest extends BaseAboutController
{
    public function testIndex(): void
    {
        // Given
        $givenRoute = 'about';
        $givenInertiaAsset = 'About';

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
